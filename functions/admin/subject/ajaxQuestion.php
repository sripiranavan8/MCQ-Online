<?php
require_once '../../../config/init.php';
// Read value
$data = $_POST['subjectId'];
$subjectId = openssl_decrypt(base64_decode($data), Config::get('encryption/method'), Config::get('encryption/key'), 0, Config::get('encryption/iv'));

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];
$columnName = $_POST['columns'][$columnIndex]['data'];
$columnSortOrder = $_POST['order'][0]['dir'];
$searchValue = $_POST['search']['value'];

$searchArray = array();

// Search
$searchQuery = '';
if ($searchValue != '') {
    $searchQuery = " AND (q.question LIKE :question OR s.name LIKE :subject) ";
    $searchArray = array(
        'question' => "%$searchValue%",
        'subject' => "$searchValue%"
    );
}

// Total number of records without filtering
$db = new Db();
$database = $db->connect();
$records = $database->query("SELECT COUNT(*) AS allcount FROM questions q LEFT JOIN subjects s ON (q.subject=s.id) WHERE s.id=" . $subjectId)->fetch();
$totalRecords = $records['allcount'];

// Total number of records with filtering
$stmt = $database->prepare("SELECT COUNT(*) AS allcount FROM questions q  LEFT JOIN subjects s ON (q.subject=s.id) WHERE 1 " . $searchQuery . "AND s.id=" . $subjectId);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordWithFilter = $records['allcount'];

// Fetch records
$stmt = $database->prepare("SELECT q.id as questionId,q.image as questionImage, q.is_multiple_answer as IsMultiple,q.question as question,s.name as subjectName,q.created_at as questionCreatedAt FROM questions q LEFT JOIN subjects s ON (q.subject=s.id)  WHERE 1 " . $searchQuery . "AND s.id=" . $subjectId . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");

// Bind values
foreach ($searchArray as $key => $search) {
    $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$subjectRecords = $stmt->fetchAll();

$data = array();
$count = 0;
foreach ($subjectRecords as $row) {
    $count++;
    $output = openssl_encrypt($row['questionId'], Config::get('encryption/method'), Config::get('encryption/key'), 0, Config::get('encryption/iv'));
    $encryptId = base64_encode($output);
    $data[] = array(
        'questionId' => $count,
        'question' => $row['question'],
        'image' => $row['questionImage'] == null ? 'No Image Available' : '<a target="_blank" href="../../public/images/question/' . $row['questionImage'] . '">' . $row['questionImage'] . '</a>',
        'isMultiple' => $row['IsMultiple'] == 0 ? 'No' : 'Yes',
        'subject' => $row['subjectName'],
        'actions' => '<a class="btn btn-sm btn-warning" onclick="viewQuestion(\'' . $encryptId . '\')"><i class="bi bi-eye"></i> View</a> 
        <a class="btn btn-sm btn-danger" onclick="deleteQuestion(\'' . $encryptId . '\')"><i class="bi bi-trash"></i> Delete</a>'
    );
}

// Response
$response = array(
    "draw" => intval($draw),
    'iTotalRecords' => $totalRecords,
    'iTotalDisplayRecords' => $totalRecordWithFilter,
    'aaData' => $data
);
echo json_encode($response);
