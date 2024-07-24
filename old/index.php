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
                      <?php if($param[1] == base64_encode('Active')) { ?>
                        <div class="alert alert-danger" role="alert">
                          <h5 class="text-white">Already Active?</h5><br/>Seems like your username is already activated. You may continue to login.
                        </div> 
                      <?php } ?>
                      <?php if($param[1] == base64_encode('Success')) { ?>
                        <div class="alert alert-success" role="alert">
                          <h5 class="text-white">Enjoy your FREE trial!</h5><br/>Start login here using your email as username and password you've just created.
                        </div> 
                      <?php } ?>
                    <div class="app-brand w-100 d-flex justify-content-center border-bottom-0">
                      <a class="w-auto pl-0" href="index">
                        <img src="assets/images/logo.png" alt="Mono">
                      </a>
                    </div>
                  </div>
                  <div class="card-body px-5 pb-5 pt-0">
                    <h4 class="text-dark mb-6 text-center">Sign in</h4>

                    <form action="auth" method="POST">
                      <script type="text/javascript">
                      tzo = - new Date().getTimezoneOffset()*60;
                      document.write('<input type="hidden" value="'+tzo+'" name="timezoneoffset">');
                      </script>
                      <div class="row">
                        <div class="form-group col-md-12 mb-4">
                          <input type="text" name="txt_username" class="form-control input-lg" placeholder="username">
                        </div>
                        <div class="form-group col-md-12 ">
                          <input type="password" name="txt_password" class="form-control input-lg"  placeholder="Password">
                        </div>
                        <div class="col-md-12">

                          <div class="d-flex justify-content-between mb-3">
                            <a class="text-color" href="#" data-toggle="modal" data-target="#modal-add"> <?php echo $forgot_password ?> </a>
                          </div>

                          <input type="submit" class="btn btn-primary btn-pill mb-4" value="<?php echo $btn_sign_in ?>" />
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>





<!-- Add Contact Button  -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form action="forgotPassword" method="POST">
        <input type="hidden" name="forgot" value="1" />
        <input type="hidden" name="token" value=<?php echo  hash("sha256","REGISTER".date('dmY')); ?> />
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">Help...I forgot my password :(</h5>
        </div>
        <div class="modal-body px-4">

          <div class="row mb-2">

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Your Email<?php echo $important_star ?></label>
                <input type="text" class="form-control" name="email" />
                <span class="mt-2 d-block">Type your email so we can send a link to reset your email.</span>
              </div>
            </div>
 
        </div>
        <div class="modal-footer px-4">
          <?php echo $btn_cancel.$btn_save //this is button ?>
        </div>
      </form>
    </div>
  </div>
</div></div>


<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/650bcb82b1aaa13b7a78084b/1har15u5d';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->


</body>
</html>
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/hotkeys-js/dist/hotkeys.min.js"></script>
<script src="../assets/js/custom.js"></script>