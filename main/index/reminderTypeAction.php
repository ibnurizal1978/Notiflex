<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../../checkSession.php";
require_once "../../components.php";
//require_once "access.php";
$tbl1 = 'tbl_reminder_type';
$tbl2 = 'tbl_reminder_duration';

// this is if action = add
if($param[1]=='add')
{
  
    $reminder_type_name	=	input_data($_POST['reminder_type_name']);
    $category_master_id	=	input_data($_POST['category_master_id']);

    if($reminder_type_name == "" || $category_master_id == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    // check if data is exist? reject it
    $s = "SELECT reminder_type_name FROM $tbl1 WHERE reminder_type_name = '".$reminder_type_name."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    $h = mysqli_query($conn,$s);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
        icon: 'error',
        text: '$notif_data_duplicate',
        })</script>";
        exit();
    }

    //success, insert data
    $s 	= "INSERT INTO $tbl1 SET reminder_type_name = '".$reminder_type_name."', category_master_id = '".$category_master_id."', created_at = UTC_TIMESTAMP(), client_id = '".$_SESSION['client_id']."'";
    mysqli_query($conn,$s);
    $last_id = mysqli_insert_id($conn);

    $banyaknya = count(@$_POST['reminder_day']);
    for ($i=0; $i<$banyaknya; $i++) {
      if(@$_POST['reminder_day'][$i]) {
          $s_menu  = "INSERT INTO $tbl2 (reminder_type_id, reminder_day, group_receiver_id, reminder_via, client_id, created_at) VALUES ('".$last_id."', '".@$_POST['reminder_day'][$i]."', '".@$_POST['group_receiver_id'][$i]."', 'EMAIL', '".$_SESSION['client_id']."', UTC_TIMESTAMP())";
          mysqli_query($conn,$s_menu);
      }
    }

    // add log
    addLog($conn, $_SESSION['user_id'],'ADD REMINDER TYPE', 'add reminder type id: '.$last_id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'reminderType';
    });</script>";
    exit();
}



// this is if action = edit
if($param[1]=='edit')
{
  
    $id		            =	input_data(Encryption::decode($_POST['id']));
    $reminder_type_name	=	input_data($_POST['reminder_type_name']);
    $category_master_id	=	input_data($_POST['category_master_id']);

    if($reminder_type_name == "" || $category_master_id == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    //is there a duplicate name?
    $sql 	= "SELECT reminder_type_name FROM $tbl1 WHERE client_id = '".$_SESSION['client_id']."' AND reminder_type_name = '".$reminder_type_name."' AND reminder_type_id <> '".$id."' LIMIT 1";
    $h 		= mysqli_query($conn,$sql);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_duplicate',
        })</script>";
        exit();
    }

    $s = "UPDATE $tbl1 SET reminder_type_name ='".$reminder_type_name."', category_master_id ='".$category_master_id."', updated_at = UTC_TIMESTAMP() WHERE reminder_type_id = '".$id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    mysqli_query($conn, $s);

    // add log
    addLog($conn, $_SESSION['user_id'],'EDIT REMINDER TYPE', 'edit reminder type id: '.$id);

    $banyaknya = count(@$_POST['reminder_day']);
    for ($i=0; $i<$banyaknya; $i++) {
      if(@$_POST['reminder_day'][$i]) {
          $s_menu  = "INSERT INTO $tbl2 (reminder_type_id, reminder_day, group_receiver_id, reminder_via, client_id, created_at) VALUES ('".$id."', '".@$_POST['reminder_day'][$i]."', '".@$_POST['group_receiver_id'][$i]."', 'EMAIL', '".$_SESSION['client_id']."', UTC_TIMESTAMP())";
          mysqli_query($conn,$s_menu);
      }
    }

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'reminderType';
    });</script>";
    exit();
}

//this is to delete reminder day
if($param[1] == "delete") {

    $id	=	Encryption::decode($param[2]);
    $reminder_type_id = $param[3];
  
    echo '.';
    if($id == '')
    {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    //sukses
    //delete data
    $s = "DELETE FROM $tbl2 WHERE reminder_duration_id = '".$id."' AND client_id = '".$_SESSION['client_id']."'";
    mysqli_query($conn, $s);

    /* add log */
    addLog($conn, $_SESSION['user_id'],'DELETE REMINDER DATE', 'Delete reminder: '.$id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'reminderTypeDetail?$reminder_type_id'
    });</script>";
    exit();
  }