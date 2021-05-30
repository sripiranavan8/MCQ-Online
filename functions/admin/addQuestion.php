<?php
require_once '../../config/init.php';
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array('subject' => array('required' => true), 'question' => array('required' => true)));
        if ($validation->passed()) {
            $question = new Question();
            $question->subject = escape(Input::get('subject'));
            $question->question = escape(Input::get('question'));

            if (escape(Input::get('is_multiple_answer'))) {
                $question->is_multiple_answer = escape(Input::get('is_multiple_answer'));
            }
            if (null !== Input::get('image')) {
                $upload = new Upload();
                $question->image = $upload->insert(Input::get('image'), '../../public/images/question/');
            }

            $question->create();
            Redirect::to('../../includes/admin/questions.php');
        } else {
            Session::put('errors', $validation->errors());
            Session::putArray('addSubject', 'subject', Input::get('subject'));
            Session::putArray('addSubject', 'question', Input::get('question'));
            Redirect::to('../../includes/admin/questions.php');
        }
    }
}
Redirect::to('../../includes/admin/questions.php');
