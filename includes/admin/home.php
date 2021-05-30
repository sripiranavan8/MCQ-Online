<?php
include_once("../../config/init.php");
include_once("../../includes/partials/htmlheader.php");
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('../../login.php');
}
?>

<head>
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="../../public/css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>

<body>
    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Online Exam</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <span class="w-100"></span>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="<?php echo Config::get('urls/root_url') ?>functions/auth/logout.php"><i class="bi bi-power"></i> Sign out</a>
            </li>
        </ul>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>
                        <hr />
                        <li class="nav-item">
                            <a class="nav-link" href="subjects.php">
                                <i class="bi bi-journals"></i>
                                Subjects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="questions.php">
                                <i class="bi bi-journal-check"></i>
                                Questions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="answers.php">
                                <i class="bi bi-journal-code"></i>
                                Answers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="history.php">
                                <i class="bi bi-clock-history"></i>
                                History
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <div class="row text-center">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 class="text-white">Subjects</h3>
                                <p>Online exam</p>
                            </div>
                            <div class="icon">
                                <i class="bi bi-journals"></i>
                            </div>
                            <a href="subjects.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 class="text-white">Questions</h3>
                                <p>Online exam</p>
                            </div>
                            <div class="icon">
                                <i class="bi bi-journal-check"></i>
                            </div>
                            <a href="questions.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 class="text-white">Answers</h3>
                                <p>Online exam</p>
                            </div>
                            <div class="icon">
                                <i class="bi bi-journal-code"></i>
                            </div>
                            <a href="answers.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3 class="text-white">History</h3>
                                <p>Online exam</p>
                            </div>
                            <div class="icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <a href="history.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php
    include_once("../../includes/partials/script.php");
    ?>