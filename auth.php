<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
include "config.php";

$txt_username	=	input_data($_POST['username']);
$txt_password	=	input_data($_POST['password']);

if($txt_username=='' || $txt_password == '')
{
  echo "<script>";
  echo "alert('Please fill username and password'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

$sql					= "select company_id, user_id, active_status, user_photo,username, password, role_id, full_name,client_id,user_timezone,user_type from tbl_user where username = '".$txt_username."' LIMIT 1";
$h						= mysqli_query($conn,$sql);

if(mysqli_num_rows($h)==0)
{
  echo "<script>";
  echo "alert('Wrong login information'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

$row 				    = mysqli_fetch_assoc($h);

//is user active?
if($row['active_status']==0)
{
  echo "<script>";
  echo "alert('This username is inactive'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

$sql3 	= "SELECT *, datediff(trial_end_date,CURDATE()) difference FROM tbl_client a INNER JOIN tbl_country b USING (country_id) WHERE a.client_id = '".$row['client_id']."' limit 1";
$h3 	= mysqli_query($conn,$sql3);
$row3 	= mysqli_fetch_assoc($h3);

//is client active?
if($row3['active_status'] == 0)
{
  echo "<script>";
  echo "alert('This buyer is inactive'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

//is paid status = 1? This feature will activate later. Now, let the user use it for free
/*if($row3['difference'] < 1 && $row3['paid_status'] == 0)
{
  echo "<script>";
  echo "alert('Ooops, please make sure your payment subscription is done.'); window.location.href=history.back()";
  echo "</script>";
  exit();
}*/

if(password_verify($txt_password, $row['password']))
{

  $sql		        = mysqli_query($conn,"update tbl_user SET last_login = UTC_TIMESTAMP() where user_id='".$row['user_id']."' limit 1");

  $_SESSION['user_id']			   = $row['user_id'];
  $_SESSION['full_name']			 = $row['full_name'];
  $_SESSION['username']			   = $row['username'];
  $_SESSION['user_photo']			 = $row['user_photo'];
  $_SESSION['user_timezone']	 = $row['user_timezone'];
  $_SESSION['user_type']			 = $row['user_type'];
  $_SESSION['role_id']			   = $row['role_id'];
  $_SESSION['client_id']	       = $row3['client_id'];
  $_SESSION['business_name']	   = $row3['business_name'];
  $_SESSION['business_address']	 = $row3['business_address'];
  $_SESSION['business_phone']		 = $row3['business_phone'];
  $_SESSION['business_email']	   = $row3['business_email'];
  $_SESSION['client_timezone']	 = $row3['client_timezone'];
  $_SESSION['city']				       = $row3['city'];
  $_SESSION['state']	           = $row3['state'];
  $_SESSION['zip_code']	         = $row3['zip_code'];
  $_SESSION['country_id']				 = $row3['country_id'];
  $_SESSION['country_name']			 = $row3['country_name'];
  $_SESSION['region_id']				 = $row3['region_id'];
  $_SESSION['currency']				   = $row3['currency'];
  $_SESSION['trial_status']			 = $row3['trial_status'];
  $_SESSION['trial_remaining']	 = $row3['difference'];
  $_SESSION['paid_status']	     = $row3['paid_status'];

  //timezone
  $serverTimezoneOffset 	= (date("O") / 100 * 60 * 60);
  $clientTimezoneOffset 	= $_POST["timezoneoffset"];
  $serverTime 			= time();
  $serverClientTimeDifference = $clientTimezoneOffset-$serverTimezoneOffset;
  $clientTime 			= $serverTime+$serverClientTimeDifference;
  $_SESSION['selisih'] 	= ($serverClientTimeDifference/(60*60));

  //tampilkan menu berdasarkan level
  $sql_nav_header = "SELECT nav_header_id,nav_header_icon,nav_header_name FROM tbl_nav_user a INNER JOIN tbl_nav_menu b using (nav_menu_id) INNER JOIN tbl_nav_header USING (nav_header_id) WHERE user_id = '".$row['user_id']."' GROUP BY nav_header_id ORDER by nav_header_name";

  $h_nav_header = mysqli_query($conn,$sql_nav_header);
  while($row_menu_header = mysqli_fetch_assoc($h_nav_header)) {
  	$_SESSION['nav_header'][]= array('header_id' => $row_menu_header['nav_header_id'],'header_icon' => $row_menu_header['nav_header_icon'],'header_name' => $row_menu_header['nav_header_name']);

  	$sql_menu 	= "SELECT nav_header_id,nav_menu_name, nav_menu_url FROM tbl_nav_user a INNER JOIN tbl_nav_menu b using (nav_menu_id) WHERE user_id = '".$row['user_id']."' AND nav_header_id = '".$row_menu_header['nav_header_id']."' AND active_status = 1 GROUP BY nav_menu_id ORDER by nav_menu_sort_order";
  	$h_menu 	= mysqli_query($conn,$sql_menu);
  	while($row_menu = mysqli_fetch_assoc($h_menu)) {
  		$_SESSION['nav_items'][]= array('url' => $row_menu['nav_menu_url'], 'name' => $row_menu['nav_menu_name'],'nav_header_id' => $row_menu['nav_header_id']);
    }
  }

  header('location:main/index/');
  

}else{
  echo "<script>";
  echo "alert('Wrong password'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

?>