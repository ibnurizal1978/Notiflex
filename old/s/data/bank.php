<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'bank_id',
                1 =>'bank_name',
                2 => 'branch',
                3 => 'beneficiary_name',
                4 => 'account_number',
                5 => 'active_status'
            );

$godeg          = 'FROM tbl_bank';
$querycount 		= $conn->query("SELECT count(bank_id) as jumlah $godeg WHERE client_id = '".$_SESSION['client_id']."'");
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
  $query = $conn->query("SELECT * $godeg WHERE client_id = '".$_SESSION['client_id']."' AND bank_name LIKE '%$search%' or branch LIKE '%$search%' or beneficiary_name LIKE '%$search%' or account_number LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(bank_id) as jumlah $godeg WHERE client_id = '".$_SESSION['client_id']."' AND bank_name LIKE '%$search%' or branch LIKE '%$search%' or beneficiary_name LIKE '%$search%' or account_number LIKE '%$search%'");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $nestedData['no'] 						  = $no;
    $nestedData['bank_id'] 	        = $r['bank_id'];
    $nestedData['bank_name'] 				= $r['bank_name'];
    $nestedData['branch']           = $r['branch'];
    $nestedData['beneficiary_name'] = html_entity_decode($r['beneficiary_name'], ENT_QUOTES);
    $nestedData['account_number']   = $r['account_number'];
    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
    $nestedData['active_status']    = $active_status;
    $nestedData['aksi'] 					  = "<a href='bankDetail?".Encryption::encode($r['bank_id'])."' class='btn-warning btn-sm'>view</a>";
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
