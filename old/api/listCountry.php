<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
include "../config.php";
$tbl    = 'tbl_country';

$result = array();
$s = "SELECT country_id, country_name FROM $tbl ORDER BY country_name";
$h = mysqli_query($conn, $s);
while($r = mysqli_fetch_assoc($h))
{
  $result1['country_id']    = $r['country_id'];
  $result1['country_name']  = $r['country_name'];
  array_push($result,$result1);
}

http_response_code(200);
echo json_encode(['message' => 'OK', 'data' => $result],JSON_PRETTY_PRINT);
exit();
?>