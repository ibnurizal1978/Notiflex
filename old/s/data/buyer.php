<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'business_name',
                1=> 'business_address',
                2=> 'credit_limit',
                3=> 'discount_rate',
                4=> 'active_status',
            );

$godeg          = "FROM tbl_client a INNER JOIN tbl_discount_rate b USING (discount_rate_id)";
$querycount 		= $conn->query("SELECT count(a.client_id) as jumlah $godeg WHERE owner_id = '".$_SESSION['client_id']."'");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT *, a.client_id as client_id $godeg WHERE owner_id = '".$_SESSION['client_id']."' order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT *, a.client_id as client_id $godeg WHERE owner_id = '".$_SESSION['client_id']."' AND business_name LIKE '%$search%' or business_address LIKE '%$search%' or business_phone LIKE '%$search%' or business_email LIKE '%$search%' or city LIKE '%$search%' or state LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(a.client_id) as jumlah $godeg WHERE owner_id = '".$_SESSION['client_id']."' AND business_name LIKE '%$search%' or business_address LIKE '%$search%' or business_phone LIKE '%$search%' or business_email LIKE '%$search%' or city LIKE '%$search%' or state LIKE '%$search%'");
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
    $nestedData['client_id'] 	      = $r['client_id'];
    $nestedData['business_name'] 	  = html_entity_decode($r['business_name'], ENT_QUOTES);
    $nestedData['business_address'] = html_entity_decode($r['business_address'], ENT_QUOTES).'<br/>'.html_entity_decode($r['city'], ENT_QUOTES).', '.html_entity_decode($r['state'], ENT_QUOTES).' '.html_entity_decode($r['zip_code'], ENT_QUOTES).'<br/><small><i class="mdi mdi-cellphone-basic text-info"></i> '.$r['business_phone'].'<br/><i class="mdi mdi-email text-warning"></i> '.$r['business_email'].'</small>';
    $nestedData['credit_limit']   = number_format($r['credit_limit'],0,",",".");
    $nestedData['discount_rate']    = $r['discount_rate'].'%';
    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
    $nestedData['active_status'] 			  = $active_status;
    $nestedData['aksi'] 							  = "<a href='buyerDetail?".Encryption::encode($r['client_id'])."' class='btn-warning btn-sm'>view</a> <a href='user?".Encryption::encode($r['client_id'])."' class='btn-info btn-sm'>user</a>";
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
