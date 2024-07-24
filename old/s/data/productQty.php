<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'warehouse_name',
                2=> 'qty',
            );

$product_id = Encryption::decode($param[1]);
$godeg          = "FROM tbl_product_qty a INNER JOIN tbl_warehouse_master b USING (warehouse_master_id)";
$querycount 		= $conn->query("SELECT count(product_qty_id) as jumlah $godeg WHERE is_temp = 0 AND a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."' GROUP BY warehouse_master_id");
$datacount 			= $querycount->fetch_array();
if(isset($datacount['jumlah'])) { $totalData = $datacount['jumlah']; }else{ $totalData = 0; }
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT a.product_qty_id, a.product_id, a.warehouse_master_id, b.name as warehouse_name, SUM(qty * CASE qty_action WHEN 'ADD' THEN 1 WHEN 'REMOVE' OR 'RESERVED' THEN -1 END) AS available_qty $godeg WHERE is_temp = 0 AND a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."' GROUP BY warehouse_master_id order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT a.product_qty_id, a.product_id, a.warehouse_master_id, b.name as warehouse_name, SUM(qty * CASE qty_action WHEN 'ADD' THEN 1 WHEN 'REMOVE' OR 'RESERVED' THEN -1 END) AS available_qty $godeg WHERE is_temp = 0 AND a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."' AND (name LIKE '%$search%' or qty LIKE '%$search%') GROUP BY warehouse_master_id order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(product_qty_id) as jumlah $godeg WHERE is_temp = 0 AND a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."' AND (name LIKE '%$search%' or qty LIKE '%$search%')");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $nestedData['no'] 				           = $no;
    $nestedData['warehouse_name']        = html_entity_decode($r['warehouse_name'], ENT_QUOTES);
    $nestedData['qty'] 	                 = $r['available_qty'];
    $nestedData['aksi']                  = '';
    //$nestedData['aksi'] 							   = "<a href='productQtyStock?".Encryption::encode($r['product_qty_id'])."' class='btn-warning btn-sm'>card</a>";
    $data[] = $nestedData;
    $no++;
  }
}

$json_data = array(
            "draw"            => intval($_POST['draw']),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
            );

echo json_encode($json_data);
?>
