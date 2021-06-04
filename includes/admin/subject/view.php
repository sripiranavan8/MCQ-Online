<?php
require_once '../../../config/init.php';
include_once("../../../includes/partials/htmlheader.php");
$user = new User();
if (!$user->isLoggedIn()) {
    Redirect::to('../../../login.php');
}

$data = $_GET['subject'];
$subjectId = openssl_decrypt(base64_decode($data), Config::get('encryption/method'), Config::get('encryption/key'), 0, Config::get('encryption/iv'));
$subject = new Subject();
$subject->find($subjectId);

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
    <link href="../../../public/css/dashboard.css" rel="stylesheet">
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
                            <a class="nav-link" aria-current="page" href="../home.php">
                                <i class="bi bi-speedometer2"></i>
                                Dashboard
                            </a>
                        </li>
                        <hr />
                        <li class="nav-item">
                            <a class="nav-link" href="../subjects.php">
                                <i class="bi bi-journals"></i>
                                Subjects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active">
                                <i class="bi bi-journal-check"></i>
                                Question
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../history.php">
                                <i class="bi bi-clock-history"></i>
                                History
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?php echo $subject->data()->name; ?>&nbsp; Exam Questions</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <p>Exam Fee: &nbsp;&nbsp;</p>
                            <p><?php echo " AUD " . number_format($subject->data()->exam_fee, 2, '.', ''); ?></p>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm" id="questionTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Image</th>
                                <th>Is Multiple Answer</th>
                                <th>Subject</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <?php
    include_once("../../../includes/partials/script.php");
    ?>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteQuestion(data) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will delete all the questions related answers too!!!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../../../functions/admin/question/delete.php',
                        type: 'post',
                        data: {
                            data
                        },
                        success: function(response) {
                            let res = JSON.parse(response)
                            console.log(res)
                            if (res.status == 200) {
                                Swal.fire(
                                    'Deleted!',
                                    'Subject has been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!'
                                })
                                console.log(res.message);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!'
                            })
                            console.log(textStatus, errorThrown);
                        }
                    });
                }
            })
        }

        function viewQuestion(data) {
            window.location.replace("<?php echo Config::get('urls/root_url') . 'includes/admin/question/view.php?question='; ?>" + data)
        }
        $(document).ready(function() {
            $('#questionTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '../../../functions/admin/subject/ajaxQuestion.php',
                    'type': 'post',
                    'data': {
                        'subjectId': '<?php echo $data; ?>'
                    }
                },
                'columns': [{
                        data: 'questionId'
                    },
                    {
                        data: 'question'
                    },
                    {
                        data: 'image'
                    },
                    {
                        data: 'isMultiple'
                    },
                    {
                        data: 'subject'
                    },
                    {
                        data: 'actions'
                    }
                ]
            });
        });
    </script>