<?php
session_start();
require_once "../../config.php";

//detail data
$id             = input_data($_GET['id']);
$s = "SELECT category_duration, category_duration_cycle  FROM tbl_category WHERE category_id = '".$id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
echo "<b>".$r['category_duration']." ".$r['category_duration_cycle']."(s)</b>";
?>
