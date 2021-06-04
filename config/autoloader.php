<?php
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
    } elseif (strpos($url, '/includes/admin/subject/')) {
        if (file_exists($file = '../../../classes/system/' . $className . '.php')) {
            require_once $file;
            require_once '../../../functions/sanitize.php';
        }
        if (file_exists($file = '../../../classes/models/' . $className . '.php')) {
            require_once $file;
            require_once '../../../functions/sanitize.php';
        }
    } elseif (strpos($url, '/includes/admin/question/')) {
        if (file_exists($file = '../../../classes/system/' . $className . '.php')) {
            require_once $file;
            require_once '../../../functions/sanitize.php';
        }
        if (file_exists($file = '../../../classes/models/' . $className . '.php')) {
            require_once $file;
            require_once '../../../functions/sanitize.php';
        }
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
    } elseif (strpos($url, '/functions/admin/subject/')) {
        if (file_exists($file = '../../../classes/system/' . $className . '.php')) {
            require_once $file;
            require_once '../../../functions/sanitize.php';
        }
        if (file_exists($file = '../../../classes/models/' . $className . '.php')) {
            require_once $file;
            require_once '../../../functions/sanitize.php';
        }
    } elseif (strpos($url, '/functions/admin/question/')) {
        if (file_exists($file = '../../../classes/system/' . $className . '.php')) {
            require_once $file;
            require_once '../../../functions/sanitize.php';
        }
        if (file_exists($file = '../../../classes/models/' . $className . '.php')) {
            require_once $file;
            require_once '../../../functions/sanitize.php';
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
