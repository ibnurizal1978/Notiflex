<?php
session_start();
if($_SESSION['lang'] == 'id') {
  include 'lang/id.php';
}else{
  include 'lang/en.php';
}
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title><?php echo $welcome_title ?></title>

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
                    <h4 class="text-dark mb-6 text-center">One Step Ahead!</h4>
                    <p>We sent a verification link to your email. Please check in your inbox (or maybe in spam folder?) and click the link inside the email.</p>
                    <br/><hr/><br/>
                    <p><b>Didn't Receive Email From Us?</b><br/><br/>Whoaa, I didn't receive email from Notiflex, nor in my spam folder :(</p>
                    <br/><br/>
                    <a class="btn btn-primary btn-pill mb-4" href="resend?<?php echo $param[1].'?'.$param[2] ?>">Resend the code, please</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

</body>
</html>
