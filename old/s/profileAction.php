<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "inc/check_session.php";
//$action  = $param[1];
$tbl     = 'tbl_client';

$business_name	    =	input_data(filter_var($_POST['business_name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$currency	          =	input_data(filter_var($_POST['currency'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$business_address	  =	input_data(filter_var($_POST['business_address'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$business_phone	    =	input_data(filter_var($_POST['business_phone'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$business_email	    =	input_data(filter_var($_POST['business_email'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$city	              =	input_data(filter_var($_POST['city'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$state	            =	input_data(filter_var($_POST['state'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$zip_code	          =	input_data(filter_var($_POST['zip_code'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
$country_id	        =	input_data(filter_var($_POST['country_id'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));

if($business_name == '' && $business_address == '' && $business_email == '')
{
  echo "<script>";
  echo "alert('$data_empty'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

//check apakah ada nama group yang sama
$s = "SELECT business_email FROM $tbl WHERE business_email = '".$business_email."' AND client_id <> '".$_SESSION['client_id']."' AND business_name = '".$business_name."' LIMIT 1";
$h = mysqli_query($conn, $s);
if(mysqli_num_rows($h) > 0)
{
  echo "<script>";
  echo "alert('$data_duplicate'); window.location.href=history.back()";
  echo "</script>";
  exit();
}

//sukses
$s = "UPDATE $tbl SET business_name = '".$business_name."', currency = '".$currency."', business_address = '".$business_address."', business_phone = '".$business_phone."', business_email = '".$business_email."', city = '".$city."', state = '".$state."', zip_code = '".$zip_code."', country_id = '".$country_id."', updated_at = UTC_TIMESTAMP() WHERE client_id = '".$_SESSION['client_id']."'";
mysqli_query($conn, $s);

/* add log */
addLog($conn, $_SESSION['user_id'],'EDIT PROFILE', 'Edit profile client ID: '.$_SESSION['client_id']);

echo "<script>";
echo "alert('Your profile has successfully updated. You need to re-login to reload your newest profile'); window.location=\"../logout\"";
echo "</script>";
?>
