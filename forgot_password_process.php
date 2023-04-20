<?php
// PHP code to generate a unique token and store it in the database
require_once("./db_connection.php");
require('./includes/PHPMailer.php');
require('./includes/SMTP.php');
require('./includes/Exception.php');
//define name spaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Get the input data from the API request
$username = $_POST['username'];

// Check if the user exists in the database
$user_query = "SELECT * FROM users WHERE username = '$username'";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);

if ($user_data) {
  // Generate a unique token
  $token = rand(999999, 9999999);

  // Store the token in the database
  $update_query = "UPDATE users SET access_token = '$token' WHERE username = '$username'";
  $update_result = mysqli_query($conn, $update_query);

  if ($update_result) {

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
    $mail->Subject = $subject;
    //set sender email

    $mail->Body = $message;
    //add recipient
    $mail->addAddress("csravi816@gmail.com");
    // $mail->addAttachment($name);

    //closing smtp connection
    $mail->Send();
    if($mail->Send())
    {
      $message= "Email Sent";
    }
    else
    {
    	$message = "Email not exist";
    }
    $mail->smtpClose();
    // Create a JSON response
    $response = array(
      'status' => 'success',
      'message' => 'Check your email to reset your password.',
      'detail' => '',
    );
    $json = json_encode($response);
    header('Content-Type: application/json');
    http_response_code(200);
    echo $json;
    die;
  } else {
    // Error updating the token in the database
    $response = array(
      'status' => 'error',
      'message' => 'An error occurred. Please try again later.',
      'detail' => '',
    );
    $json = json_encode($response);
    header('Content-Type: application/json');
    http_response_code(500);
    echo $json;
    die;
  }
} else {
  // User doesn't exist
  $response = array(
    'status' => 'error',
    'message' => 'The email address you entered is not registered.',
    'detail' => ''
  );
  $json = json_encode($response);
  header('Content-Type: application/json');
  http_response_code(400);
  echo $json;
  die;
}