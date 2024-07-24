<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0=> 'order_code',
                1=> 'check_out_date',
                2=> 'remaining',
                3=> 'order_status',
                4=> 'payment_status',
            );

if($param[1] == 1)
{
  $payment_status = 'PENDING';
}elseif($param[1] == 2){
  $payment_status = 'ON VERIFICATION';
}elseif($param[1] == 3){
  $payment_status = 'REJECTED';
}else{
  $payment_status = 'PAID';
}

$godeg          = "FROM tbl_order a INNER JOIN tbl_client b USING (client_id)";
$querycount 		= $conn->query("SELECT count(order_id) as jumlah $godeg WHERE order_status NOT IN ('IN CART', 'REJECTED') AND payment_status = '".$payment_status."' AND a.owner_id = '".$_SESSION['client_id']."'");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT business_name, order_code, order_status, payment_status, date_format(DATE_ADD(check_out_date, INTERVAL '".$_SESSION['selisih']."' hour), '%M %d, %Y at %H:%i') as check_out_date, term_of_payment-datediff(now(), check_out_date) as remaining, date_format(DATE_ADD(check_out_date, INTERVAL term_of_payment DAY), '%M %d, %Y') as due_date $godeg WHERE order_status NOT IN ('IN CART', 'REJECTED') AND payment_status = '".$payment_status."' AND a.owner_id = '".$_SESSION['client_id']."' order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT business_name, order_code, order_status, payment_status, date_format(DATE_ADD(check_out_date, INTERVAL '".$_SESSION['selisih']."' hour), '%M %d, %Y at %H:%i') as check_out_date, term_of_payment-datediff(now(), check_out_date) as remaining, date_format(DATE_ADD(check_out_date, INTERVAL term_of_payment DAY), '%M %d, %Y') as due_date $godeg WHERE order_status NOT IN ('IN CART', 'REJECTED') AND payment_status = '".$payment_status."' AND a.owner_id = '".$_SESSION['client_id']."' AND (order_code LIKE '%$search%' or check_out_date LIKE '%$search%' or order_status LIKE '%$search%' or payment_status LIKE '%$search%' or term_of_payment-datediff(now(), check_out_date) LIKE '%$search%') order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(order_id) as jumlah $godeg WHERE order_status NOT IN ('IN CART', 'REJECTED') AND payment_status = '".$payment_status."' AND a.owner_id = '".$_SESSION['client_id']."' AND (order_code LIKE '%$search%' or check_out_date LIKE '%$search%' or order_status LIKE '%$search%' or payment_status LIKE '%$search%' or term_of_payment-datediff(now(), check_out_date) LIKE '%$search%')");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {

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


    $aksi = "<a href='paymentDetail?".Encryption::encode($r['order_code'])."' class='btn-warning btn-sm'>view</a>";

    $nestedData['no'] 				     = $no;
    $nestedData['order_code']      = $r['order_code'].'<br/>By: '.$r['business_name'];
    $nestedData['check_out_date']  = $r['check_out_date'];
    $nestedData['remaining']       = $r['remaining'].' day(s) left<br/><b><small>Due date: '.$r['due_date'].'</b></small>';
    $nestedData['order_status'] 	 = $order_status;
    $nestedData['payment_status']  = $payment_status;
    $nestedData['aksi']            = $aksi;
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
