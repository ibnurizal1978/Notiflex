<?php
session_start();
require_once "../config.php";
require_once "inc/check_session.php";
$action  = $param[1];
$tbl     = 'tbl_category';

if($action == "add") {
  $category_name	      =	input_data($_POST['category_name']);
  $category_description	=	input_data($_POST['category_description']);
  $category_duration	  =	input_data($_POST['category_duration']);
  $category_duration_cycle	  =	input_data($_POST['category_duration_cycle']);


  if($category_name == '' || $category_duration == '' || $category_duration_cycle == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama group yang sama
  $s = "SELECT category_name FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND category_name = '".$category_name."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //sukses
  $param1 = base64_decode($_POST['param1']);

  if($param1 == 'EMPLOYEE' && $_POST['param2'] == 1)
  {
    $type = 'EMPLOYEE CERTIFICATION';
  }elseif($param1 == 'EMPLOYEE' && $_POST['param2'] == 2)
  {
    $type = 'EMPLOYEE CONTRACT';
  }else{
    $type = $param1;
  }

  $s = "INSERT INTO $tbl SET category_name = '".$category_name."', category_description = '".$category_description."', category_duration = '".$category_duration."', category_duration_cycle = '".$category_duration_cycle."', category_type = '".$type."', client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s);
  /* add log */
  addLog($conn, $_SESSION['user_id'],'ADD CATEGORY', 'Add new category name: '.$category_name);

  if(@$_POST['param2'] <> '')
  {
    echo "<script>";
    echo "alert('$data_success'); window.location=\"category?".$_POST['param1']."?".@$_POST['param2']."\"";
    echo "</script>";
  }else{
    echo "<script>";
    echo "alert('$data_success'); window.location=\"category?".$_POST['param1']."\"";
    echo "</script>";
  }
  exit();
}


if($action == "edit") {
  $category_id          = Encryption::decode($_POST['category_id']);
  $category_name	      =	input_data($_POST['category_name']);
  $active_status	      =	input_data($_POST['active_status']);
  $category_description	=	input_data($_POST['category_description']);
  $category_duration	   =	input_data($_POST['category_duration']);
  $category_duration_cycle	  =	input_data($_POST['category_duration_cycle']);

  if($category_name == '' || $category_id == '' || $category_duration == '' || $category_duration_cycle == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama group yang sama
  $s = "SELECT category_name FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND category_name = '".$category_name."' AND category_id <> '".$category_id."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }


    $s = "UPDATE $tbl SET category_name = '".$category_name."', category_description = '".$category_description."', category_duration = '".$category_duration."', category_duration_cycle = '".$category_duration_cycle."', active_status = '".$active_status."', updated_at = UTC_TIMESTAMP() WHERE category_id = '".$category_id."' LIMIT 1";
    mysqli_query($conn, $s);


  /* add log */
  addLog($conn, $_SESSION['user_id'],'EDIT CATEGORY', 'edit category name for id: '.$category_id);

  echo "<script>";
  echo "alert('$data_success'); window.location=\"category?".$_POST['param1']."?".@$_POST['param2']."\"";
  echo "</script>";

}
?>
