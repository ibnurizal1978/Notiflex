<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'product_name',
                1=> 'product_qty',
                2=> 'product_price',
                3=> 'product_minimum_order',
                4=> 'active_status',
            );

$godeg          = "FROM tbl_product a INNER JOIN tbl_product_category b USING (product_category_id)";
$querycount 		= $conn->query("SELECT count(product_id) as jumlah $godeg WHERE a.client_id = '".$_SESSION['client_id']."'");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT *, a.active_status $godeg WHERE a.client_id = '".$_SESSION['client_id']."' order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT *, a.active_status $godeg WHERE a.client_id = '".$_SESSION['client_id']."' AND product_name LIKE '%$search%' or product_category_name LIKE '%$search%' or product_unit LIKE '%$search%' or product_qty LIKE '%$search%' or product_price LIKE '%$search%' or product_unit LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(product_id) as jumlah $godeg WHERE a.client_id = '".$_SESSION['client_id']."' AND product_name LIKE '%$search%' or product_category_name LIKE '%$search%' or product_unit LIKE '%$search%' or product_qty LIKE '%$search%' or product_price LIKE '%$search%' or product_unit LIKE '%$search%'");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $s2 = "SELECT SUM(qty * CASE qty_action WHEN 'ADD' THEN 1 WHEN 'REMOVE' OR 'RESERVED' THEN -1 END) AS total FROM tbl_product_qty WHERE client_id = '".$_SESSION['client_id']."' AND product_id = '".$r['product_id']."' LIMIT 1";
    $h2 = $conn->query($s2);
    $r2 = $h2->fetch_array();
    if($r2['total'] == 0) { $total = 0; }else{ $total = $r2['total']; }

    $nestedData['no'] 				            = $no;
    $nestedData['product_id'] 	          = $r['product_id'];
    $nestedData['product_name']           = html_entity_decode($r['product_name'], ENT_QUOTES).'<br/><small>under category: <b class="text-info">'.$r['product_category_name'].'</b></small>';
    $nestedData['product_qty'] 	          = $total;
    $nestedData['product_price'] 	        = $_SESSION['currency'].' '.number_format($r['product_price'],0,",",".");
    $nestedData['product_minimum_order'] 	= $r['product_minimum_order'].' '.$r['product_unit'];
    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
    $nestedData['active_status'] 			  = $active_status;
    $nestedData['aksi'] 							  = "<a href='productDetail?".Encryption::encode($r['product_id'])."' class='btn-warning btn-sm'>view</a> <a href='productQty?".Encryption::encode($r['product_id'])."' class='btn-info btn-sm'>qty</a> <a href='productQtyStock?".Encryption::encode($r['product_id'])."' class='btn-sm btn-primary'>stock card</a>";
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
