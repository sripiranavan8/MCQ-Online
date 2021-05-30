<?php
include_once("../../config/init.php");
include_once("../../includes/partials/htmlheader.php");
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('../../login.php');
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
                            <a class="nav-link" aria-current="page" href="home.php">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>
                        <hr />
                        <li class="nav-item">
                            <a class="nav-link active" href="subjects.php">
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
                    <h1 class="h2">Exam Subjects</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary active">View Subjects</button>
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#exampleModal">Add new Subject</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="subjectTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Exam fee</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </main>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../../functions/admin/addSubject.php" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="subject">Subject Name</label>
                                <input type="text" name="subject" id="subject" class="form-control <?php if (isset($error)) {
                                                                                                        if ($error['subject']) { ?> is-invalid <?php }
                                                                                                                                        } ?>">
                                <?php
                                if (isset($error)) {
                                    if ($error['subject']) { ?>
                                        <span class="invalid-feedback" role="alert"><?php echo $error['subject']; ?></span>
                                <?php }
                                } ?>
                            </div>
                            <div class="col-12 form-group">
                                <label for="examFee">Exam fee</label>
                                <input type="text" name="examFee" id="examFee" class="form-control <?php if (isset($error)) {
                                                                                                        if ($error['examFee']) { ?> is-invalid <?php }
                                                                                                                                        } ?>">
                                <?php
                                if (isset($error)) {
                                    if ($error['examFee']) { ?>
                                        <span class="invalid-feedback" role="alert"><?php echo $error['examFee']; ?></span>
                                <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    include_once("../../includes/partials/script.php");
    ?>
    <?php
    if (isset($error)) { ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#exampleModal').modal('show');
            });
        </script>
    <?php }
    ?>
    <script>
        function deleteSubject(id) {

        }
        $(document).ready(function() {
            $('#subjectTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '../../functions/admin/ajaxSubject.php'
                },
                'columns': [{
                        data: 'id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'exam_fee'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'actions'
                    }
                ]
            });
        });
    </script>