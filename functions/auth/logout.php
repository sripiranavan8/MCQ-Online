<?php
require_once '../../config/init.php';

$user = new User();
$user->logout();
Redirect::to('../../login.php');
