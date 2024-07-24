<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['user_id']);
unset($_SESSION['nav_header']);
unset($_SESSION['nav_items']);
session_destroy();
require_once 'config.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Notiflex</title>
    <!-- CSS files -->
    <link href="./assets/css/tabler.min.css?1692870487" rel="stylesheet"/>
    <link href="./assets/css/tabler-flags.min.css?1692870487" rel="stylesheet"/>
    <link href="./assets/css/tabler-vendors.min.css?1692870487" rel="stylesheet"/>
    <link href="./assets/css/demo.min.css?1692870487" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
  </head>
  <body  class=" d-flex flex-column">
    <script src="./assets/js/demo-theme.min.js?1692870487"></script>
    <div class="page page-center">
      <div class="container container-normal py-4">
        <div class="row align-items-center g-4">
          <div class="col-lg">
            <div class="container-tight">
              <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark"><img src="./static/logo.png" height="36" alt=""></a>
              </div>
              <div class="card card-md">
                <div class="card-body">
                   
                    <h4 class="text-dark mb-6 text-center">One Step Ahead!</h4>
                    <p>We sent a verification link to your email. Please check in your inbox (or maybe in spam folder?) and click the link inside the email.</p>
                    <br/><br/>
                    <p><b>Didn't Receive Email From Us?</b><br/><br/>Huhuhuhu, I didn't receive email from Notiflex, nor in my spam folder :(</p>
                    <br/><br/>
                    <a class="btn btn-primary btn-pill mb-4" href="resend?<?php echo $param[1].'?'.$param[2] ?>">Don't worry, click here to resend the code</a>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./assets/js/tabler.min.js?1692870487" defer></script>
    <script src="./assets/js/demo.min.js?1692870487" defer></script>
    <script>
        function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
        }
    </script>


<!-- modal for forgot password -->
<div class="modal modal-blur fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Forgot Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Your username</label>
            <input type="text" class="form-control" name="example-text-input" placeholder="Type your username">
        </div>
        <div class="row">
            <div class="col-lg-8">
            <div class="mb-3">
                <label class="form-label">Check the box below for verification</label>
                <div class="input-group input-group-flat">
                <span class="input-group-text">
                <div class="g-recaptcha brochure__form__captcha" data-sitekey="6LeGr1QjAAAAACrr4m1cC79BTiKGFmQdt2p11IXf"></div>
                </span>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="modal-footer">
        <?php echo $btn_cancel.' '.$btn_save ?>
        </div>
    </div>
    </div>
</div>
<!-- end modal forgot password -->
  </body>
</html>