<?php
session_start();
require_once "../config.php";
require_once "inc/check_session.php";
$action  = $param[1];
$tbl     = 'tbl_group_receiver';
$tbl2    = 'tbl_group_receiver_detail';

if($action == "add") {
  $group_name	        =	input_data($_POST['group_name']);
  $group_description	=	input_data($_POST['group_description']);

  if($group_name == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama group yang sama
  $s = "SELECT group_name FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND group_name = '".$group_name."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //sukses
  $s = "INSERT INTO $tbl SET group_name = '".$group_name."', group_description = '".$group_description."', client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s);
  $last_id = mysqli_insert_id($conn);

  $banyaknya = count(@$_POST['user_id']);
  for ($i=0; $i<$banyaknya; $i++) {
    if(@$_POST['user_id'][$i]) {
    $sql_menu2  = "INSERT INTO $tbl2 (group_receiver_id,user_id,client_id, created_at) VALUES ('".$last_id."', '".@$_POST['user_id'][$i]."','".$_SESSION['client_id']."', UTC_TIMESTAMP())";
    mysqli_query($conn,$sql_menu2);
    //echo $sql_menu2.'<br/>';
    }
  }

  /* add log */
  addLog($conn, $_SESSION['user_id'],'ADD GROUP RECEIVER', 'Add new group receiver name: '.$group_name);
  echo "<script>";
  echo "alert('$data_success'); window.location=\"groupReceiver\"";
  echo "</script>";

}


if($action == "edit") {
  $id          = Encryption::decode($_POST['id']);
  $group_name	      =	input_data($_POST['group_name']);
  $group_description  =	input_data($_POST['group_description']);
  $active_status	  =	input_data($_POST['active_status']);

  if($group_name == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

    //check apakah ada nama group yang sama
    $s = "SELECT group_name FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND group_name = '".$group_name."' AND group_receiver_id <> '".$id."' LIMIT 1";
    $h = mysqli_query($conn, $s);
    if(mysqli_num_rows($h) > 0)
    {
      echo "<script>";
      echo "alert('$data_duplicate'); window.location.href=history.back()";
      echo "</script>";
      exit();
    }


    $s = "UPDATE $tbl SET group_name = '".$group_name."', group_description = '".$group_description."', active_status = '".$active_status."', updated_at = UTC_TIMESTAMP() WHERE group_receiver_id = '".$id."' LIMIT 1";
  mysqli_query($conn, $s);

  mysqli_query($conn, "DELETE FROM tbl_group_receiver_detail WHERE group_receiver_id = '".$id."'");

  $banyaknya = count(@$_POST['user_id']);
  for ($i=0; $i<$banyaknya; $i++) {
    if(@$_POST['user_id'][$i]) {
        $sql_menu2  = "INSERT INTO $tbl2 (group_receiver_id,user_id,client_id, created_at) VALUES ('".$id."', '".@$_POST['user_id'][$i]."','".$_SESSION['client_id']."', UTC_TIMESTAMP())";
        mysqli_query($conn,$sql_menu2);
      //echo $sql_menu2.'<br/>';
    }
  }

  /* add log */
  addLog($conn, $_SESSION['user_id'],'EDIT GROUP RECEIVER', 'edit group receiver for id: '.$id);

  echo "<script>";
  echo "alert('$data_success'); window.location=\"groupReceiver\"";
  echo "</script>";

}
?>
