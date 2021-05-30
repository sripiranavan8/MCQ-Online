<?php
require "../../vendor/autoload.php";
include "../../config/init.php";

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalHttp\HttpException;

$orderId  = Session::get('orderId');
// unset($_SESSION['orderId']);

$clientId = Config::get("paypal/clientId");
$clientSecret = Config::get("paypal/clientSecret");

$environment = new SandboxEnvironment($clientId, $clientSecret);
$client = new PayPalHttpClient($environment);
$request = new OrdersCaptureRequest($orderId);
$request->prefer('return=representation');
try {
    $response = $client->execute($request);
    $results = $response->result;
    $course = $results->purchase_units[0]->reference_id;
    $userDetails = $results->purchase_units[0]->description;
    $payerName = $results->payer->name->given_name . ' ' . $results->payer->name->surname;
    $payerEmail = $results->payer->email_address;
    $subject = new Subject();
    $subject->find($course);
    $data = [
        'course' => $subject->data(),
        'payerName' => $payerName,
        'payerEmail' => $payerEmail,
        'description' => $userDetails
    ];
    print_r($data);
    if ($_GET['token'] == $response->result->id) {
        Session::put('isExamFeePaid', ['status' => true, 'subject' => $course]);
        Session::delete('orderId');
        Redirect::to('../user/exam.php');
    }
} catch (HttpException $ex) {
    echo $ex->statusCode;
    print_r($ex->getMessage());
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paypal Return Success</title>
</head>

<body>
    <h1>Success</h1>
</body>

</html>