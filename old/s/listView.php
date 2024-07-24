<?php
session_start();
require_once "../config.php";
require_once "inc/check_session.php";
$photo_name  = $param[1];
$tbl1    = 'tbl_list_upload';

$s = "SELECT photo_name FROM $tbl1 WHERE client_id = '".$_SESSION['client_id']."' AND photo_name = '".$photo_name."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
?>



