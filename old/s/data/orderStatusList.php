<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0=> 'order_detail_id',
                1=> 'product_name',
                2=> 'qty',
                3=> 'price',
                4=> 'discount',
                5=> 'sub_total_after_tax',
            );
$order_code     = Encryption::decode($param[1]);
$godeg          = "FROM tbl_order_detail a INNER JOIN tbl_order b USING (order_code)";
$querycount 		= $conn->query("SELECT count(order_detail_id) as jumlah $godeg WHERE order_code = '".$order_code."' AND a.client_id = '".$_SESSION['client_id']."'");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT * $godeg WHERE order_code = '".$order_code."' AND a.client_id = '".$_SESSION['client_id']."' order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  //$s = "SELECT * $godeg WHERE order_code = '".$order_code."' AND a.client_id = '".$_SESSION['client_id']."' AND order_code LIKE '%$search%' or product_name LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query("SELECT * $godeg WHERE order_code = '".$order_code."' AND a.client_id = '".$_SESSION['client_id']."' AND order_code LIKE '%$search%' or product_name LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(order_detail_id) as jumlah $godeg WHERE order_code = '".$order_code."' AND a.client_id = '".$_SESSION['client_id']."' AND order_code LIKE '%$search%' or product_name LIKE '%$search%'");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {

    $nestedData['no'] 				              = $no;
    $nestedData['product_name'] 	          = $r['product_name'];
    $nestedData['qty'] 	                    = number_format($r['qty'],0,",",".");
    $nestedData['price']                    = $_SESSION['currency'].' '.number_format($r['price'],2,",",".");
    $nestedData['discount']                 = $_SESSION['currency'].' '.number_format($r['discount'],2,",",".");
    $nestedData['sub_total_after_tax'] = $_SESSION['currency'].' '.number_format($r['sub_total_after_tax'],2,",",".");
    $nestedData['aksi'] 				            = "<a href='orderStatusListDetail?".Encryption::encode($r['order_detail_id'])."' class='btn-warning btn-sm'>edit</a> <a href='orderStatusListAction?delete?".Encryption::encode($r['order_detail_id'])."' class='btn-danger btn-sm'>delete</a>";
    //$nestedData['aksi'] 				            = "<a data-toggle='modal' data-target='#modal-add".Encryption::encode($r['order_detail_id'])."' href='#' class='btn-warning btn-sm'>edit</a> <a onclick='return confirm(\"Are you sure you want to delete this item?\");' href='orderStatusListAction?delete?".Encryption::encode($r['order_detail_id'])."' class='btn-danger btn-sm'>delete</a>";
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
