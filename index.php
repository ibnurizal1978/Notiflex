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
                    <!--show an error message related to login process-->
                    <?php if(@$_GET['r']==1) {?>
                        <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
                            </div>
                            <div>
                            Arrghhh, seems your username or password is wrong
                            </div>
                        </div>
                        </div>
                    <?php } ?>

                    <?php if(@$param[1]==2) {?>
                        <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                        <div class="d-flex">
                            <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
                            </div>
                            <div>
                            For security reason, we did an automatic logout due to idle activities. Please re-login.
                            </div>
                        </div>
                        </div>
                    <?php } ?>
                    <!--end show an error message related to login process-->
                  <h2 class="h2 text-center mb-4">Login to your account</h2>
                  <form action="auth" method="POST" autocomplete="off" novalidate id="time_form" name="time_form">
			        <script type="text/javascript">
			            tzo = - new Date().getTimezoneOffset()*60;
			            document.write('<input type="hidden" value="'+tzo+'" name="timezoneoffset">');
			        </script>	
                    <div class="mb-3">
                      <label class="form-label">Username</label>
                      <input type="text" name="username" class="form-control" placeholder="username" autocomplete="off">
                    </div>
                    <div class="mb-2">
                      <label class="form-label">
                        Password
                        <span class="form-label-description">
                        </span>
                      </label>
                      <div class="input-group input-group-flat">
                        <input type="password" name="password" class="form-control"  placeholder="password"  autocomplete="off" id="password">
                        <span class="input-group-text">
                          <a href="#" onclick="myFunction()" class="link-secondary" title="Show password" data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                          </a>
                        </span>
                      </div>
                    </div>
                    <div class="form-footer">
                      <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="text-center text-secondary mt-3">
                Forgot password? <a href="#" data-bs-toggle="modal" data-bs-target="#modal" tabindex="-1">Click here</a>
              </div>
            </div>
          </div>
          <div class="col-lg d-none d-lg-block">
            <img src="./static/illustrations/undraw_secure_login_pdn4.svg" height="300" class="d-block mx-auto" alt="">
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