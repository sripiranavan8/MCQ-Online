<?php
include_once('../../config/init.php');
include_once("../../includes/partials/htmlheader.php");
// $_SESSION['isExamFeePaid'] = array('status' => true, 'subject' => 4);
if (Session::exists('isExamFeePaid')) {
    $subject = Session::get('isExamFeePaid')['subject'];
    $exam = new Subject();
    $allQuestions = $exam->getAllQuestionsById($subject);
} else {
    Redirect::to('../../');
}
?>
<div class="container d-flex justify-content-center align-items-center flex-column" style="min-height: 100vh;">
    <?php $questionCount = 0;
    foreach ($allQuestions as $question) {
        $questionCount++; ?>
        <div class="col-md-12">
            <?php echo $questionCount . ')' . $question['question'];
            $questionModel = new Question();
            $allAnswers = $questionModel->getAllAnswersById($question['id']);
            $answerCount = 0;
            foreach ($allAnswers as $answer) {
                $answerCount++;
                if ($question['is_multiple_answer']) { ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="<?php echo $answer['id']; ?>" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            <?php echo $answer['answer']; ?>
                        </label>
                    </div>
                <?php } else { ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" value="<?php echo $answer['id']; ?>" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            <?php echo $answer['answer']; ?>
                        </label>
                    </div>
                <?php }
                ?>
            <?php }
            ?>
        </div>
        <br />
    <?php } ?>
</div>
<?php
include_once("../../includes/partials/script.php");
?>