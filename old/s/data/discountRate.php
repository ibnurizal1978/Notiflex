<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'discount_rate_id',
                1 =>'group_name',
                2=> 'discount_rate',
            );

$godeg          = 'FROM tbl_discount_rate';
$querycount 		= $conn->query("SELECT count(discount_rate_id) as jumlah $godeg WHERE client_id = '".$_SESSION['client_id']."'");
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
  $query = $conn->query("SELECT * $godeg WHERE client_id = '".$_SESSION['client_id']."' AND group_name LIKE '%$search%' or discount_rate LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(discount_rate_id) as jumlah $godeg WHERE client_id = '".$_SESSION['client_id']."' AND group_name LIKE '%$search%' or discount_rate LIKE '%$search%'");
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
    $nestedData['discount_rate_id'] 	= $r['discount_rate_id'];
    $nestedData['group_name'] 				= html_entity_decode($r['group_name'], ENT_QUOTES);
    $nestedData['discount_rate']      = $r['discount_rate'].'%';
    $nestedData['aksi'] 							= "<a href='discountRateDetail?".Encryption::encode($r['discount_rate_id'])."' class='btn-warning btn-sm'>view</a>";
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
