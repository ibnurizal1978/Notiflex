<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "inc/check_session.php";
$action  = $param[1];
$tbl     = 'tbl_category_receiver';

if($action == "add1") {
  $category_receiver_name	=	input_data($_POST['category_receiver_name']);

  if($category_receiver_name == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //duplicate?
  $s = "SELECT category_receiver_name FROM tbl_category_receiver WHERE category_receiver_name = '".$category_receiver_name."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //sukses
  $s = "INSERT INTO $tbl SET category_receiver_name = '".$category_receiver_name."', type = 'EMAIL', client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s);
  $last_id = mysqli_insert_id($conn);

  /*$banyaknya = count(@$_POST['category_detail_id']);
  for ($i=0; $i<$banyaknya; $i++) {
    if(@$_POST['category_detail_id'][$i]) {
    $sql_menu2  = "INSERT INTO tbl_category_receiver_detail(category_detail_id,category_receiver_id,client_id, created_at) VALUES ('".@$_POST['category_detail_id'][$i]."','".$last_id."','".$_SESSION['client_id']."', UTC_TIMESTAMP())";
    mysqli_query($conn,$sql_menu2);
    //echo $sql_menu2.'<br/>';
    }
  }*/

  /* add log */
  addLog($conn, $_SESSION['user_id'],'ADD RECEIVER EMAIL', 'Add new receiver email: '.$category_receiver_name);
  
  echo "<script>";
  echo "alert('$data_success'); window.location=\"categoryReceiver\"";
  echo "</script>";

}

if($action == "add2") {
    $category_receiver_name	=	input_data($_POST['category_receiver_name']);
   
    if($category_receiver_name == '')
    {
      echo "<script>";
      echo "alert('$data_empty'); window.location.href=history.back()";
      echo "</script>";
      exit();
    }
  
    //sukses
    $s = "INSERT INTO $tbl SET category_receiver_name = '".$category_receiver_name."', type = 'SMS', client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
    mysqli_query($conn, $s);
    $last_id = mysqli_insert_id($conn);

    $banyaknya = count(@$_POST['category_detail_id']);
    for ($i=0; $i<$banyaknya; $i++) {
      if(@$_POST['category_detail_id'][$i]) {
      $sql_menu2  = "INSERT INTO tbl_category_receiver_detail(category_detail_id,category_receiver_id,client_id, created_at) VALUES ('".@$_POST['category_detail_id'][$i]."','".$last_id."','".$_SESSION['client_id']."', UTC_TIMESTAMP())";
      mysqli_query($conn,$sql_menu2);
      //echo $sql_menu2.'<br/>';
      }
    }

    /* add log */
    addLog($conn, $_SESSION['user_id'],'ADD RECEIVER SMS', 'Add new receiver sms: '.$category_receiver_name);
    echo "<script>";
    echo "alert('$data_success'); window.location=\"categoryDetail?".$_POST['category_detail_id']."\"";
    echo "</script>";
  
  }


if($action == "edit") {
    $category_receiver_id	=	Encryption::decode($_POST['category_receiver_id']);
    $category_receiver_name	=	input_data($_POST['category_receiver_name']);
    $active_status	        =	input_data($_POST['active_status']);

  if($category_receiver_name == '' || $active_status == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama group yang sama
  $s = "SELECT category_receiver_name, type FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND category_receiver_name = '".$category_receiver_name."' AND category_receiver_id <> '".$category_receiver_id."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check if type = sms but user input non numeric?
  $s = "SELECT type FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND category_receiver_id = '".$category_receiver_id."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  $r = mysqli_fetch_assoc($h);
  //if($r['type'] == 'SMS')
  //{
    if(!is_numeric($category_receiver_name) && $r['type'] == 'SMS')
    {
        echo "<script>";
        echo "alert('This receiver is SMS. You should type number.'); window.location.href=history.back()";
        echo "</script>";
        exit();
    } 
  //} 


    $s = "UPDATE $tbl SET category_receiver_name = '".$category_receiver_name."', active_status = '".$active_status."', updated_at = UTC_TIMESTAMP() WHERE category_receiver_id = '".$category_receiver_id."' LIMIT 1";
    mysqli_query($conn, $s);

    $s_delete = "DELETE FROM tbl_category_receiver_detail WHERE category_receiver_id = '".$category_receiver_id."'";
    mysqli_query($conn, $s_delete);

    /*$banyaknya = count(@$_POST['category_detail_id']);
    for ($i=0; $i<$banyaknya; $i++) {
      if(@$_POST['category_detail_id'][$i]) {
      $sql_menu2  = "INSERT INTO tbl_category_receiver_detail(category_detail_id,category_receiver_id,client_id, created_at) VALUES ('".@$_POST['category_detail_id'][$i]."','".$category_receiver_id."','".$_SESSION['client_id']."', UTC_TIMESTAMP())";
      mysqli_query($conn,$sql_menu2);
      //echo $sql_menu2.'<br/>';
      }
    }*/

  /* add log */
  addLog($conn, $_SESSION['user_id'],'EDIT CATEGORY RECEIVER', 'edit category receiver for id: '.$category_receiver_id);

  echo "<script>";
  echo "alert('$data_success'); window.location=\"categoryReceiver\"";
  echo "</script>";

}

if($action == "delete") {
  $category_receiver_id	=	Encryption::decode($param[2]);
  $category_detail_id	=	$param[3];

  if($category_receiver_id == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //sukses
  $s = "DELETE FROM $tbl WHERE category_receiver_id = '".$category_receiver_id."' LIMIT 1";
  mysqli_query($conn, $s);

  /* add log */
  addLog($conn, $_SESSION['user_id'],'DELETING RECEIVER', 'Delete receiver : '.$category_receiver_id);
  echo "<script>";
  echo "alert('$data_deleted'); window.location=\"categoryDetail?".$category_detail_id."\"";
  echo "</script>";

}
?>
