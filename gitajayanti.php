<?php
header("Access-Control-Allow-Origin: *");
error_reporting(0);
session_start();
include('connect.php');
require("razorpay_inc_gitaj.php");

date_default_timezone_set('Asia/Kolkata');

if(isset($_GET['donationdata'])){
  $donationdata = $_GET['donationdata'];
  $donationvalues = explode("|", $donationdata);
  
  $valuescount = sizeof($donationvalues);
  $refno = $donationvalues[$valuescount-1];
  $amount = round($donationvalues[0]*100); 
  
  $order  = $api->order->create(array('receipt' => $refno, 'amount' => $amount, 'currency' => 'INR')); // Creates order
  $order_id = $order->id;
  
  $donationdate = date("Y-m-d");
  
  
  $sql="SELECT * from gitajayanti where refno = '".$refno."'";
  $result = mysqli_query($con, $sql);
        if($result->num_rows==0) {
            $sql = "INSERT INTO `gitajayanti` (`totalamount`,`date`, `name`, `whatsappno`, `email`, `address`, `goaharcow`, `goaharmonth`, `goaharamount`,`gopujaseva`,`gopujadate`, `gochikitsa`, `gograss`, `extendedamount`, `refno`, `orderid`, `paymentid`, `paymentsignature`) VALUES ('".$donationvalues[0]."','".$donationdate."', '".$donationvalues[1]."','".$donationvalues[2]."', '".$donationvalues[3]."', '".$donationvalues[4]."', '".$donationvalues[5]."', '".$donationvalues[6]."', '".$donationvalues[7]."', '".$donationvalues[8]."', '".$donationvalues[9]."', '".$donationvalues[10]."','".$donationvalues[11]."','".$donationvalues[12]."','".$donationvalues[13]."', '".$order_id."', '', '')";

            if ($con->query($sql) === TRUE) {
                      // echo "New record created successfully";
                  } 
            else {
                      echo "Error: " . $sql . "<br>" . $con->error;
                 } 
        } 
        else {
          
          $sql = "UPDATE `gitajayanti` SET `name`='".$donationvalues[1]."',`whatsappno`='".$donationvalues[2]."',`email`='".$donationvalues[3]."',`address`='".$donationvalues[4]."',`goaharcow`='".$donationvalues[5]."',`goaharmonth`='".$donationvalues[6]."',`goaharamount`='".$donationvalues[7]."',`gopujaseva`='".$donationvalues[8]."',`gopujadate`='".$donationvalues[9]."',`gochikitsa`='".$donationvalues[10]."',`gograss`='".$donationvalues[11]."',`extendedamount`='".$donationvalues[12]."',`orderid`='".$order_id."' WHERE refno = '".$refno."'";
          
           if ($con->query($sql) === TRUE) {
        // echo "New record updated successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $con->error;
                } 
        }  
  echo $order_id;
 
}

if(isset($_POST['razorpay_payment_id']) && isset($_POST['razorpay_order_id']) && isset($_POST['razorpay_signature']) ){
    $razorpay_payment_id = $_POST['razorpay_payment_id'];
    $razorpay_order_id = $_POST['razorpay_order_id'];
    $razorpay_signature = $_POST['razorpay_signature'];
    $refno = false;
    $n1 = false;
    

    $sql2 = "select * from gitajayanti where `orderid`='".$razorpay_order_id."' ";
    $res2 = $con->query($sql2);
    if($res2->num_rows == 1) {
        $arr2 = $res2->fetch_assoc(); // fetching all fields of the row
        try {
            // $payment = $api->payment->fetch($razorpay_payment_id)->capture(array('amount' => $arr2['totalamount']*100));

            $sql1 = "UPDATE `gitajayanti` SET `paymentsignature`='" . $razorpay_signature . "', `paymentid`='" . $razorpay_payment_id . "' WHERE `orderid` ='" . $razorpay_order_id . "'";
            $res1 = $con->query($sql1);
            $n1 = $con->affected_rows;
            console.log("radhe");
/*
            $payments = $api->order->fetch($razorpay_order_id)->payments();
            // $payments = $api->payment->fetch($arr2['refno']);
            echo '<pre>';
            json_encode($payments);
            die;
*/
            $refno = $arr2['refno'];

        } catch (Exception $e) {
            print( 'Error: ' . $e->getMessage() );
        }
    }
  
   if($refno && $n1) {
    confirmregistration($arr2); 
   } else {
      header('Location: https://www.iskconnewtown.com/paymentfailed');
   }
}

