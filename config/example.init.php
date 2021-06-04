<?php

ob_start();
session_start();
$server = 'http://localhost';
$directoryPath = '/PHP/My/OnlieQuiz/';
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'Your Database User name',
        'password' => 'Your Database Password',
        'db' => 'db_online_exam'
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    ),
    'encryption' => array(
        'key' => 'your Key',
        'method' => 'AES-256-CBC',
        'iv' => 'iv secret key',
    ),
    'urls' => array('root_url' => $server . $directoryPath),
    'root' => array('root_path' => $directoryPath),
    'paypal' => array(
        'clientId' => "Your Paypal ClientId",
        'clientSecret' => "Your PaypalSecret"
    )
);

require_once 'autoloader.php';
