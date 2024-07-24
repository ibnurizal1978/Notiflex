<?php
session_start();
require_once "../config.php";
require_once "inc/check_session.php";
$action  = $param[1];
$tbl     = 'tbl_list';
$tbl2    = 'tbl_list_expiration';
$tbl3    = 'tbl_list_upload';

if($action == "add") {
  $name	            =	input_data($_POST['name']);
  $active_status	=	input_data($_POST['active_status']);
  @$position	    =	input_data(@$_POST['position']);
  @$email	        =	input_data(@$_POST['email']);
  @$phone	        =	input_data(@$_POST['phone']);
  @$address	        =	input_data(@$_POST['address']);
  @$city	        =	input_data(@$_POST['city']);
  @$vehicle_plate	=	input_data(@$_POST['vehicle_plate']);
  @$vehicle_type	=	input_data(@$_POST['vehicle_type']);
  @$vehicle_year	=	input_data(@$_POST['vehicle_year']);
  @$electronic_type	=	input_data(@$_POST['electronic_type']);
  @$serial_number	=	input_data(@$_POST['serial_number']);
  @$start_date	  =	input_data(@$_POST['start_date']);
  @$expired_date	=	input_data(@$_POST['expired_date']);
  $param1         = base64_decode($_POST['param1']);


  if($param1 == $module_employee)
  {
    $vehicle_year = '1990';
    if($name == '' || $position == '' || $email == '' || $phone == '')
    {
        echo "<script>";
        echo "alert('$data_empty'); window.location.href=history.back()";
        echo "</script>";
        exit();
    }
  }

    if($param1 == $module_vehicle)
    {
        if($name == '' || $vehicle_plate == '' || $vehicle_year == '' || $vehicle_type == '')
        {
            echo "<script>";
            echo "alert('$data_empty'); window.location.href=history.back()";
            echo "</script>";
            exit();
        }
    }

    if($param1 == $module_clients || $param1 == $module_vendors)
    {
        $vehicle_year = '1990';
        if($name == '' || $email == '' || $phone == '' || $address == '' || $city == '')
        {
            echo "<script>";
            echo "alert('$data_empty'); window.location.href=history.back()";
            echo "</script>";
            exit();
        }
    }

    if($param1 == $module_electronics)
    {
        $vehicle_year = '1990';
        if($name == '' || $electronic_type == '' || $serial_number == '')
        {
            echo "<script>";
            echo "alert('$data_empty'); window.location.href=history.back()";
            echo "</script>";
            exit();
        }
    }

    if($param1 == $module_domain)
    {
        $vehicle_year = '1990';
        if($name == '' || $expired_date == '')
        {
            echo "<script>";
            echo "alert('$data_empty'); window.location.href=history.back()";
            echo "</script>";
            exit();
        }
    }

  //check apakah ada nama group yang sama
  $s = "SELECT name FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND name = '".$name."' LIMIT 1";
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

  $s = "INSERT INTO $tbl SET name = '".$name."', position = '".$position."', email = '".$email."', phone = '".$phone."',
  address = '".$address."', city = '".$city."', vehicle_plate = '".$vehicle_plate."', vehicle_type = '".$vehicle_type."',
  vehicle_year = '".$vehicle_year."', electronic_type = '".$electronic_type."', serial_number = '".$serial_number."', type = '".$param1."', client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s);

  /* if type = domain then directly insert expired date here */
  if($param1 == $module_domain)
  {
    $last_id = mysqli_insert_id($conn);
    $s2 = "INSERT INTO $tbl2 SET list_id = '".$last_id."', category_id = '0', start_date = '".$start_date."', expired_date = '".$expired_date."', held_by = '0',  client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";  
    mysqli_query($conn, $s2);
  }

  /* add log */
  addLog($conn, $_SESSION['user_id'],'ADD LIST', 'Add new list name: '.$name);


  echo "<script>";
  echo "alert('$data_success'); window.location=\"list?".$_POST['param1']."\"";
  echo "</script>";
  exit();
}


