<?php
require_once '../../config/init.php';
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array('answer' => array('required' => true), 'question' => array('required' => true)));
        if ($validation->passed()) {
            $answer = new Answer();
            $answer->answer = escape(Input::get('answer'));
            $answer->question = escape(Input::get('question'));
            $answer->isCorrect = escape(Input::get('isCorrect'));
            $answer->create();
            Redirect::to('../../includes/admin/questions.php');
        } else {
            Session::put('answerErrors', $validation->errors());
            Session::putArray('addAnswers', 'answer', Input::get('answer'));
            Redirect::to('../../includes/admin/questions.php');
        }
    }
}
Redirect::to('../../includes/admin/questions.php');
