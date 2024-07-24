<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../../checkSession.php";
require_once "../../components.php";
//require_once "access.php";
$tbl1 = 'tbl_user';
$tbl2 = 'tbl_client';

// this is if action = add
if($param[1]=='add')
{
  
  $full_name	=	input_data($_POST['full_name']);
  $username		=	input_data($_POST['username']);
  $password		=	input_data($_POST['password']);

  if($username == "" || $full_name == "" || $password == "") {
    echo "<script>Swal.fire({
        icon: 'error',
        text: '$notif_data_empty',
    })</script>";
    exit();
  }

  // check if data is exist? reject it
  $sql 	= "SELECT username FROM tbl_user WHERE username = '".$username."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
  $h 		= mysqli_query($conn,$sql);
  if(mysqli_num_rows($h)>0) {
    echo "<script>Swal.fire({
      icon: 'error',
      text: '$notif_data_duplicate',
    })</script>";
    exit();
  }

  //check password pattern
  function valid_pass($password) {
    if (!preg_match_all('$\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\s*$', $password))
        return FALSE;
    return TRUE;
  }

  if(!valid_pass($password)) {
    echo "<script>Swal.fire({
      icon: 'error',
      text: 'Password must have a minimum of 6 characters and must have 1 uppercase letter, 1 lowercase letter and a number.',
    })</script>";
    exit();
  }


  if(strlen($password)<6) {
    echo "<script>Swal.fire({
      icon: 'error',
      text: 'Minimum password is 6 characters',
    })</script>";
    exit();
  }


  //add their menu access too
  if(@$_POST['nav_menu_id'] == 0)
  {
    echo "<script>Swal.fire({
      icon: 'error',
      text: 'You must select at least one module to assign to this user',
    })</script>";
    exit();
  }

  //success, insert new user
  $password = password_hash($password, PASSWORD_DEFAULT);

  $s = "INSERT INTO $tbl1 SET username = '".$username."', password = '".$password."', full_name = '".$full_name."', client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s);
  $last_id = mysqli_insert_id($conn);

  $banyaknya = count(@$_POST['nav_menu_id']);
  for ($i=0; $i<$banyaknya; $i++) {
    if(@$_POST['nav_menu_id'][$i]) {
      $s  = "INSERT INTO tbl_nav_user(nav_menu_id,user_id,client_id) VALUES ('".$_POST['nav_menu_id'][$i]."','".$last_id."','".$_SESSION['client_id']."')";
      mysqli_query($conn,$s);
    }
  }

  // add log
  addLog($conn, $_SESSION['user_id'],'ADD USER', 'add username: '.$username);

  echo "<script>Swal.fire({
    icon: 'success',
    text: '$notif_data_success',
  }).then(function() {
    window.location = 'users';
  });</script>";
  exit();
}



// this is if action = edit
if($param[1]=='edit')
{
  
  $id		          =	input_data(Encryption::decode($_POST['id']));
  $full_name		  =	input_data($_POST['full_name']);
  $active_status	=	input_data($_POST['active_status']);

  if($id == "" || $full_name == "") {
    echo "<script>Swal.fire({
        icon: 'error',
        text: '$notif_data_empty',
    })</script>";
    exit();
  }

  // if password column filled, this is active
  if($_POST['password'] <> '')
  {
    
    $password = $_POST['password'];
    function valid_pass($password) {
        if (!preg_match_all('$\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\s*$', $password))
            return FALSE;
        return TRUE;
    }

    if(!valid_pass($password)) {
        echo "<script>Swal.fire({
            icon: 'error',
            text: 'Password must have a minimum of 6 characters and must have 1 uppercase letter, 1 lowercase letter and a number',
        })</script>";
        exit();
    }

    if(strlen($password)<6) {
      echo "<script>Swal.fire({
          icon: 'error',
          text: 'Minimum password is 6 characters',
      })</script>";
      exit();
    }

    //generate new password
    $password = password_hash($password, PASSWORD_DEFAULT);

    $s = "UPDATE $tbl1 SET password = '".$password."', full_name = '".$full_name."', active_status = '".$active_status."', updated_at = UTC_TIMESTAMP() WHERE user_id = '".$id."' LIMIT 1";

  }else{

    $s = "UPDATE tbl_user SET full_name = '".$full_name."', active_status = '".$active_status."',  updated_at = UTC_TIMESTAMP() WHERE user_id = '".$id."' LIMIT 1";
  }
  mysqli_query($conn, $s);

  // insert module access to table navigation
  if(@$_POST['nav_menu_id'] == 0)
  {
    echo "<script>Swal.fire({
      icon: 'error',
      text: 'You must select at least one module to assign to this user',
    })</script>";
    exit();
  }

  // delete existing menu from user (revoke their menu)
  $s = "DELETE FROM tbl_nav_user WHERE user_id = '".$id."'";
  mysqli_query($conn, $s);

  $banyaknya = count(@$_POST['nav_menu_id']);
  for ($i=0; $i<$banyaknya; $i++) {
    if(@$_POST['nav_menu_id'][$i]) {
      $s  = "INSERT INTO tbl_nav_user(nav_menu_id,user_id,client_id) VALUES ('".$_POST['nav_menu_id'][$i]."','".$id."','".$_SESSION['client_id']."')";
      mysqli_query($conn,$s);
    }
  }

  // add log
  addLog($conn, $_SESSION['user_id'],'EDIT USER', 'edit user id: '.$id);

  echo "<script>Swal.fire({
    icon: 'success',
    text: '$notif_data_success',
  }).then(function() {
    window.location = 'users';
  });</script>";
  exit();
}