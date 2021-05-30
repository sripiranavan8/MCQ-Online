<?php
include_once("config/init.php");
include_once("includes/partials/htmlheader.php");
$user = new User();
if ($user->isLoggedIn()) {
    Redirect::to('includes/admin/home.php');
}
$error = null;
if (Session::exists('errors')) {
    $error = Session::get('errors');
    Session::delete('errors');
}
if (Session::exists('validation')) {
    $validation = Session::get('validation');
    Session::delete('validation');
}
?>

<head>
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>

<body class="text-center">
    <main class="form-signin">
        <form action="functions/auth/login.php" method="POST">
            <img class="mb-4" src="https://getbootstrap.com/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal">Sign in</h1>
            <?php if (isset($validation)) { ?>
                <p class="alert-danger"><?php echo $validation; ?></p>
            <?php } ?>
            <div class="form-floating">
                <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" <?php if ($emailError = escape(Session::getArrayValue('LoginForm', 'email'))) { ?> value="<?php echo $emailError; ?>" <?php } ?> autocomplete="username">
                <label for="email">Email address</label>
                <?php if (isset($error['email'])) { ?>
                    <small class="text-danger"><em><?php echo $error['email']; ?></em></small>
                <?php } ?>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="password" autocomplete="current-password" placeholder="Password">
                <label for="password">Password</label>
                <?php if (isset($error['password'])) { ?>
                    <small class="text-danger"><em><?php echo $error['password']; ?></em></small>
                <?php } ?>
            </div>

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
            <p class="mt-5 mb-3 text-muted">&copy; <?php echo date('Y');  ?></p>
        </form>
    </main>
    <?php
    include_once("includes/partials/script.php");
    ?>