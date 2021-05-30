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
    $searchQuery = " AND (question LIKE :question) ";
    $searchArray = array(
        'question' => "%$searchValue%"
    );
}

// Total number of records without filtering
$db = new Db();
$database = $db->connect();
$records = $database->query("SELECT COUNT(*) AS allcount FROM questions q LEFT JOIN subjects s ON (q.subject=s.id)")->fetch();
$totalRecords = $records['allcount'];

// Total number of records with filtering
$stmt = $database->prepare("SELECT COUNT(*) AS allcount FROM questions q  LEFT JOIN subjects s ON (q.subject=s.id) WHERE 1 " . $searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordWithFilter = $records['allcount'];

// Fetch records
$stmt = $database->prepare("SELECT q.id as questionId,q.image as questionImage, q.is_multiple_answer as IsMultiple,q.question as question,s.name as subjectName,q.created_at as questionCreatedAt FROM questions q LEFT JOIN subjects s ON (q.subject=s.id)  WHERE 1 " . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");

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
    $data[] = array(
        'questionId' => $count,
        'question' => $row['question'],
        'image' => $row['questionImage'] == null ? 'No Image Available' : '<a target="_blank" href="../../public/images/question/' . $row['questionImage'] . '">' . $row['questionImage'] . '</a>',
        'isMultiple' => $row['IsMultiple'] == 0 ? 'No' : 'Yes',
        'subject' => $row['subjectName'],
        'actions' => '<button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" onclick="addQuestionId(' . $row['questionId'] . ')" data-bs-target="#answerModal"><i class="bi bi-plus-lg"></i> Add answer</button> 
        <a class="btn btn-sm btn-danger" onclick="deleteSubject(' . $row['questionId'] . ')"><i class="bi bi-trash"></i> Delete</a>'
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
