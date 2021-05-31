<?php
require "../../vendor/autoload.php";
include_once("../../config/init.php");

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'email' => array('required' => true),
            'firstName' => array('required' => true), 'lastName' => array('required' => true),
            'subject' => array('required' => true)
        ));
        if ($validation->passed()) {

            $subject = Input::get('subject');
            $firstName = Input::get('firstName');
            $lastName = Input::get('lastName');
            $email = Input::get('email');

            $selectSubject = new Subject();
            $selectSubject->find((int)$subject);

            $clientId = Config::get("paypal/clientId");
            $clientSecret = Config::get("paypal/clientSecret");

            $environment = new SandboxEnvironment($clientId, $clientSecret);
            $client = new PayPalHttpClient($environment);

            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                "intent" => "CAPTURE",
                "purchase_units" => [
                    [
                        "reference_id" => $subject,
                        "amount" => [
                            "value" => $selectSubject->data()->exam_fee,
                            "currency_code" => "AUD"
                        ],
                        "custom_id" => $email,
                        "description" => 'First name: ' . $firstName . ' Last name: ' . $lastName . ' Email: ' . $email
                    ],
                ],
                "application_context" => [
                    "cancel_url" => Config::get('urls/root_url') . "includes/paypal/cancel.php",
                    "return_url" => Config::get('urls/root_url') . "includes/paypal/return.php"
                ]
            ];

            try {
                $response = $client->execute($request);
                $orderId = $response->result->id;
                Session::put('orderId', $orderId);
                foreach ($response->result->links as $link) {
                    if ($link->rel == "approve") {
                        header('location:' . $link->href);
                    }
                }
            } catch (HttpException $ex) {
                echo $ex->statusCode;
                print_r($ex->getMessage());
            }
            die;
        } else {
            Session::put('errors', $validation->errors());
            Session::putArray('userForm', 'firstName', $firstName);
            Session::putArray('userForm', 'lastName', $lastName);
            Session::putArray('userForm', 'email', $email);
            Session::putArray('userForm', 'subject', $subject);
            Redirect::to('../../');
        }
    }
}
Redirect::to("../../");
