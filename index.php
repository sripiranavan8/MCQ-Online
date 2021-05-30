<?php
include_once("config/init.php");
include_once("includes/partials/htmlheader.php");

$subjects = new Subject();
$allSubjects = $subjects->getAll();

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

<body>
    <div class="container d-flex justify-content-center align-items-center flex-column" style="min-height: 100vh;">
        <h3>Online Exam</h3>
        <form action="functions/paypal/payments.php" class="paypal" id="paypal_form" method="post">
            <div class="row">
                <div class="form-group col-md-6 offset-md-3">
                    <label for="lastName">First Name</label>
                    <input class="form-control<?php if (isset($error['firstName'])) {
                                                    echo ' is-invalid';
                                                } ?>" type="text" <?php if ($firstNameValue = escape(Session::getArrayValue('userForm', 'firstName'))) {
                                                                    ?> value="<?php echo $firstNameValue; ?>" <?php                } ?> name="firstName" id="firstName" placeholder="Your first name">
                    <?php
                    if (isset($error['firstName'])) { ?>
                        <div class="invalid-feedback">
                            <?php echo $error['firstName']; ?>
                        </div>
                    <?php }
                    ?>
                </div>
                <div class="form-group col-md-6 offset-md-3">
                    <label for="lastName">Last Name</label>
                    <input class="form-control<?php if (isset($error['lastName'])) {
                                                    echo ' is-invalid';
                                                } ?>" type="text" <?php if ($lastNameValue = escape(Session::getArrayValue('userForm', 'lastName'))) {
                                                                    ?> value="<?php echo $lastNameValue; ?>" <?php                } ?> name="lastName" id="lastName" placeholder="Your last name">
                    <?php
                    if (isset($error['lastName'])) { ?>
                        <div class="invalid-feedback">
                            <?php echo $error['lastName']; ?>
                        </div>
                    <?php }
                    ?>
                </div>
                <div class="form-group col-md-6 offset-md-3">
                    <label for="email">Your email address</label>
                    <input class="form-control<?php if (isset($error['email'])) {
                                                    echo ' is-invalid';
                                                } ?>" type="email" <?php if ($emailValue = escape(Session::getArrayValue('userForm', 'email'))) {
                                                                    ?> value="<?php echo $emailValue; ?>" <?php                } ?> name="email" id="email" placeholder="Your email address">
                    <?php
                    if (isset($error['email'])) { ?>
                        <div class="invalid-feedback">
                            <?php echo $error['email']; ?>
                        </div>
                    <?php }
                    ?>
                </div>
                <div class="form-group col-md-6 offset-md-3">
                    <label for="subject">What subject exam you are looking for?</label>
                    <select name="subject" id="subject" class="form-control<?php if (isset($error['subject'])) {
                                                                                echo ' is-invalid';
                                                                            } ?>">
                        <option selected disabled>Select the subject</option>
                        <?php if (count($allSubjects) > 0) {
                            foreach ($allSubjects as $subject) { ?>

                                <?php if ($subjectValue = escape(Session::getArrayValue('userForm', 'subject'))) {
                                    if ($subjectValue == $subject->id) {
                                ?>
                                        <option selected value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                                    <?php
                                    } else {
                                    ?> <option value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option> <?php               }
                                                                                                                    } else { ?>
                                    <option value="<?php echo $subject->id; ?>"><?php echo $subject->name; ?></option>
                        <?php }
                                                                                                                }
                                                                                                            } ?>
                    </select>
                    <?php
                    if (isset($error['subject'])) { ?>
                        <div class="invalid-feedback">
                            <?php echo $error['subject']; ?>
                        </div>
                    <?php }
                    ?>
                </div>
                <div class="form-group col-md-6 offset-md-3 d-flex justify-content-center">
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <button class="btn btn-info m-3" type="submit">Proceed</button>
                </div>
            </div>
        </form>
    </div>
    <?php
    include_once("includes/partials/script.php");
    ?>