if($action == "edit") {
  $id               = Encryption::decode($_POST['id']);
  $name	            =	input_data($_POST['name']);
  $active_status	=	input_data($_POST['active_status']);
  @$position	    =	input_data(@$_POST['position']);
  @$email	        =	input_data(@$_POST['email']);
  @$phone	        =	input_data(@$_POST['phone']);
  @$address	        =	input_data(@$_POST['address']);
  @$city	        =	input_data(@$_POST['city']);
  @$vehicle_plate	=	input_data(@$_POST['vehicle_plate']);
  @$vehicle_type	=	input_data(@$_POST['vehicle_type']);
  @$vehicle_year	=	input_data(@$_POST['vehicle_year']);
  @$electronic_type	=	input_data(@$_POST['electronic_type']);
  @$serial_number	=	input_data(@$_POST['serial_number']);
  $param1           = base64_decode($_POST['param1']);


  if($param1 == $module_employee)
  {
    $vehicle_year = '1990';
    if($name == '' || $position == '' || $email == '' || $phone == '')
    {
        echo "<script>";
        echo "alert('$data_empty'); window.location.href=history.back()";
        echo "</script>";
        exit();
    }
  }

    if($param1 == $module_vehicle)
    {
        if($name == '' || $vehicle_plate == '' || $vehicle_year == '' || $vehicle_type == '')
        {
            echo "<script>";
            echo "alert('$data_empty'); window.location.href=history.back()";
            echo "</script>";
            exit();
        }
    }

    if($param1 == $module_clients || $param1 == $module_vendors)
    {
        $vehicle_year = '1990';
        if($name == '' || $email == '' || $phone == '' || $address == '' || $city == '')
        {
            echo "<script>";
            echo "alert('$data_empty'); window.location.href=history.back()";
            echo "</script>";
            exit();
        }
    }

    if($param1 == $module_electronics)
    {
        $vehicle_year = '1990';
        if($name == '' || $electronic_type == '' || $serial_number == '')
        {
            echo "<script>";
            echo "alert('$data_empty'); window.location.href=history.back()";
            echo "</script>";
            exit();
        }
    }

  //check apakah ada nama group yang sama
  $s = "SELECT name FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND name = '".$name."' AND id <> '".$id."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }


    $s = "UPDATE $tbl SET name = '".$name."', position = '".$position."', email = '".$email."', phone = '".$phone."',
    address = '".$address."', city = '".$city."', vehicle_plate = '".$vehicle_plate."', vehicle_type = '".$vehicle_type."',
    vehicle_year = '".$vehicle_year."',  electronic_type = '".$electronic_type."', serial_number = '".$serial_number."', active_status = '".$active_status."', updated_at = UTC_TIMESTAMP() WHERE id = '".$id."' LIMIT 1";
    mysqli_query($conn, $s);

  /* add log */
  addLog($conn, $_SESSION['user_id'],'EDIT LIST', 'edit category name for id: '.$id);

  echo "<script>";
  echo "alert('$data_success'); window.location=\"list?".$_POST['param1']."\"";
  echo "</script>";

}


