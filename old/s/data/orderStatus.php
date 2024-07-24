<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0=> 'business_name',
                1=> 'check_out_date',
                2=> 'order_code',
                3=> 'order_status',
                4=> 'payment_status',
            );

if($param[1] == 1)
{
  $order_status = 'PENDING';
}elseif($param[1] == 2){
  $order_status = 'ON PROCESS';
}elseif($param[1] == 3){
  $order_status = 'ON DELIVERY';
}elseif($param[1] == 4){
  $order_status = 'DELIVERED';
}else{
  $order_status = 'REJECTED';
}

$godeg          = "FROM tbl_order a INNER JOIN tbl_client b USING (client_id)";
$querycount 		= $conn->query("SELECT count(order_id) as jumlah $godeg WHERE a.owner_id = '".$_SESSION['client_id']."' AND order_status = '".$order_status."'");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT *, date_format(DATE_ADD(check_out_date, INTERVAL '".$_SESSION['selisih']."' hour), '%M %d, %Y at %H:%i') as check_out_date $godeg WHERE a.owner_id = '".$_SESSION['client_id']."' AND order_status = '".$order_status."' order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT *, date_format(DATE_ADD(check_out_date, INTERVAL '".$_SESSION['selisih']."' hour), '%M %d, %Y at %H:%i') as check_out_date $godeg WHERE a.owner_id = '".$_SESSION['client_id']."' AND order_status = '".$order_status."' AND order_code LIKE '%$search%' or a.updated_at LIKE '%$search%' or business_name LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(order_id) as jumlah $godeg WHERE a.owner_id = '".$_SESSION['client_id']."' AND order_status = '".$order_status."' AND order_code LIKE '%$search%' or a.updated_at LIKE '%$search%' or business_name LIKE '%$search%'");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    if($r['order_status'] == 'IN_CART')
    {
      $button = "<a href='orderStatusList?".Encryption::encode($r['order_code'])."' class='btn-warning btn-sm'>view</a> <a href='checkOut?".Encryption::encode($r['order_code'])."' class='btn-info btn-sm'>check out</a>";
    }else{
      $button = "<a href='checkOutDetail?".Encryption::encode($r['order_code'])."' class='btn-warning btn-sm'>view</a>";
    }

    if($r['order_status'] =='ON PROCESS' || $r['order_status'] =='ON DELIVERY')
    {
      $order_status = '<span class="badge badge-light">'.$r['order_status'].'</span>';
    }
    if ($r['order_status'] == 'DELIVERED') {
      $order_status = '<span class="badge badge-success">'.$r['order_status'].'</span>';
    }
    if ($r['order_status'] == 'REJECTED') {
      $order_status = '<span class="badge badge-danger">'.$r['order_status'].'</span>';
    }
    if ($r['order_status'] == 'PENDING') {
      $order_status = '<span class="badge badge-warning">'.$r['order_status'].'</span>';
    }

    //payment status
    if ($r['payment_status'] == 'PENDING') {
      $payment_status = '<span class="badge badge-warning">'.$r['payment_status'].'</span>';
    }

    if ($r['payment_status'] == 'ON VERIFICATION') {
      $payment_status = '<span class="badge badge-default">'.$r['payment_status'].'</span>';
    }

    if ($r['payment_status'] == 'PAID') {
      $payment_status = '<span class="badge badge-success">'.$r['payment_status'].'</span>';
    }

    if ($r['payment_status'] == 'REJECTED') {
      $payment_status = '<span class="badge badge-danger">'.$r['payment_status'].'</span>';
    }

    $nestedData['business_name'] 	 = $r['business_name'];
    $nestedData['check_out_date']  = $r['check_out_date'];
    $nestedData['order_code']      = $r['order_code'];
    $nestedData['order_status'] 	 = $order_status;
    $nestedData['payment_status']  = $payment_status;
    $nestedData['aksi'] 				   = $button;
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
