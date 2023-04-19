<?php
// PHP code to reset the password if the token matches
require_once("./db_connection.php");

// Get the input data from the API request
$access_token = $_POST['access_token'];
$password = $_POST['password'];

// Validate input data
if (!$access_token || !$password) {
  // Access token and password are required
  $response = array(
    'status' => 'error',
    'message' => 'Access token and password are required.',
    'detail' => '',
  );
  $json = json_encode($response);
  header('Content-Type: application/json');
  http_response_code(400);
  echo $json;
  die;
}

// Check if the access_token exists in the database
$user_query = "SELECT * FROM users WHERE access_token = '$access_token'";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

if ($user_data) {
  // Update the user's password in the database
  $update_query = "UPDATE users SET password = '$hashed_password', access_token = NULL WHERE access_token = '$access_token'";
  $update_result = mysqli_query($conn, $update_query);

  if ($update_result) {
    // Create a JSON response
    $response = array(
      'status' => 'success',
      'message' => 'Your password has been reset.',
      'detail' => '',
    );
    $json = json_encode($response);
    header('Content-Type: application/json');
    http_response_code(200);
    echo $json;
    die;
  } else {
    // Error updating the password in the database
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
  // access_token doesn't exist or has expired
  $response = array(
    'status' => 'error',
    'message' => 'The access token you entered is invalid or has expired.',
    'detail' => ''
  );
  $json = json_encode($response);
  header('Content-Type: application/json');
  http_response_code(400);
  echo $json;
  die;
}
