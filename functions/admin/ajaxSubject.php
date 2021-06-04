<?php
require_once '../../config/init.php';
// Read value
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
    $searchQuery = " AND (name LIKE :name OR exam_fee LIKE :examFee) ";
    $searchArray = array(
        'name' => "%$searchValue%",
        'examFee' => "%$searchValue%"
    );
}

// Total number of records without filtering
$db = new Db();
$database = $db->connect();
$records = $database->query("SELECT COUNT(*) AS allcount FROM subjects")->fetch();
$totalRecords = $records['allcount'];

// Total number of records with filtering
$stmt = $database->prepare("SELECT COUNT(*) AS allcount FROM subjects WHERE 1 " . $searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordWithFilter = $records['allcount'];

// Fetch records
$stmt = $database->prepare("SELECT * FROM subjects WHERE 1 " . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");

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
    $output = openssl_encrypt($row['id'], Config::get('encryption/method'), Config::get('encryption/key'), 0, Config::get('encryption/iv'));
    $encryptId = base64_encode($output);
    $data[] = array(
        'id' => $count,
        'name' => $row['name'],
        'exam_fee' => 'AUD ' . number_format((float)$row['exam_fee'], 2, '.', ''),
        'created_at' => $row['created_at'],
        'actions' => '<a class="btn btn-sm btn-warning" onclick="viewSubject(\'' . $encryptId . '\')"><i class="bi bi-eye"></i> View</a>
        <a class="btn btn-sm btn-danger" onclick="deleteSubject(\'' . $encryptId . '\')"><i class="bi bi-trash"></i> Delete</a>'
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
