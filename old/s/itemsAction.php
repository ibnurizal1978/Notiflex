<?php
session_start();
require_once "../config.php";
require_once "inc/check_session.php";
$action  = $param[1];
$tbl     = 'tbl_items';

if($action == "add") {
    $category_id	=	Encryption::decode($_POST['category_id']);
  $items_name	    =	input_data($_POST['items_name']);
  $start_date	    =	input_data($_POST['start_date']);
  $expired_date	    =	input_data($_POST['expired_date']);
  $employee_id	    =	input_data($_POST['employee_id']);

  if($items_name == '' || $category_id == '' || $start_date == '' || $expired_date == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama yang sama
  $s = "SELECT items_name FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND items_name = '".$items_name."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  echo 'a';
  if($_FILES["items_photo"]["error"] != 0) {
    echo 'aaa';
    //echo "<script>";
    echo "alert('You must upload photo for evidence'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //sukses
  if ($_FILES['items_photo']['size'] > 3000000) {
    echo "<script>";
    echo "alert('File size must not more than 3MB'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }
  
    $permitted_chars 	= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $temp 				= explode(".", $_FILES["fCover"]["name"]);
    $name 				= $_FILES['items_photo']['name'];
    $target_dir 		= $image_path.'../items_photo/';
    $photo1 		= substr(str_shuffle($permitted_chars), 0, 16).'.'.end($temp);
    $target_file 		= $target_dir.$photo1;
    $imageFileType 		= strtolower($temp[1]);
    $extensions_arr 	= array("jpg","jpeg","png","gif");
  
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" && $imageFileType != "jpeg") {
      echo "<script>";
      echo "alert('File type must JPG or GIF'); window.location.href=history.back()";
      echo "</script>";
      exit();
    }
    move_uploaded_file($_FILES["items_photo"]["tmp_name"], $target_file);
    $source_image = $target_file;
    $image_destination = $target_dir.$photo1;
  

  $s = "INSERT INTO $tbl SET items_name = '".$items_name."', category_id = '".$category_id."', start_date = '".$start_date."', expired_date = '".$expired_date."', employee_id = '".$employee_id."',  client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s);
  $last_id = mysqli_insert_id($conn);

  /* add log */
  addLog($conn, $_SESSION['user_id'],'ADD ITEMS', 'Add new items name: '.$items_name);
  echo "<script>";
  echo "alert('$data_added'); window.location=\"items?".$_POST['category_id']."\"";
  echo "</script>";
  exit();
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
