<?php
require_once '../../config/init.php';
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array('subject' => array('required' => true), 'examFee' => array('required' => true)));
        if ($validation->passed()) {
            $subject = new Subject();
            $subject->name = escape(Input::get('subject'));
            $subject->examFee = escape(Input::get('examFee'));
            $subject->slug = str_replace(' ', '_', escape(Input::get('subject')));
            $subject->create();
            Redirect::to('../../includes/admin/subjects.php');
        } else {
            Session::put('errors', $validation->errors());
            Session::putArray('addSubject', 'subject', Input::get('subject'));
            Session::putArray('addSubject', 'examFee', Input::get('examFee'));
            Redirect::to('../../includes/admin/subjects.php');
        }
    }
}
Redirect::to('../../includes/admin/subjects.php');
