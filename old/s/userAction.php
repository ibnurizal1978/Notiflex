<?php
session_start();
require_once "../config.php";
require_once "inc/check_session.php";
$action  = $param[1];
$tbl     = 'tbl_user';

if($action == "add") {
  $username	        =	input_data(filter_var($_POST['username'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  $password	        =	input_data(filter_var($_POST['password'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  $full_name	      =	input_data(filter_var($_POST['full_name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  $role_id	        =	input_data(filter_var($_POST['role_id'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  $client_id	      =	input_data(filter_var($_POST['client_id'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));

  function valid_pass($password) {
      if (!preg_match_all('$\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\s*$', $password))
          return FALSE;
      return TRUE;
  }

   if(!valid_pass($password)) {
    echo "<script>";
    echo "alert('Minimum of 6 characters and must have 1 uppercase letter, 1 lowercase letter and a number.'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }


  if(strlen($password)<6) {
    echo "<script>";
    echo "alert('Minimum password is 6 characters.'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  if($username == '' || $full_name == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama group yang sama
  $s = "SELECT username FROM $tbl WHERE client_id = '".$client_id."' AND username = '".$username."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  $password = password_hash($password, PASSWORD_DEFAULT);

  //sukses
  $s = "INSERT INTO $tbl SET username = '".$username."', password = '".$password."', role_id = '".$role_id."', full_name = '".$full_name."', client_id = '".$client_id."', owner_id = '".$_SESSION['client_id']."', user_type = 'BUYER', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s);
  /* add log */
  addLog($conn, $_SESSION['user_id'],'ADD USER', 'Add new user name: '.$username);

  echo "<script>";
  echo "alert('$data_success'); window.location=\"user?".Encryption::encode($client_id)."\"";
  echo "</script>";

}


if($action == "edit") {
  $user_id          = Encryption::decode($_POST['user_id']);
  $client_id        = Encryption::decode($_POST['client_id']);
  $username	        =	input_data(filter_var($_POST['username'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  $password	        =	input_data(filter_var($_POST['password'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  $full_name	      =	input_data(filter_var($_POST['full_name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  $role_id	        =	input_data(filter_var($_POST['role_id'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));
  $active_status	  =	input_data(filter_var($_POST['active_status'],FILTER_SANITIZE_FULL_SPECIAL_CHARS));

  if($username == '' && $password == '' && $full_name == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama group yang sama
  $s = "SELECT username FROM $tbl WHERE client_id = '".$client_id."' AND username = '".$username."' AND user_id <> '".$user_id."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  if($password <> '')
  {
    function valid_pass($password) {
        if (!preg_match_all('$\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\s*$', $password))
            return FALSE;
        return TRUE;
    }

     if(!valid_pass($password)) {
      echo "<script>";
      echo "alert('Minimum of 6 characters and must have 1 uppercase letter, 1 lowercase letter and a number.'); window.location.href=history.back()";
      echo "</script>";
      exit();
    }


    if(strlen($password)<6) {
      echo "<script>";
      echo "alert('Minimum password is 6 characters.'); window.location.href=history.back()";
      echo "</script>";
      exit();
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    $s = "UPDATE $tbl SET username = '".$username."', password = '".$password."', role_id = '".$role_id."', full_name = '".$full_name."', active_status = '".$active_status."', updated_at = UTC_TIMESTAMP() WHERE user_id = '".$user_id."' LIMIT 1";

  }else{

    $s = "UPDATE $tbl SET username = '".$username."', role_id = '".$role_id."', full_name = '".$full_name."', active_status = '".$active_status."', updated_at = UTC_TIMESTAMP() WHERE user_id = '".$user_id."' LIMIT 1";
  }
  mysqli_query($conn, $s);

  /* add log */
  addLog($conn, $_SESSION['user_id'],'EDIT USER', 'edit user for id: '.$user_id);

  echo "<script>";
  echo "alert('$data_success'); window.location=\"user?".Encryption::encode($client_id)."\"";
  echo "</script>";

}
?>
