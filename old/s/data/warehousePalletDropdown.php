<?php
require "../../config.php";
//require "../inc/check_session.php";
$warehouse_master_id = $_POST['warehouse_master_id'];
$sql = "SELECT warehouse_pallet_id, name FROM tbl_warehouse_pallet WHERE warehouse_master_id ='".$warehouse_master_id."' ORDER BY name";
echo $sql2;
$h = mysqli_query($conn, $sql);

$html = "<option value=''> Select </option>";
while($data = mysqli_fetch_assoc($h)){
  $html .= "<option value='".$data['warehouse_pallet_id']."'>".$data['name']."</option>";
}
$callback = array('data_kota'=>$html);
echo json_encode($callback);
?>