if($action == "addExpiration") {
  $list_id	        =	Encryption::decode($_POST['id']);
  $category_id	    =	input_data($_POST['category_id']);
  $start_date	      =	input_data($_POST['start_date']);
  $expired_date	    =	input_data($_POST['expired_date']);
  $expired_date2	  =	input_data($_POST['expired_date2']);
  $held_by	        =	input_data($_POST['held_by']);

  if($list_id == '' || $category_id == '' || $start_date == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama yang sama
  $s = "SELECT list_id FROM $tbl2 WHERE client_id = '".$_SESSION['client_id']."' AND list_id = '".$list_id."' AND category_id = '".$category_id."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  if($_FILES["list_upload"]["error"] != 0) {
    echo "<script>";
    echo "alert('You must upload a photo/document for evidence'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //sukses
  if ($_FILES['list_upload']['size'] > 3000000) {
    echo "<script>";
    echo "alert('File size must not more than 3MB'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }
  
    $permitted_chars 	= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $temp 				= explode(".", $_FILES["list_upload"]["name"]);
    $name 				= $_FILES['list_upload']['name'];
    $target_dir 		= $image_path.'../list_upload/';
    $photo1 		= substr(str_shuffle($permitted_chars), 0, 16).'.'.end($temp);
    $target_file 		= $target_dir.$photo1;
    $imageFileType 		= strtolower($temp[1]);
    $extensions_arr 	= array("jpg","jpeg","png","gif");
  
    if($imageFileType != "png" && $imageFileType != "xlsx" && $imageFileType != "xls" && $imageFileType != "docx" && $imageFileType != "doc" && $imageFileType != "pdf" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" && $imageFileType != "jpeg") {
      //echo "<script>";
      echo "alert('File type must PDF, DOC or DOCX, XLS or XLSX, PNG, JPG or GIF'); window.location.href=history.back()";
      echo "</script>";
      exit();
    }
    move_uploaded_file($_FILES["list_upload"]["tmp_name"], $target_file);
    $source_image = $target_file;
    $image_destination = $target_dir.$photo1;
  
  //check which one user choose for expired method, type the date or choose the duration day?
  if($expired_date2 <> '')
  {
    $s_check = "SELECT category_duration, category_duration_cycle FROM tbl_category WHERE category_id = '".$category_id."' LIMIT 1";
    $h_check = mysqli_query($conn, $s_check);
    $r_check = mysqli_fetch_assoc($h_check);
    $s = "INSERT INTO $tbl2 SET list_id = '".$list_id."', category_id = '".$category_id."', start_date = '".$start_date."', expired_date = DATE_ADD('".$start_date."', INTERVAL ".$r_check['category_duration']." ".$r_check['category_duration_cycle']."), held_by = '".$held_by."',  client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  }else{
    $s = "INSERT INTO $tbl2 SET list_id = '".$list_id."', category_id = '".$category_id."', start_date = '".$start_date."', expired_date = '".$expired_date."', held_by = '".$held_by."',  client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  }
  
  mysqli_query($conn, $s);
  $last_id = mysqli_insert_id($conn);

  $s2 = "INSERT INTO $tbl3 SET list_expiration_id = '".$last_id."', photo_name = '".$photo1."', client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s2);

  /* add log */
  addLog($conn, $_SESSION['user_id'],'ADD EXPIRATION LIST', 'Add new expiration list: '.$list_id);
  echo "<script>";
  echo "alert('$data_success'); window.location=\"listDetail?".$_POST['param1']."?".$_POST['param2']."#list\"";
  echo "</script>";
  exit();
}

if($action == "renewExpiration") {
  $list_id	        =	Encryption::decode($_POST['id']);
  $old_id	          =	Encryption::decode($_POST['old_id']);
  $category_id	    =	Encryption::decode($_POST['category_id']);
  $start_date	      =	input_data($_POST['start_date']);
  $expired_date	    =	input_data($_POST['expired_date']);
  $expired_date2	  =	input_data($_POST['expired_date2']);
  $held_by	        =	input_data($_POST['held_by']);

  if($list_id == '' || $category_id == '' || $start_date == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  if($_FILES["list_upload"]["error"] != 0) {
    echo "<script>";
    echo "alert('You must upload a photo/document for evidence'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //sukses
  if ($_FILES['list_upload']['size'] > 3000000) {
    echo "<script>";
    echo "alert('File size must not more than 3MB'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }
  
    $permitted_chars 	= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $temp 				= explode(".", $_FILES["list_upload"]["name"]);
    $name 				= $_FILES['list_upload']['name'];
    $target_dir 		= $image_path.'../list_upload/';
    $photo1 		= substr(str_shuffle($permitted_chars), 0, 16).'.'.end($temp);
    $target_file 		= $target_dir.$photo1;
    $imageFileType 		= strtolower($temp[1]);
    $extensions_arr 	= array("jpg","jpeg","png","gif");
  
    if($imageFileType != "png" && $imageFileType != "xlsx" && $imageFileType != "xls" && $imageFileType != "docx" && $imageFileType != "doc" && $imageFileType != "pdf" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" && $imageFileType != "jpeg") {
      //echo "<script>";
      echo "alert('File type must PDF, DOC or DOCX, XLS or XLSX, PNG, JPG or GIF'); window.location.href=history.back()";
      echo "</script>";
      exit();
    }
    move_uploaded_file($_FILES["list_upload"]["tmp_name"], $target_file);
    $source_image = $target_file;
    $image_destination = $target_dir.$photo1;
  
  //check which one user choose for expired method, type the date or choose the duration day?
  if($expired_date2 <> '')
  {
    $s_check = "SELECT category_duration, category_duration_cycle FROM tbl_category WHERE category_id = '".$category_id."' LIMIT 1";
    $h_check = mysqli_query($conn, $s_check);
    $r_check = mysqli_fetch_assoc($h_check);
    $s = "INSERT INTO $tbl2 SET list_id = '".$list_id."', category_id = '".$category_id."', start_date = '".$start_date."', expired_date = DATE_ADD('".$start_date."', INTERVAL ".$r_check['category_duration']." ".$r_check['category_duration_cycle']."), held_by = '".$held_by."',  client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  }else{
    $s = "INSERT INTO $tbl2 SET list_id = '".$list_id."', category_id = '".$category_id."', start_date = '".$start_date."', expired_date = '".$expired_date."', held_by = '".$held_by."',  client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  }
  
  mysqli_query($conn, $s);
  $last_id = mysqli_insert_id($conn);

  $s2 = "INSERT INTO $tbl3 SET list_expiration_id = '".$last_id."', photo_name = '".$photo1."', client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s2);

  $s3 = "UPDATE $tbl2 SET archived_status = 1 WHERE list_expiration_id = '".$old_id."' AND client_id = '".$_SESSION['client_id']."'";
  mysqli_query($conn, $s3);

  /* add log */
  addLog($conn, $_SESSION['user_id'],'ADD EXPIRATION LIST', 'Add new expiration list: '.$list_id);

  if($_POST['param4'] == 1)
  {
    echo "<script>";
    echo "alert('$data_success'); window.location=\"listExpiration?".$_POST['param5']."\"";
    echo "</script>"; 
    exit();   
  }else{
    echo "<script>";
    echo "alert('$data_success'); window.location=\"listDetail?".$_POST['param1']."?".$_POST['param2']."#list\"";
    echo "</script>";
    exit();
  }
 

}
?>