function confirmregistration($arr2){

    console.log("one confirmregistration");
    $to = $arr2['email'];
    $subject = "Goseva - Payment Confirmation";
  
    $kalash = '';
    $goseva = '';
    $annadan = '';
    $refno = '';
    
  //   if($arr2['kalashamount']!=0) {
  //     $kalash =  $arr2['kalashamount']==2001?'Kalash Abhishek with Bronze Kalash - Rs. 2001':($arr2['kalashamount']==5001?'Kalash Abhishek with Silver Kalash - Rs. 5001':'Kalash Abhishek with Gold Kalash - Rs. 10001');
  //     $kalash = $kalash.'  <br>  ';
  //   }
    
  // if($arr2['gosevaamount']!=0) {
  //     $goseva =  $arr2['gosevaamount']==5001?'Go Puja and Go Seva on Janmashtami - Rs. 5001':($arr2['gosevaamount']==2501?'Go Puja on Janmashtami - Rs. 2501' : (($arr2['gosevaamount']==1101?'Go Seva for 11 cows - Rs. 1101': 'Go Seva for 21 cows - Rs. 2101')));
  //     $goseva = $goseva.'  <br>  ';
                                                                                  
  //   }
  
  // if($arr2['annadanamount']!=0) {
  //   $annadan = 'Donated '.$arr2['platecount'].' plates of Annadan for Janmashtami - Rs. '.$arr2['annadanamount'].'  <br>  ';
    
  // }
  
  // $extenddonation = $arr2['extenddonationamount']!=0?'Donated Rs. '.$arr2['extenddonationamount'].' for extending to help others <br> ' :'';

  $name = 'Name: '.$arr2['name'];
  $whatsappno = 'Whatsapp No: '.$arr2['whatsappno'];
  $email = 'Email: '.$arr2['email'];
  $address = 'Address: '.$arr2['address'];
  $goaharcow = 'Go-Ahar Number of cows: '.$arr2['goaharcow'];
  $goaharmonth = 'Go-ahar Number of month: '.$arr2['goaharmonth'];
  $goaharamount = 'Go-ahar Amount: '.$arr2['goaharamount'];
  $gopujaseva = 'Go Puja: '.$arr2['gopujaseva'];
  $gopujadate = 'Go Puja Date: '.$arr2['gopujadate'];
  $gochikitsa = 'Go chikitsa: '.$arr2['gochikitsa'];
  $gograss = 'Go-Grass Banana Feeding Seva: '.$arr2['gograss'];
  $extendedamount = 'Extended Amount: '.$arr2['extendedamount'];
  $refno = 'Your Payment Reference Number: '.$arr2['refno'];
  
    // $registrationdetails = $name . $whatsappno . $email . $address . $goaharcow . $gopujaseva . $whatsappno . $email . $refno;
    $registrationdetails = nl2br("\n".$name."\n".$whatsappno."\n".$email."\n".$address."\n".$goaharcow."\n".$goaharmonth."\n".$goaharamount."\n".$gopujaseva."\n".$gopujadate."\n".$gochikitsa."\n".$gograss."\n".$extendedamount."\n".$refno);
    $message = "
<html>
<head>
</head>
<h:body><div style='margin-left: auto;margin-right: auto;overflow:auto'>
<h3 style = 'text-align:left; font-family: Raleway, Arial, Helvetica, sans-serif;font-weight: 400;line-height: 1.4;letter-spacing: 0px;font-style: normal;'>Hare Krishna!,<br><br> Confirming your payment for Goseva <br><br>Following are your payment details: <br>".$registrationdetails." <br><br><br>

Please Subscribe/Like ISKCON NewTown’s Official Facebook & Youtube channels to stay tuned with Enlivening Events/Classes coming up soon for this Janmastami. <br>

https://www.facebook.com/iskconnewtown.kolkata <br>

https://www.youtube.com/channel/UC4Soo7pqYpKkPLwqqRW8RrQ <br>

</h3>



</body>
</html>
";
// Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
// More headers
    $headers .= 'From: <admin@iskconnewtown.com>' . "\r\n";
    mail($to,$subject,$message,$headers);
   
    header('Location: https://www.iskconnewtown.com/thankyou');
} 

if(isset($_GET['emailid'])){
  
   $to = $_GET['emailid'];
    $subject = "Testing";
  
    $registrationdetails = "Hare Krishna";
    $message = "
<html>
<head><style type='text/css'>
table {font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;width: 100%;border-collapse: collapse;} td, 
th {font-size: 1em;border: 1px solid black;padding: 3px 7px 2px 7px;} th {font-size: 1.1em;text-align: left;padding-top: 5px;padding-bottom: 4px;
background-color: #A7C942;color: #ffffff;} tr.alt td {color: #000000;background-color: #EAF2D3;}
</style>
</head>
<h:body><div style='margin-left: auto;margin-right: auto;overflow:auto'>
<h3 style = 'text-align:left;    font-family: Raleway, Arial, Helvetica, sans-serif;font-weight: 400;line-height: 1.4;letter-spacing: 0px;font-style: normal;'>Hare Krishna!, Confirming your registration for personality development course <br><br>Following are your registration details: <br>".$registrationdetails."

Please Subscribe/Like ISKCON NewTown’s Official Facebook & Youtube channels to stay tuned with Enlivening Events/Classes /Online courses  <br>

https://www.facebook.com/iskconnewtown.kolkata <br>

https://www.youtube.com/channel/UC4Soo7pqYpKkPLwqqRW8RrQ <br>

</h3>



</body>
</html>
";
// Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
// More headers
    $headers .= 'From: <admin@iskconnewtown.com>' . "\r\n";
    mail($to,$subject,$message,$headers);
  
  

}



?>