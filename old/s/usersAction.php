<?php
session_start();
require_once "../config.php";
require_once "inc/check_session.php";
$action  = $param[1];
$tbl     = 'tbl_user';

if($action == "add") {
  $username	        =	input_data($_POST['username']);
  $password	        =	input_data($_POST['password']);
  $full_name	      =	input_data($_POST['full_name']);

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

  if($username == '' || $full_name == '' || $_POST['nav_menu_id'] == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama group yang sama
  $s = "SELECT username FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND username = '".$username."' LIMIT 1";
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
  $s = "INSERT INTO $tbl SET username = '".$username."', password = '".$password."', full_name = '".$full_name."', client_id = '".$_SESSION['client_id']."', user_type = 'SELLER', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s);
  $last_id = mysqli_insert_id($conn);

  $banyaknya = count(@$_POST['nav_menu_id']);
  for ($i=0; $i<$banyaknya; $i++) {
    if(@$_POST['nav_menu_id'][$i]) {
      $sql_menu = "SELECT nav_menu_id from tbl_nav_menu WHERE nav_menu_id = '".@$_POST['menu_id'][$i]."'";
      $h_menu   = mysqli_query($conn,$sql_menu);
      $row_menu = mysqli_fetch_assoc($h_menu);
    $sql_menu2  = "INSERT INTO tbl_nav_user(nav_menu_id,user_id,client_id) VALUES ('".@$_POST['nav_menu_id'][$i]."','".$last_id."','".$_SESSION['client_id']."')";
    mysqli_query($conn,$sql_menu2);
    //echo $sql_menu2.'<br/>';
    }
  }

  /* add log */
  addLog($conn, $_SESSION['user_id'],'ADD USER', 'Add new user name: '.$username);
  echo "<script>";
  echo "alert('$data_success'); window.location=\"users\"";
  echo "</script>";

}


if($action == "edit") {
  $user_id          = Encryption::decode($_POST['user_id']);
  $password	        =	input_data($_POST['password']);
  $full_name	      =	input_data($_POST['full_name']);
  $active_status	  =	input_data($_POST['active_status']);

  if($full_name == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
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

    $s = "UPDATE $tbl SET password = '".$password."', full_name = '".$full_name."', active_status = '".$active_status."', updated_at = UTC_TIMESTAMP() WHERE user_id = '".$user_id."' LIMIT 1";

  }else{

    $s = "UPDATE $tbl SET full_name = '".$full_name."', active_status = '".$active_status."', updated_at = UTC_TIMESTAMP() WHERE user_id = '".$user_id."' LIMIT 1";
  }
  mysqli_query($conn, $s);

  $sql_delete = "DELETE FROM tbl_nav_user WHERE user_id = '".$user_id."'";
  mysqli_query($conn,$sql_delete);
  mysqli_query($conn, "DELETE FROM tbl_nav_category_user WHERE user_id = '".$user_id."'");

  $banyaknya = count(@$_POST['nav_menu_id']);
  for ($i=0; $i<$banyaknya; $i++) {
    if(@$_POST['nav_menu_id'][$i]) {
      $sql_menu = "SELECT nav_menu_id from tbl_nav_menu WHERE nav_menu_id = '".@$_POST['nav_menu_id'][$i]."'";
      $h_menu   = mysqli_query($conn,$sql_menu);
      $row_menu = mysqli_fetch_assoc($h_menu);
      $sql_menu2  = "INSERT INTO tbl_nav_user(nav_menu_id,user_id,client_id) VALUES ('".$_POST['nav_menu_id'][$i]."','".$user_id."','".$_SESSION['client_id']."')";
      mysqli_query($conn,$sql_menu2);
      //echo $sql_menu2.'<br/>';
    }
  }

  /*$banyaknya2 = count(@$_POST['category_id']);
  for ($i=0; $i<$banyaknya2; $i++) {
    if(@$_POST['category_id'][$i]) {
    $sql_menu2  = "INSERT INTO tbl_nav_category_user(category_id,user_id,client_id) VALUES ('".@$_POST['category_id'][$i]."','".$user_id."','".$_SESSION['client_id']."')";
    mysqli_query($conn,$sql_menu2);
    //echo $sql_menu2.'<br/>';
    }
  }*/


  /* add log */
  addLog($conn, $_SESSION['user_id'],'EDIT USER', 'edit user for id: '.$user_id);

  echo "<script>";
  echo "alert('$data_success'); window.location=\"users\"";
  echo "</script>";

}
?>
