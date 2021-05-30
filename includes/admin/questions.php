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
$subject = new Subject();
$allSubjects = $subject->getAll();
$token = Token::generate();
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
                            <a class="nav-link" href="subjects.php">
                                <i class="bi bi-journals"></i>
                                Subjects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="questions.php">
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
                    <h1 class="h2">Exam Questions</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary active">View Questions</button>
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#exampleModal">Add new Question</button>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../../functions/admin/addQuestion.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="question">Question</label>
                                <input type="text" name="question" id="question" class="form-control <?php if (isset($error)) {
                                                                                                            if ($error['question']) { ?> is-invalid <?php }
                                                                                                                                            } ?>">
                                <?php
                                if (isset($error)) {
                                    if ($error['question']) { ?>
                                        <span class="invalid-feedback" role="alert"><?php echo $error['question']; ?></span>
                                <?php }
                                } ?>
                            </div>
                            <div class="col-12 form-group">
                                <label for="image">Image (Optional)</label>
                                <input type="file" name="image" id="image" class="form-control">
                            </div>
                            <div class="col-12 form-group">
                                <label for="subject">Subject</label>
                                <select name="subject" id="subject" class="form-control <?php if (isset($error)) {
                                                                                            if ($error['subject']) { ?> is-invalid <?php }
                                                                                                                            } ?>">
                                    <option selected disabled>Please select a Subject</option>
                                    <?php foreach ($allSubjects as $subject) { ?>
                                        <option value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                                    <?php } ?>
                                </select>
                                <?php
                                if (isset($error)) {
                                    if ($error['subject']) { ?>
                                        <span class="invalid-feedback" role="alert"><?php echo $error['subject']; ?></span>
                                <?php }
                                } ?>
                            </div>
                            <div class="col-12 form-group">
                                <label for="is_multiple">Is this question have multiple answers</label>
                                <div>
                                    <div class="form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_multiple_answer" value="0" checked>
                                        <label class="form-check-label">No</label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_multiple_answer" value="1">
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="answerModal" tabindex="-1" aria-labelledby="answerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="answerModalLabel">Add New Answer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../../functions/admin/addAnswers.php" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 form-group">
                                <label for="answer">Answer</label>
                                <input type="text" name="answer" id="answer" class="form-control <?php if (isset($error)) {
                                                                                                        if ($error['answer']) { ?> is-invalid <?php }
                                                                                                                                        } ?>">
                                <?php
                                if (isset($error)) {
                                    if ($error['answer']) { ?>
                                        <span class="invalid-feedback" role="alert"><?php echo $error['answer']; ?></span>
                                <?php }
                                } ?>
                            </div>

                            <div class="col-12 form-group">
                                <label for="isCorrect">Is this answer correct</label>
                                <div>
                                    <div class="form-check-inline">
                                        <input class="form-check-input" type="radio" name="isCorrect" value="0" checked>
                                        <label class="form-check-label">No</label>
                                    </div>
                                    <div class="form-check-inline">
                                        <input class="form-check-input" type="radio" name="isCorrect" value="1">
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="question" id="addQuestionId">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
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
        function addQuestionId(id) {
            $('#addQuestionId').val(id);
        }

        function deleteSubject(id) {

        }
        $(document).ready(function() {
            $('#questionTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '../../functions/admin/ajaxQuestion.php'
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