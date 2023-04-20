<?php
//include required phpmailer files

require('includes/PHPMailer.php');
require('includes/SMTP.php');
require('includes/Exception.php');
//define name spaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//create a instance phpmailer
$mail = new PHPMailer();
//set mailer to use smtp
$mail->isSMTP();
//define smtp host
$mail->Host = "smtp.gmail.com";
//enable smtp authentication
$mail->SMTPAuth = "true";
//set type of encryption (ssl/tls)
$mail->SMTPSecure = "tls";
//set port to connect smtp
$mail->Port = "587";
//set gmail user
$mail->Username = "csravi816@gmail.com";
//set gmail password
$mail->Password = "Ravi1234";
//set gmail subject
$mail->Subject = "Test Email Using PHPMailer";
//set sender email
$mail->setFrom("csravi816@gmail.com");
//email body
$mail->Body = "Hello Sajjad Wish You the Best of luck Ahead";
//add recipient
$mail->addAddress("bharathamirani093@gmail.com");
//finaly send email
$mail->send();
//closing smtp connection
if($mail->Send())
{
  echo "Email Sent";
}
else
{
    echo "Error";
}
$mail->smtpClose();
?>