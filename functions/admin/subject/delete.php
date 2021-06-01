<?php
require_once '../../../config/init.php';

$data = $_POST['data'];
$subjectId = openssl_decrypt(base64_decode($data), Config::get('encryption/method'), Config::get('encryption/key'), 0, Config::get('encryption/iv'));
$subject = new Subject();
if ($subject->find($subjectId)) {
    $subjectName = $subject->data()->name;
    $questions = $subject->getAllQuestionsById($subjectId);
    if (count($questions) > 0) {
        $arr = [];
        foreach ($questions as $question) {
            $questionId = $question[0];
            $tempQuestion = new Question();
            $answers = $tempQuestion->getAllAnswersById($questionId);
            if (count($answers) > 0) {
                foreach ($answers as $answer) {
                    $tempAnswer = new Answer();
                    $tempAnswer->delete($answer[0]);
                }
                $question->delete($questionId);
            } else {
                $tempQuestion->delete($questionId);
            }
        }
        $subject->delete($subjectId);
        echo json_encode(['status' => 200, 'message' => $subjectName . ' Deleted Successfully']);
    } else {
        $subject->delete($subjectId);
        echo json_encode(['status' => 200, 'message' => $subjectName . ' Deleted Successfully']);
    }
} else {
    echo json_encode(['status' => 400, 'message' => 'Subject Not found']);
}
