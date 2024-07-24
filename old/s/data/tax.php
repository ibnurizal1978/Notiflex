<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'tax_name',
                1 =>'tax_rate',
                2=> 'notes',
            );

$godeg          = 'FROM tbl_tax';
$querycount 		= $conn->query("SELECT count(tax_id) as jumlah $godeg WHERE client_id = '".$_SESSION['client_id']."'");
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
  $query = $conn->query("SELECT * $godeg WHERE client_id = '".$_SESSION['client_id']."' AND tax_name LIKE '%$search%' or tax_rate LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(tax_id) as jumlah $godeg WHERE client_id = '".$_SESSION['client_id']."' AND tax_name LIKE '%$search%' or tax_rate LIKE '%$search%'");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $nestedData['no'] 			= $no;
    $nestedData['tax_id'] 	= $r['tax_id'];
    $nestedData['tax_name'] = html_entity_decode($r['tax_name'], ENT_QUOTES);
    $nestedData['tax_rate'] = $r['tax_rate'];
    $nestedData['aksi'] 		= "<a href='taxDetail?".Encryption::encode($r['tax_id'])."' class='btn-warning btn-sm'>view</a>";
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
