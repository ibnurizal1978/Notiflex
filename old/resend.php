<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
include "config.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'plugins/PHPMailer/src/Exception.php';
require 'plugins/PHPMailer/src/PHPMailer.php';
require 'plugins/PHPMailer/src/SMTP.php';

$email	=	input_data(base64_decode($param[1]));
$code   =	input_data($param[2]);

if($email =='' || $code == '')
{
  echo "<script>";
  echo "alert('Oops, your email and code is missing'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

$sql = "select email, code from tbl_user_verification where email = '".$email."' AND code = '".$code."' LIMIT 1";
$h	 = mysqli_query($conn,$sql);

if(mysqli_num_rows($h)==0)
{
  echo "<script>";
  echo "alert('We are unable to resend a verification code to your email. Perhaps you account already active?'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

$s2 = "select full_name FROM tbl_user WHERE username = '".$email."' LIMIT 1";
$h2 = mysqli_query($conn, $s2);
$r2 = mysqli_fetch_assoc($h2);
//send mail
$body = 'Hello '.$r2['full_name'].',<br/><br/>Ready to start your Notiflex? Let\'s start by verifying your email. We do a lot of behind-the-scenes work to make sure you can get more benefits by using our solution.<br/><br/><a href='.$base_url.'apps/verify?'.base64_encode($username)."?".$code.'" style="background:#7434eb; padding:10px; margin:10px; color:#fff; text-decoration:none">Click here to verify your email</a><br/><br/><br/>If the button doesn\'t work, you may copy paste this link to your browser:<br/><br/><a href="'.$base_url.'apps/verify?'.base64_encode($username).'?'.$code.'">'.$base_url.'apps/verify?'.base64_encode($username).'?'.$code.'</a>'.$smtp_footer;
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
$mail->addAddress($email, $r2['full_name']);
$mail->isHTML(true);
$mail->Subject      = 'Notiflex Registration';
$mail->Body         = $body;
if(!$mail->send()) {
    echo 'Failed sending email to this account. You might type wrong email address. Please use another email address';
} else {
    header("Location: ".$base_url."apps/success?".base64_encode($email)."?".$code);
}

?>