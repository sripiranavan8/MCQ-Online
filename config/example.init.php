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
    'urls' => array('root_url' => $server . $directoryPath),
    'root' => array('root_path' => $directoryPath),
    'paypal' => array(
        'clientId' => "Your Paypal ClientId",
        'clientSecret' => "Your PaypalSecret"
    )
);

spl_autoload_register('myAutoLoader');

function myAutoLoader($className)
{
    $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if (file_exists($file = 'classes/system/' . $className . '.php')) {
        require_once $file;
        require_once 'functions/sanitize.php';
    } elseif (file_exists($file = 'classes/models/' . $className . '.php')) {
        require_once $file;
        require_once 'functions/sanitize.php';
    } elseif (strpos($url, '/includes/admin/')) {
        if (file_exists($file = '../../classes/system/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
        if (file_exists($file = '../../classes/models/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
    } elseif (strpos($url, '/includes/paypal/')) {
        if (file_exists($file = '../../classes/system/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
        if (file_exists($file = '../../classes/models/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
    } elseif (strpos($url, '/includes/user/')) {
        if (file_exists($file = '../../classes/system/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
        if (file_exists($file = '../../classes/models/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
    } elseif (strpos($url, '/functions/auth/')) {
        if (file_exists($file = '../../classes/system/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
        if (file_exists($file = '../../classes/models/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
    } elseif (strpos($url, '/functions/admin/')) {
        if (file_exists($file = '../../classes/system/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
        if (file_exists($file = '../../classes/models/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
    } elseif (strpos($url, '/functions/paypal/')) {
        if (file_exists($file = '../../classes/system/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
        if (file_exists($file = '../../classes/models/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
    } elseif (strpos($url, '/functions/user/')) {
        if (file_exists($file = '../../classes/system/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
        if (file_exists($file = '../../classes/models/' . $className . '.php')) {
            require_once $file;
            require_once '../../functions/sanitize.php';
        }
    }
}
