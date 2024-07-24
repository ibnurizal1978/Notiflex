<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'order_code',
                1 => 'warehouse_status',
            );

$godeg          = "FROM tbl_order_qty";
$querycount 		= $conn->query("SELECT count(order_qty_id) as jumlah $godeg WHERE client_id = '".$_SESSION['client_id']."'");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT * $godeg WHERE client_id = '".$_SESSION['client_id']."' order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT * $godeg WHERE client_id = '".$_SESSION['client_id']."' AND order_code LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(order_qty_id) as jumlah $godeg WHERE client_id = '".$_SESSION['client_id']."' AND order_code LIKE '%$search%'");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $nestedData['no'] 								  = $no;
    $nestedData['order_code'] 	        = $r['order_code'];
    $nestedData['name'] 				        = html_entity_decode($r['name'], ENT_QUOTES);
    $nestedData['address']              = html_entity_decode($r['address'], ENT_QUOTES);
    $nestedData['notes']                = html_entity_decode($r['notes'], ENT_QUOTES);
    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
    $nestedData['active_status'] 			  = $active_status;
    $nestedData['aksi'] 							  = "<a href='warehouseMasterDetail?".Encryption::encode($r['warehouse_master_id'])."' class='btn-warning btn-sm'>view</a>";
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
