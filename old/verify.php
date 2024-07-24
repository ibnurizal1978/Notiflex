<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
include "config.php";

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
  echo "We are unable to resend a verification code to your email. Perhaps you account already active?";
  exit();
}

$s2 = "SELECT user_id, client_id, active_status FROM tbl_user WHERE username = '".$email."' LIMIT 1";
$h2 = mysqli_query($conn, $s2);
$r2 = mysqli_fetch_assoc($h2);

if($r2['active_status'] == 1)
{
  header("Location: ".$base_url."apps/index?".base64_encode('Active'));
  exit();
}

//update active_status = 1 so user can login
$s3 = "UPDATE tbl_user SET active_status = 1 WHERE username = '".$email."' LIMIT 1";
$h3 = mysqli_query($conn, $s3);

//give access to the menu so user can view menu on the left side
$sql_menu = "SELECT nav_menu_id from tbl_nav_menu WHERE active_status = 1";
$h_menu   = mysqli_query($conn,$sql_menu);
while($row_menu = mysqli_fetch_assoc($h_menu))
{
  $sql_menu2  = "INSERT INTO tbl_nav_user(nav_menu_id,user_id,client_id)
                VALUES
                ('".$row_menu['nav_menu_id']."','".$r2['user_id']."','".$r2['client_id']."')";
  mysqli_query($conn,$sql_menu2);
}


//redirect to index page
header("Location: ".$base_url."apps/index?".base64_encode('Success'));

?>