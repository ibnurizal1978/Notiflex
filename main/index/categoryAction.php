<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../../checkSession.php";
require_once "../../components.php";
//require_once "access.php";
$tbl1 = 'tbl_category_master';

// this is if action = add
if($param[1]=='add')
{
  
    $category_master_name	=	input_data($_POST['category_master_name']);

    if($category_master_name == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    // check if data is exist? reject it
    $s = "SELECT category_master_name FROM $tbl1 WHERE category_master_name = '".$category_master_name."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    $h = mysqli_query($conn,$s);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
        icon: 'error',
        text: '$notif_data_duplicate',
        })</script>";
        exit();
    }

    //success, insert data
    $s 	= "INSERT INTO $tbl1 SET category_master_name = '".$category_master_name."', created_at = UTC_TIMESTAMP(), client_id = '".$_SESSION['client_id']."'";
    mysqli_query($conn,$s);
    $last_id = mysqli_insert_id($conn);

    // add log
    addLog($conn, $_SESSION['user_id'],'ADD CATEGORY', 'add category id: '.$last_id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'category';
    });</script>";
    exit();
}



// this is if action = edit
if($param[1]=='edit')
{
  
    $id		                =	input_data(Encryption::decode($_POST['id']));
    $category_master_name	=	input_data($_POST['category_master_name']);

    if($category_master_name == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    //is there a duplicate name?
    $sql 	= "SELECT category_master_name FROM $tbl1 WHERE client_id = '".$_SESSION['client_id']."' AND category_master_name = '".$category_master_name."' AND category_master_id <> '".$id."' LIMIT 1";
    $h 		= mysqli_query($conn,$sql);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_duplicate',
        })</script>";
        exit();
    }

    $s = "UPDATE $tbl1 SET category_master_name ='".$category_master_name."', updated_at = UTC_TIMESTAMP() WHERE category_master_id = '".$id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    mysqli_query($conn, $s);

    // add log
    addLog($conn, $_SESSION['user_id'],'EDIT CATEGORY', 'edit category id: '.$id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'category';
    });</script>";
    exit();
}