<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
include "../config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require '../plugins/PHPMailer/src/Exception.php';
require '../plugins/PHPMailer/src/PHPMailer.php';
require '../plugins/PHPMailer/src/SMTP.php';

$token = hash("sha256","REGISTER".date('dmY'));
$tbl   = 'tbl_user';

if(@$_POST['token'] == '') {
    http_response_code(400);
    echo json_encode(['message' => 'empty token']);
    exit();
}
  
if(@$_POST['token'] != $token) {
    http_response_code(400);
    echo json_encode(['message' => 'token mismatch', 'token' => $token]);
    exit();
}
  
if ($_SERVER['REQUEST_METHOD'] <> 'POST') {
    http_response_code(400);
    echo json_encode(['message' => 'The request is using the method']);
    exit();
}

if(@$_POST['full_name'] == '' || @$_POST['email'] == ''  || @$_POST['password'] == ''  || @$_POST['country_id'] == '')
{
    http_response_code(400);
    echo json_encode(['message' => 'Please fill your name, email and password.']);
    exit();
}

$full_name  = input_data($_POST['full_name']);
$username   = input_data($_POST['email']);
$password   = input_data($_POST['password']);
$country_id = input_data($_POST['country_id']);

function valid_pass($password) {
    if (!preg_match_all('$\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\s*$', $password))
        return FALSE;
    return TRUE;
}

if(!valid_pass($password)) {
    http_response_code(400);
    echo json_encode(['message' => 'Minimum of 6 characters and must have 1 uppercase letter, 1 lowercase letter and a number.']);
    exit();
}


if(strlen($password)<6) {
    http_response_code(400);
    echo json_encode(['message' => 'Minimum password is 6 characters.']);
    exit();
}


if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['message' => 'Please type correct email address.']);
    exit();
}

//check apakah ada nama client yang sama
$s = "SELECT business_name FROM tbl_client WHERE business_name = '".$username."' LIMIT 1";
$h = mysqli_query($conn, $s);
if(mysqli_num_rows($h) > 0)
{
    http_response_code(400);
    echo json_encode(['message' => 'This email is unavailable for registration process. Please try another email.']);
    exit();
}

//check apakah ada nama user yang sama
$s = "SELECT username FROM $tbl WHERE username = '".$username."' LIMIT 1";
$h = mysqli_query($conn, $s);
if(mysqli_num_rows($h) > 0)
{
    http_response_code(400);
    echo json_encode(['message' => 'This email is unavailable. Please try another email.']);
    exit();
}

$password = password_hash($password, PASSWORD_DEFAULT);

//sukses
$s = "INSERT INTO tbl_client SET business_name = '".$username."', business_email = '".$username."', country_id = '".$country_id."', trial_end_date = DATE_ADD(now(), INTERVAL 15 day), created_at = UTC_TIMESTAMP()";
mysqli_query($conn, $s);
$last_id = mysqli_insert_id($conn);

$s2 = "INSERT INTO $tbl SET username = '".$username."', password = '".$password."', full_name = '".$full_name."', active_status = 0, client_id = '".$last_id."', created_at = UTC_TIMESTAMP()";
mysqli_query($conn, $s2);
$last_id2 = mysqli_insert_id($conn);

//create verification code
$random = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$code   = substr(str_shuffle($random), 0, 20);
$s3     = "INSERT INTO tbl_user_verification SET email = '".$username."', code = '".$code."', created_at = UTC_TIMESTAMP()";
mysqli_query($conn, $s3);


/* add log */
addLog($conn, $last_id2,'ADD USER FROM WEB', 'Add new user name: '.$username);

//send mail
$body = 'Hello '.$full_name.',<br/><br/>Ready to start your Notiflex? Let\'s start by verifying your email. We do a lot of behind-the-scenes work to make sure you can get more benefits by using our solution.<br/><br/><a href='.$base_url.'apps/verify?'.base64_encode($username)."?".$code.'" style="background:#7434eb; padding:10px; margin:10px; color:#fff; text-decoration:none">Click here to verify your email</a><br/><br/><br/>If the button doesn\'t work, you may copy paste this link to your browser:<br/><br/><a href="'.$base_url.'apps/verify?'.base64_encode($username).'?'.$code.'">'.$base_url.'apps/verify?'.base64_encode($username).'?'.$code.'</a>'.$smtp_footer;
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->SMTPDebug = false;
$mail->do_debug = 0;
$mail->SMTPSecure   = $smtp_secure_method;
$mail->Host         = $smtp_host;
$mail->Port         = 587;
$mail->SMTPAuth     = true;
$mail->Timeout      = 60;
$mail->SMTPKeepAlive = true;
$mail->Username     = $smtp_username;
$mail->Password     = $smtp_password;
$mail->setFrom($smtp_sender_email, $smtp_sender_name);
$mail->addAddress($username, $full_name);
$mail->isHTML(true);
$mail->Subject      = 'Notiflex Registration';
$mail->Body         = $body;
if(!$mail->send()) {
    http_response_code(400);
    echo json_encode(['message' => 'Failed sending email to this account. You might type wrong email address. Please use another email address']);
} else {
    http_response_code(200);
    echo json_encode(['message' => 'OK', 'code' => $code]);
}
exit();
?>