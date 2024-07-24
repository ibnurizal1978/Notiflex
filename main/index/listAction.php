<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../../checkSession.php";
require_once "../../components.php";
//require_once "access.php";
$tbl1 = 'tbl_list';
$tbl2 = 'tbl_list_expiration';
$tbl3 = 'tbl_list_upload';

// this is if action = add
if($param[1]=='add')
{
  
    $name	            =	input_data($_POST['name']);
    $description	    =	input_data($_POST['description']);
    $reminder_type_id	=	input_data($_POST['reminder_type_id']);
    $additional_data	=	input_data($_POST['additional_data']);
    $start_date	        =	input_data($_POST['start_date']);
    $expired_date	    =	input_data($_POST['expired_date']);
    $expired_day	    =	input_data(@$_POST['expired_day']);
    $expired_duration	=	input_data(@$_POST['expired_duration']);

    if($name == "" || $reminder_type_id == "" || $additional_data == "" || $start_date == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    // check if data is exist? reject it
    $s = "SELECT name FROM $tbl1 WHERE name = '".$name."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    $h = mysqli_query($conn,$s);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
        icon: 'error',
        text: '$notif_data_duplicate',
        })</script>";
        exit();
    }

    //file didn't upload? reject it
    if($_FILES["list_upload"]["error"] != 0) {
        echo "<script>Swal.fire({
            icon: 'error',
            text: 'Please upload document or photo for evidence',
            })</script>";
            exit();
    }

    //file larger than 3MB? reject it
    if ($_FILES['list_upload']['size'] > 3000000) {
        echo "<script>Swal.fire({
            icon: 'error',
            text: 'File size should not more than 3 MB',
            })</script>";
            exit();
    }

    //prepare file attribute
    $temp 				= explode(".", $_FILES["list_upload"]["name"]);
    $file_name 			= $_FILES['list_upload']['name'];
    $target_dir 		= '../../uploads/';
    $imageFileType 		= strtolower($temp[1]);
    $extensions_arr 	= array("jpg","jpeg","png","gif");
  
    if($imageFileType == "exe" || $imageFileType == "apk") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: 'Do not upload exe or apk file!',
            })</script>";
            exit();
    }

    //if user choose expired date, it will follow date format from frontend. otherwise must follow alternative expired date
    if($expired_date <> '')
    {
        $exp_date = "'".$expired_date."'";
    }else{
        $exp_date = "DATE_ADD('".$start_date."', INTERVAL ".$expired_day." ".$expired_duration.")";
    }

    //success
    //upload file
    move_uploaded_file($_FILES["list_upload"]["tmp_name"], $target_dir.$file_name);

    //insert into tbl_list
    $s1 	= "INSERT INTO $tbl1 SET name = '".$name."', description = '".$description."', reminder_type_id = '".$reminder_type_id."', additional_data = '".$additional_data."', created_at = UTC_TIMESTAMP(), client_id = '".$_SESSION['client_id']."'";
    mysqli_query($conn,$s1);
    $last_id = mysqli_insert_id($conn);

    //insert into tbl_list_expiration
    $s2 = "INSERT INTO $tbl2 SET list_id = '".$last_id."', start_date = '".$start_date."', expired_date = ".$exp_date.", client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
    mysqli_query($conn,$s2);
    $last_id2 = mysqli_insert_id($conn);

    //insert into tbl_list_upload
    $s3 = "INSERT INTO $tbl3 SET list_expiration_id = '".$last_id2."', file_name = '".$file_name."', client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
    mysqli_query($conn, $s3);

    // add log
    addLog($conn, $_SESSION['user_id'],'ADD LIST DATA', 'add id: '.$last_id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'list';
    });</script>";
    exit();
}