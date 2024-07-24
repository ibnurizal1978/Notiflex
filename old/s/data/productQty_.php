<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'warehouse_name',
                1=> 'pallet_name',
                2=> 'qty',
            );

$product_id = Encryption::decode($param[1]);
$godeg          = "FROM tbl_product_qty a INNER JOIN tbl_warehouse_master b USING (warehouse_master_id) INNER JOIN tbl_warehouse_pallet c USING (warehouse_pallet_id)";
$querycount 		= $conn->query("SELECT count(product_qty_id) as jumlah $godeg WHERE a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."' GROUP BY product_qty_id");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT a.product_id, a.warehouse_master_id, a.warehouse_pallet_id, b.name as warehouse_name, c.name as pallet_name, SUM(qty * CASE qty_action WHEN 'ADD' THEN 1 WHEN 'REMOVE' THEN -1 END) AS available_qty $godeg WHERE a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."' GROUP BY product_qty_id order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT a.product_id, a.warehouse_master_id, a.warehouse_pallet_id, b.name as warehouse_name, c.name as pallet_name, SUM(qty * CASE qty_action WHEN 'ADD' THEN 1 WHEN 'REMOVE' THEN -1 END) AS available_qty $godeg WHERE a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."' AND (b.name LIKE '%$search%' or c.name LIKE '%$search%' or qty LIKE '%$search%') order by $order $dir GROUP BY product_qty_id LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(product_qty_id) as jumlah $godeg WHERE a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."' AND (b.name LIKE '%$search%' or c.name LIKE '%$search%' or qty LIKE '%$search%') GROUP BY product_qty_id");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    //knowing assigned qty based on warehouse_master_id, warehouse_pallet_id and product_id
    $s2 = "SELECT sum(qty) as total_delivered FROM tbl_order_qty WHERE warehouse_master_id = '".$r['warehouse_master_id']."' AND warehouse_pallet_id = '".$r['warehouse_pallet_id']."' AND product_id = '".$r['product_id']."'";
    $h2 = mysqli_query($conn, $s2);
    $r2 = mysqli_fetch_assoc($h2);
    $available_qty = $r['available_qty'] - $r2['total_delivered'];

    $nestedData['no'] 				           = $no;
    $nestedData['warehouse_name']        = html_entity_decode($r['warehouse_name'], ENT_QUOTES);
    $nestedData['pallet_name']           = html_entity_decode($r['pallet_name'], ENT_QUOTES);
    $nestedData['qty'] 	                 = $available_qty.'<br/>'.$s2;
    $nestedData['aksi']                  = '';
    //$nestedData['aksi'] 							   = "<a href='productQtyDetail?".Encryption::encode($r['product_qty_id'])."' class='btn-warning btn-sm'>view</a>";
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
