<?php
// include 'connect.php';
include('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
$keyId= 'rzp_test_BFRmbwRrztYcvF';
$keySecret = '2WF0BSO4EX052kjUZrlsBR5L';
$api = new Api($keyId,$keySecret);

// $registration_amount = 10000;
?>