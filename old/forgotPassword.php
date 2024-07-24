<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//echo $_POST['forgot'];
if(@$_POST['forgot'] == 1)
{
    //echo 'tai';

    require 'plugins/PHPMailer/src/Exception.php';
    require 'plugins/PHPMailer/src/PHPMailer.php';
    require 'plugins/PHPMailer/src/SMTP.php';

    $token = hash("sha256","REGISTER".date('dmY'));
    $tbl   = 'tbl_user';

    if(@$_POST['token'] == '') {
        echo "<script>";
        echo "alert('Oops, your token is empty. Please try again'); window.location.href=history.back()";
        echo "</script>";
        exit();
    }
    
    if(@$_POST['token'] != $token) {
        echo "<script>";
        echo "alert('Oops, your token is invalid. Perhaps you are trying to access this page directly?'); window.location.href=history.back()";
        echo "</script>";
        exit();
    }
    

    if(@$_POST['email'] == '')
    {
        echo "<script>";
        echo "alert('You did not type your email. How come we send you an email then?'); window.location.href=history.back()";
        echo "</script>";
        exit();
    }

    $email  = input_data($_POST['email']);


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>";
        echo "alert('Seems that you mistype and email address, please re-check.'); window.location.href=history.back()";
        echo "</script>";
        exit();
    }
    
    //create new password
    $random     = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password   = substr(str_shuffle($random), 0, 6);
    $password2   = password_hash($password, PASSWORD_DEFAULT);
    $s3     = "UPDATE $tbl SET password = '".$password2."' WHERE username = '".$email."' LIMIT 1";
    mysqli_query($conn, $s3);

    $s = "SELECT user_id, full_name FROM $tbl WHERE username = '".$email."' LIMIT 1";
    $h = mysqli_query($conn, $s);
    $r = mysqli_fetch_assoc($h);

    //add log
    addLog($conn, $r['user_id'],'FORGOT PASSWORD', 'username: '.$email);

    //send mail
    $body = 'Hello '.$r['full_name'].',<br/><br/>Seems like you just requested a forgot password for Notiflex.<br/><br/>Here is your new password: '.$password.'<br/><br/><br/>Cheers,<br/>Your Notiflex Assistant';
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
    $mail->addAddress($email, $r['full_name']);
    $mail->isHTML(true);
    $mail->Subject      = 'Notiflex - Reset Your Password';
    $mail->Body         = $body;
    if(!$mail->send()) {
        $title      = "Oops, sorry :(";
        $content    = "We cannot send the email. If you have typed it correctly, maybe our mail server is too busy. You can retry anyway.";
    } else {
        $title      = "Reset Your Password";
        $content    = " Hi there,<br/>Seems like you just requested a forgot password for Notiflex. Your new password has been sent to your email. Please check in your inbox (or maybe in your spam or junk folder).";
    }
}else{
  $title = '';
  $content = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Notfilex - Reset Your Password</title>

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700|Roboto" rel="stylesheet">
  <link href="assets/plugins/material/css/materialdesignicons.min.css" rel="stylesheet" />
  <link href="assets/plugins/simplebar/simplebar.css" rel="stylesheet" />

  <!-- PLUGINS CSS STYLE -->
  <link href="assets/plugins/nprogress/nprogress.css" rel="stylesheet" />

  <!-- MONO CSS -->
  <link id="main-css-href" rel="stylesheet" href="assets/css/style.css" />




  <!-- FAVICON -->
  <link href="assets/images/favicon.png" rel="shortcut icon" />

  <!--
    HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
  -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script src="assets/plugins/nprogress/nprogress.js"></script>
</head>

</head>
  <body class="bg-light-gray" id="body">
          <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh">
          <div class="d-flex flex-column justify-content-between">
            <div class="row justify-content-center">
              <div class="col-lg-6 col-md-10">
                <div class="card card-default mb-0">
                  <div class="card-header pb-0">
                    <div class="app-brand w-100 d-flex justify-content-center border-bottom-0">
                      <a class="w-auto pl-0" href="index">
                        <img src="assets/images/logo.png" alt="Mono">
                      </a>
                    </div>
                  </div>
                  <div class="card-body px-5 pb-5 pt-0">
                    <h4 class="text-dark mb-6 text-center"><?php echo $title ?></h4>
                    <p>
                      <?php echo $content ?>
                      <br/><br/>
                      Cheers,
                      Notiflex team.
                      <br/><br/>
                      <a class="btn btn-primary btn-pill mb-4" href="/apps/">Allright, back to login</a>
                    </p>
                    <br/>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

</body>
</html>
