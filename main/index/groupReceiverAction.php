<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../../checkSession.php";
require_once "../../components.php";
//require_once "access.php";
$tbl1 = 'tbl_group_receiver';
$tbl2 = 'tbl_group_receiver_detail';

// this is if action = add
if($param[1]=='add')
{
  
    $group_name	        =	input_data($_POST['group_name']);
    $group_description	=	input_data($_POST['group_description']);

    if($group_name == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    // check if data is exist? reject it
    $s = "SELECT group_name FROM $tbl1 WHERE group_name = '".$group_name."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    $h = mysqli_query($conn,$s);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
        icon: 'error',
        text: '$notif_data_duplicate',
        })</script>";
        exit();
    }

    //user didn't choose member? reject it
    if(@$_POST['user_id'] == 0)
    {
      echo "<script>Swal.fire({
        icon: 'error',
        text: 'You must select at least one member to this group',
      })</script>";
      exit();
    }

    //success, insert data
    $s 	= "INSERT INTO $tbl1 SET group_name = '".$group_name."', group_description = '".$group_description."', created_at = UTC_TIMESTAMP(), client_id = '".$_SESSION['client_id']."'";
    mysqli_query($conn,$s);
    $last_id = mysqli_insert_id($conn);

    $banyaknya = count(@$_POST['user_id']);
    for ($i=0; $i<$banyaknya; $i++) {
      if(@$_POST['user_id'][$i]) {
      $sql_menu2  = "INSERT INTO $tbl2 (group_receiver_id,user_id,client_id, created_at) VALUES ('".$last_id."', '".@$_POST['user_id'][$i]."','".$_SESSION['client_id']."', UTC_TIMESTAMP())";
      mysqli_query($conn,$sql_menu2);
      }
    }

    // add log
    addLog($conn, $_SESSION['user_id'],'ADD GROUP RECEIVER', 'add id: '.$last_id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'groupReceiver';
    });</script>";
    exit();
}



// this is if action = edit
if($param[1]=='edit')
{
  
    $id		            =	input_data(Encryption::decode($_POST['id']));
    $group_name	        =	input_data($_POST['group_name']);
    $group_description	=	input_data($_POST['group_description']);

    if($group_name == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    //is there a duplicate name?
    $sql 	= "SELECT group_name FROM $tbl1 WHERE client_id = '".$_SESSION['client_id']."' AND group_name = '".$group_name."' AND group_receiver_id <> '".$id."' LIMIT 1";
    $h 		= mysqli_query($conn,$sql);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_duplicate',
        })</script>";
        exit();
    }

    //user didn't choose member? reject it
    if(@$_POST['user_id'] == 0)
    {
        echo "<script>Swal.fire({
        icon: 'error',
        text: 'You must select at least one member to this group',
        })</script>";
        exit();
    }

    //success
    $s = "UPDATE $tbl1 SET group_name ='".$group_name."', group_description ='".$group_description."', updated_at = UTC_TIMESTAMP() WHERE group_receiver_id = '".$id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    mysqli_query($conn, $s);

    mysqli_query($conn, "DELETE FROM tbl_group_receiver_detail WHERE group_receiver_id = '".$id."'");

    $banyaknya = count(@$_POST['user_id']);
    for ($i=0; $i<$banyaknya; $i++) {
      if(@$_POST['user_id'][$i]) {
          $sql_menu2  = "INSERT INTO $tbl2 (group_receiver_id,user_id,client_id, created_at) VALUES ('".$id."', '".@$_POST['user_id'][$i]."','".$_SESSION['client_id']."', UTC_TIMESTAMP())";
          mysqli_query($conn,$sql_menu2);
      }
    }

    // add log
    addLog($conn, $_SESSION['user_id'],'EDIT GROUP RECEIVER', 'edit id: '.$id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'groupReceiver';
    });</script>";
    exit();
}