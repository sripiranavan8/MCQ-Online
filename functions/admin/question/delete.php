<?php
require_once '../../../config/init.php';

$data = $_POST['data'];
$questionId = openssl_decrypt(base64_decode($data), Config::get('encryption/method'), Config::get('encryption/key'), 0, Config::get('encryption/iv'));
$question = new Question();
if ($question->find($questionId)) {
    $questionName = $question->data()->question;
    $answers = $question->getAllAnswersById($questionId);
    if (count($answers) > 0) {
        foreach ($answers as $answer) {
            $tempAnswer = new Answer();
            $tempAnswer->delete($answer[0]);
        }
        $question->delete($questionId);
    } else {
        $question->delete($questionId);
        echo json_encode(['status' => 200, 'message' => $questionName . ' Deleted Successfully']);
    }
} else {
    echo json_encode(['status' => 400, 'message' => 'Question Not found']);
}
