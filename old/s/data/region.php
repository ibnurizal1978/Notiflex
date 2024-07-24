<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'region_id',
                1 =>'region_name',
                2=> 'discount_rate',
                3=> 'region_description',
            );

$querycount 		= $conn->query("SELECT count(region_id) as jumlah FROM tbl_region WHERE client_id = '".$_SESSION['client_id']."'");
$datacount 			= $querycount->fetch_array();


$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT * FROM tbl_region WHERE client_id = '".$_SESSION['client_id']."' order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT * FROM tbl_region WHERE client_id = '".$_SESSION['client_id']."' AND region_name LIKE '%$search%' or region_description LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(region_id) as jumlah FROM tbl_region WHERE client_id = '".$_SESSION['client_id']."' AND region_name LIKE '%$search%' or region_description LIKE '%$search%'");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $nestedData['no'] 								= $no;
    $nestedData['region_id'] 					= $r['region_id'];
    $nestedData['region_name'] 				= html_entity_decode($r['region_name'], ENT_QUOTES);
    $nestedData['region_description'] = html_entity_decode($r['region_description'], ENT_QUOTES);
    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
		$nestedData['active_status'] 			= $active_status;
    $nestedData['aksi'] 							= "<a href='regionDetail?".Encryption::encode($r['region_id'])."' class='btn-warning btn-sm'>view</a>";
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
