<?php
// include 'connect.php';
include('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
$keyId= 'rzp_live_BkG5TkB4pU5rZa';
$keySecret = 'ILJe6DdlYIoJl1H8bwKgJbt5';
$api = new Api($keyId,$keySecret);

// $registration_amount = 10000;
?>