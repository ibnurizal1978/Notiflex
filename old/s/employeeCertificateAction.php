<?php
session_start();
require_once "../config.php";
require_once "inc/check_session.php";
$action  = $param[1];
$tbl     = 'tbl_employee';

if($action == "add") {
  $full_name	    =	input_data($_POST['full_name']);
  $designation	    =	input_data($_POST['designation']);
  $phone	        =	input_data($_POST['phone']);
  $email	        =	input_data($_POST['email']);


  if($full_name == '' || $designation == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama yang sama
  $s = "SELECT full_name FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND full_name = '".$full_name."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //sukses
  $s = "INSERT INTO $tbl SET full_name = '".$full_name."', designation = '".$designation."', phone = '".$phone."', email = '".$email."', client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s);
  $last_id = mysqli_insert_id($conn);

  /* add log */
  addLog($conn, $_SESSION['user_id'],'ADD EMPLOYEE', 'Add new employee name: '.$full_name);
  echo "<script>";
  echo "alert('$data_success'); window.location=\"employee\"";
  echo "</script>";

}


if($action == "edit") {
  $employee_id      = Encryption::decode($_POST['employee_id']);
  $full_name	    =	input_data($_POST['full_name']);
  $designation	    =	input_data($_POST['designation']);
  $phone	        =	input_data($_POST['phone']);
  $email	        =	input_data($_POST['email']);


  if($employee_id == '' || $full_name == '' || $designation == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama group yang sama
  $s = "SELECT full_name FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND full_name = '".$full_name."' AND designation = '".$designation."'  AND employee_id <> '".$employee_id."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }


    $s = "UPDATE $tbl SET full_name = '".$full_name."', designation = '".$designation."', phone = '".$phone."', email = '".$email."', active_status = '".$active_status."', updated_at = UTC_TIMESTAMP() WHERE category_id = '".$category_id."' LIMIT 1";
    mysqli_query($conn, $s);


  /* add log */
  addLog($conn, $_SESSION['user_id'],'EDIT EMPLOYEE', 'edit employee name for id: '.$employee_id);

  echo "<script>";
  echo "alert('$data_success'); window.location=\"employee\"";
  echo "</script>";

}
?>
