<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../../checkSession.php";
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Notiflex</title>
    <!-- CSS files -->
    <link href="../../assets/css/tabler.min.css?1692870487" rel="stylesheet"/>
    <link href="../../assets/css/tabler-flags.min.css?1692870487" rel="stylesheet"/>
    <link href="../../assets/css/tabler-payments.min.css?1692870487" rel="stylesheet"/>
    <link href="../../assets/css/tabler-vendors.min.css?1692870487" rel="stylesheet"/>
    <link href="../../assets/css/demo.min.css?1692870487" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
    </style>
    <script src="../../assets/js/jquery.min.js"></script>
  </head>
  <body >
    <script src="../../assets/js/demo-theme.min.js?1692870487"></script>
    <div class="page">
      <!-- Navbar -->
      <header class="navbar navbar-expand-md d-print-none" >
        <div class="container-xl">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href=".">
              <img src="../../static/logo.png" width="110" height="32" alt="Logo Ordermatix" class="navbar-brand-image">
            </a>
          </h1>
          <div class="navbar-nav flex-row order-md-last">
            <div class="d-none d-md-flex">
              <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"
		   data-bs-placement="bottom">
                <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
              </a>
              <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip"
		   data-bs-placement="bottom">
                <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
              </a>
            </div>
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"></span>
                <div class="d-none d-xl-block ps-2">
                  <div><?php echo $_SESSION['full_name'] ?></div>
                  <div class="mt-1 small text-secondary"><?php echo $_SESSION['username'] ?></div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <a href="#" data-bs-toggle="modal" data-bs-target="#password" class="dropdown-item">Change Password</a>
                <a href="../../logout" class="dropdown-item">Logout</a>
              </div>
            </div>
          </div>
          <div class="collapse navbar-collapse" id="navbar-menu">
            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link" href="./" >
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <img src="../../static/icons/home.svg" />
                    </span>
                    <span class="nav-link-title">
                      Home
                    </span>
                  </a>
                </li>
                
                <?php foreach($_SESSION['nav_header'] as $key => $value)  { ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#<?php echo $value['header_name'] ?>" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" >
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <img src="../../static/icons/<?php echo $value['header_icon'] ?>.svg" />
                        </span>
                        <span class="nav-link-title"><?php echo $value['header_name'] ?></span> 
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                            <?php
                            foreach($_SESSION['nav_items'] as $key2 => $value2) {
                                if($value['header_id']==$value2['nav_header_id']) {
                                $url_menu = $value2['url'];
                            ?>
                                <a class="dropdown-item" href="<?php echo $url_menu; ?>"><?php echo $value2['name'] ?></a>
                                <?php }} ?>
                            </div>  
                        </div>
                    </div>
                </li>
                <?php } ?>
                
              </ul>
            </div>
          </div>
        </div>
      </header>


      <!-- modal dialog for change password -->
      <form id="form_simpan_password" class="card">
      <div class="modal modal-blur fade" id="password" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Change Password</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-lg-12">
                              <div class="mb-3">
                                  <label class="form-label required">Current Password</label>
                                  <div class="input-group input-group-flat">
                                      <input type="text" name="current" class="form-control ps-0" autocomplete="off">
                                  </div>
                              </div>
                          </div>
                          <div class="col-lg-12">
                              <div class="mb-3">
                                  <label class="form-label required">New Password</label>
                                  <div class="input-group input-group-flat">
                                      <input type="text" name="password" class="form-control ps-0" autocomplete="off">
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                  <div id="results_password"></div><div id="button_password"></div>
                  </div>
              </div>
          </div>
      </div>
      </form>

      <script>
      $(document).ready(function(){
        $("#button_password").html('<?php echo $btn_save2 ?>');  
        $('#submit_data').click(function(){  
          $('#form_simpan_password').submit(); 
          $("#results_password").html('');
        });  
        $('#form_simpan_password').on('submit', function(event){
          $("#results_password").html(''); 
          $("#button_password").html('<?php echo $btn_save2_loading ?>');  
          event.preventDefault();  
          $.ajax({  
            url:"passwordAction",  
            method:"POST",  
            data:new FormData(this),  
            contentType:false,  
            processData:false,  
            success:function(data){ 
              $('#results_password').html(data);
              $("#button_password").html('<?php echo $btn_save2 ?>');  
            }  
          });  
        });  
      });  
      </script>