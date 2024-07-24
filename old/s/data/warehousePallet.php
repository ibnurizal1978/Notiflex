<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'pallet_name',
                1=> 'warehouse_name',
                2=> 'notes',
                3=> 'active_status',
            );

$godeg          = "FROM tbl_warehouse_pallet a INNER JOIN tbl_warehouse_master b USING (warehouse_master_id)";
$querycount 		= $conn->query("SELECT count(warehouse_pallet_id) as jumlah $godeg WHERE a.client_id = '".$_SESSION['client_id']."'");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT warehouse_pallet_id, a.name as pallet_name, b.name as warehouse_name, a.active_status, a.notes $godeg WHERE a.client_id = '".$_SESSION['client_id']."' order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT warehouse_pallet_id, a.name as pallet_name, b.name as warehouse_name, a.active_status, a.notes $godeg WHERE a.client_id = '".$_SESSION['client_id']."' AND (a.name LIKE '%$search%' or b.name LIKE '%$search%' or a.notes LIKE '%$search%') order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(warehouse_pallet_id) as jumlah $godeg WHERE a.client_id = '".$_SESSION['client_id']."' AND a.name LIKE '%$search%' or b.name LIKE '%$search%' or a.notes LIKE '%$search%'");
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
    $nestedData['warehouse_pallet_id'] 	= $r['warehouse_pallet_id'];
    $nestedData['pallet_name'] 				  = html_entity_decode($r['pallet_name'], ENT_QUOTES);
    $nestedData['warehouse_name'] 		  = html_entity_decode($r['warehouse_name'], ENT_QUOTES);
    $nestedData['notes']                = $r['notes'];
    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
    $nestedData['active_status'] 			  = $active_status;
    $nestedData['aksi'] 							  = "<a href='warehousePalletDetail?".Encryption::encode($r['warehouse_pallet_id'])."' class='btn-warning btn-sm'>view</a>";
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
