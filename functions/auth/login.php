<?php
require_once '../../config/init.php';
if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array('email' => array('required' => true), 'password' => array('required' => true)));
        if ($validation->passed()) {
            $user = new User();
            $login = $user->login(Input::get('email'), Input::get('password'));
            if ($login) {
                Redirect::to('../../includes/admin/home.php');
            } else {
                Session::put('validation', 'Credentials not match!!');
                Redirect::to('../../login.php');
            }
        } else {
            Session::put('errors', $validation->errors());
            Session::putArray('LoginForm', 'email', Input::get('email'));
            Redirect::to('../../login.php');
        }
    }
}
Redirect::to('../../login.php');
