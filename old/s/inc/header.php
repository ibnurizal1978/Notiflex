<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "check_session.php";

/*if(!in_array($file,$_SESSION['nav_items'][0]) && $file <> 'index') {
  echo '<h1>You are not authorized to access this page</h1>';
  exit();
}*/
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title><?php echo $_SESSION['full_name'] ?></title>

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700|Roboto" rel="stylesheet">
  <link href="../assets/plugins/material/css/materialdesignicons.min.css" rel="stylesheet" />
  <link href="../assets/plugins/simplebar/simplebar.css" rel="stylesheet" />

  <!-- PLUGINS CSS STYLE -->
  <link href="../assets/plugins/nprogress/nprogress.css" rel="stylesheet" />
  <link href="../assets/plugins/prism/prism.css" rel="stylesheet" />
  <!-- MONO CSS -->
  <link id="main-css-href" rel="stylesheet" href="../assets/css/style.css" />
  <!-- FAVICON -->
  <link rel="apple-touch-icon" sizes="57x57" href="../assets/images/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="../assets/images/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="../assets/images/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/images/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="../assets/images/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="../assets/images/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="../assets/images/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="../assets/images/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="../assets/images/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="../assets/images/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="../assets/images/favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="../assets/images/favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">

  <!-- tour -->
  <link rel="stylesheet" href="https://unpkg.com/webtour@1.1.0/dist/webtour.min.css">
  <script src="https://unpkg.com/webtour@1.1.0/dist/webtour.min.js"></script>

  <!--data tables-->
  <link href="../assets/plugins/DataTables/DataTables-1.10.18/css/jquery.dataTables.min.css" rel="stylesheet" />

  <!--
    HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
  -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script src="../assets/plugins/nprogress/nprogress.js"></script>
</head>


  <body class="navbar-fixed sidebar-fixed" id="body">
    <script>
      NProgress.configure({ showSpinner: false });
      NProgress.start();
    </script>



    <!-- ====================================
    ——— WRAPPER
    ===================================== -->
    <div class="wrapper">


        <!-- ====================================
          ——— LEFT SIDEBAR WITH OUT FOOTER
        ===================================== -->
        <aside class="left-sidebar sidebar-light" id="left-sidebar">
          <div id="sidebar" class="sidebar sidebar-with-footer">
            <!-- Aplication Brand -->
            <div class="app-brand">
              <a href="index">
                <img src="../assets/images/logo.png" alt="Mono">
              </a>
            </div>
            <!-- begin sidebar scrollbar -->
            <div class="sidebar-left" data-simplebar style="height: 100%;">
              <!-- sidebar menu -->
              <ul class="nav sidebar-inner" id="sidebar-menu">

            

                  <li class="section-title">Menu</li>

                  <?php foreach($_SESSION['nav_header'] as $key => $value)  { ?>
                  <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#<?php echo $value['header_name'] ?>" aria-expanded="false" aria-controls="email">
                      <i class="mdi <?php echo $value['header_icon'] ?>"></i>
                      <span class="nav-text"><?php echo $value['header_name'] ?></span> <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="<?php echo $value['header_name'] ?>" data-parent="#sidebar-menu">
                      <div class="sub-menu">
                        <?php
                        foreach($_SESSION['nav_items'] as $key2 => $value2) {
                            if($value['header_id']==$value2['nav_header_id']) {
                            $url_menu = $value2['url'];
                        ?>
                        <li>
                          <a class="sidenav-item-link" href="<?php echo $url_menu; ?>">
                            <span class="nav-text"><?php echo $value2['name'] ?></span>
                          </a>
                        </li>
                      <?php }} ?>
                      </div>
                    </ul>
                  </li>
                <?php } ?>

                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <li class="section-title">Settings</li>
                <hr/>

                  <li class="has-sub">
                    <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#01" aria-expanded="false" aria-controls="email">
                      <i class="mdi mdi-wrench"></i>
                      <span class="nav-text">Settings</span> <b class="caret"></b>
                    </a>
                    <ul class="collapse" id="01" data-parent="#sidebar-menu">
                      <div class="sub-menu">
                        <li>
                          <a class="sidenav-item-link" href="users">
                            <span class="nav-text">Users</span>
                          </a>
                        </li>
                        <li>
                          <a class="sidenav-item-link" href="groupReceiver">
                            <span class="nav-text">Group Receiver</span>
                          </a>
                        </li>
                      </div>
                    </ul>
                  </li>

                  <p>&nbsp;</p>
                  <p>&nbsp;</p>
                  <li class="section-title">Reports</li>
                  <hr/>
                  <li class="has-sub">

                        <li>
                          <a class="sidenav-item-link" href="listExpiration?total">
                            <span class="nav-text">Total Expiration Data</span>
                          </a>
                        </li>
                        <li>
                          <a class="sidenav-item-link" href="listExpiration?today">
                            <span class="nav-text">Expired Today</span>
                          </a>
                        </li>
                        <li>
                          <a class="sidenav-item-link" href="listExpiration?month">
                            <span class="nav-text">Expired This Month</span>
                          </a>
                        </li>
                        <li>
                          <a class="sidenav-item-link" href="listExpiration?expired">
                            <span class="nav-text">Already Expired</span>
                          </a>
                        </li>
                  
                  </li>

              </ul>
            </div>

            <!--<div class="sidebar-footer">
              <div class="sidebar-footer-content">
                <ul class="d-flex">
                  <li>
                    <a href="user-account-settings.html" data-toggle="tooltip" title="Profile settings"><i class="mdi mdi-settings"></i></a></li>
                  <li>
                    <a href="#" data-toggle="tooltip" title="No chat messages"><i class="mdi mdi-chat-processing"></i></a>
                  </li>
                </ul>
              </div>
            </div>-->
          </div>
        </aside>



      <!-- ====================================
      ——— PAGE WRAPPER
      ===================================== -->
      <div class="page-wrapper">

          <!-- Header -->
          <header class="main-header" id="header">
            <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
              <!-- Sidebar toggle button -->
              <button id="sidebar-toggler" class="sidebar-toggle">
                <span class="sr-only">Toggle navigation</span>
              </button>
              
              <?php if($_SESSION['trial_remaining'] > 0) { ?>
                <div class="alert alert-success text-center" role="alert">
                  <h5 class="text-white">Enjoy your trial period :). <!--It will be expired in <?php //echo $_SESSION['trial_remaining'] ?> day(s). -->
                </div> 
              <?php } ?>
              <div class="navbar-right ">
                <ul class="nav navbar-nav">
                  <!-- Offcanvas -->
                  <!-- User Account -->
                  <li class="dropdown user-menu">
                    <button class="dropdown-toggle nav-link" data-toggle="dropdown">
                      <span class="d-none d-lg-inline-block"><?php echo $_SESSION['full_name'] ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                      <li class="dropdown-footer">
                        <a class="dropdown-link-item" href="../logout"> <i class="mdi mdi-logout"></i> Log Out </a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>
            </nav>


          </header>
