<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "inc/check_session.php";
$action   = $param[1];
$tbl1     = 'tbl_category_reminder';
$tbl2     = 'tbl_category_reminder_receiver';

if($action == "add") {
  $category_id	=	input_data(Encryption::decode($_POST['category_id']));
  $reminder_day	      =	input_data($_POST['reminder_day']);
  $reminder_method    = 'EMAIL';

  if($category_id == '' || $reminder_day == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //duplicate?
  $s = "SELECT category_id FROM $tbl1 WHERE category_id = '".$category_id."' AND reminder_day = '".$reminder_day."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  
  $s = "INSERT INTO $tbl1 SET category_id = '".$category_id."', reminder_day = '".$reminder_day."', reminder_method = '".$reminder_method."', client_id = '".$_SESSION['client_id']."', created_at = UTC_TIMESTAMP()";
  mysqli_query($conn, $s);
  $last_id = mysqli_insert_id($conn);

  $banyaknya = count(@$_POST['group_receiver_id']);
  for ($i=0; $i<$banyaknya; $i++) {
    if(@$_POST['group_receiver_id'][$i]) {
      //$s1 = "SELECT user_id FROM tbl_group_receiver_detail WHERE group_receiver_id = '".@$_POST['group_receiver_id'][$i]."'";
      //$h1 = mysqli_query($conn, $s1);
      //while($r1 = mysqli_fetch_assoc($h1))
      //{
        $sql_menu2  = "INSERT INTO $tbl2(category_reminder_id, group_receiver_id, client_id, created_at) VALUES ('".$last_id."', '".@$_POST['group_receiver_id'][$i]."','".$_SESSION['client_id']."', UTC_TIMESTAMP())";
        echo $sql_menu2.'<br/>';
        mysqli_query($conn,$sql_menu2);
      //}
    }
  }
  //sukses
  //this is for individual user receiver, now changed to group receiver so this no longer need
  /*$banyaknya = count(@$_POST['user_id']);
  for ($i=0; $i<$banyaknya; $i++) {
    if(@$_POST['user_id'][$i]) {
    $sql_menu2  = "INSERT INTO $tbl2(category_reminder_id, user_id, client_id, created_at) VALUES ('".$last_id."', '".@$_POST['user_id'][$i]."','".$_SESSION['client_id']."', UTC_TIMESTAMP())";
    mysqli_query($conn,$sql_menu2);
    }
  }*/

  /* add log */
  addLog($conn, $_SESSION['user_id'],'ADD REMINDER DAY', 'Add new reminder day: '.$category_id);
  echo "<script>";
  echo "alert('$data_success'); window.location=\"categoryDetail?".@$_POST['param1']."?".@$_POST['param2']."?".@$_POST['param3']."\"";
  echo "</script>";

}


if($action == "edit") {
    $category_reminder_id	=	Encryption::decode($_POST['category_reminder_id']);
    $category_detail_id	    =	Encryption::decode($_POST['category_detail_id']);
    $reminder_day	        =	input_data($_POST['reminder_day']);
    $reminder_method	    =	input_data($_POST['reminder_method']);

  if($category_reminder_id == '' || $reminder_day == '' || $reminder_method == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //check apakah ada nama group yang sama
  $s = "SELECT reminder_day FROM $tbl WHERE client_id = '".$_SESSION['client_id']."' AND reminder_day = '".$reminder_day."' AND category_reminder_id <> '".$category_reminder_id."' AND category_detail_id = '".$category_detail_id."' LIMIT 1";
  $h = mysqli_query($conn, $s);
  if(mysqli_num_rows($h) > 0)
  {
    echo "<script>";
    echo "alert('$data_duplicate'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }


    $s = "UPDATE $tbl SET reminder_day = '".$reminder_day."', reminder_method = '".$reminder_method."', updated_at = UTC_TIMESTAMP() WHERE category_reminder_id = '".$category_reminder_id."' LIMIT 1";
    mysqli_query($conn, $s);


  /* add log */
  addLog($conn, $_SESSION['user_id'],'EDIT CATEGORY REMINDER', 'edit category reimnder for id: '.$category_reminder_id);

  echo "<script>";
  //echo "alert('$data_success'); window.location=\"categoryDetail?".$category_id."\"";
  echo "alert('$data_success'); window.location=\"categoryDetail?".@$_POST['param1']."?".@$_POST['param2']."?".@$_POST['param3']."\"";
  echo "</script>";

}

if($action == "delete") {

  $category_reminder_id	=	Encryption::decode($param[2]);
  $category_id	=	$param[3];

  if($category_reminder_id == '')
  {
    echo "<script>";
    echo "alert('$data_empty'); window.location.href=history.back()";
    echo "</script>";
    exit();
  }

  //sukses
  $s = "DELETE FROM $tbl1 WHERE category_reminder_id = '".$category_reminder_id."' AND client_id = '".$_SESSION['client_id']."'";
  mysqli_query($conn, $s);

  $s2 = "DELETE FROM $tbl2 WHERE category_reminder_id = '".$category_reminder_id."' AND client_id = '".$_SESSION['client_id']."'";
  mysqli_query($conn, $s2);

  /* add log */
  addLog($conn, $_SESSION['user_id'],'DELETING REMINDER DATE', 'Delete reminder: '.$category_reminder_id);
  echo "<script>";
  echo "alert('$data_deleted'); window.location=\"categoryDetail?".$param[4]."?".$param[5]."?".$param[6]."\"";
  echo "</script>";

}
?>
