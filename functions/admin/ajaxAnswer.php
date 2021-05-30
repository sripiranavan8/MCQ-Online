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
    $searchQuery = " AND (answer LIKE :answer) ";
    $searchArray = array(
        'answer' => "%$searchValue%"
    );
}

// Total number of records without filtering
$db = new Db();
$database = $db->connect();
$records = $database->query("SELECT COUNT(*) AS allcount FROM answers a LEFT JOIN questions q ON (a.question=q.id)")->fetch();
$totalRecords = $records['allcount'];


// Total number of records with filtering
$stmt = $database->prepare("SELECT COUNT(*) AS allcount FROM answers a  LEFT JOIN questions q ON (a.question=q.id) WHERE 1 " . $searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordWithFilter = $records['allcount'];

// Fetch records
$stmt = $database->prepare("SELECT a.answer as answer, a.is_correct as isCorrect,q.question as question, a.id as answerId FROM answers a LEFT JOIN questions q ON (a.question=q.id)  WHERE 1 " . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");

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
        'answerId' => $count,
        'answer' => $row['answer'],
        'isCorrect' => $row['isCorrect'] == 0 ? 'No' : 'Yes',
        'question' => $row['question'],
        'actions' => '<a class="btn btn-sm btn-danger" onclick="deleteSubject(' . $row['answerId'] . ')"><i class="bi bi-trash"></i> Delete</a>'
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
