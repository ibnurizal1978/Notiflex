<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'product_category_name',
                1 => 'discount',
                2=> 'notes',
                3=> 'active_status',
            );

$godeg          = "FROM tbl_product_category";
$querycount 		= $conn->query("SELECT count(product_category_id) as jumlah $godeg WHERE client_id = '".$_SESSION['client_id']."'");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
  $godeg = "SELECT * $godeg WHERE client_id = '".$_SESSION['client_id']."' order by $order $dir LIMIT $limit OFFSET $start";
	$query = $conn->query($godeg);
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT * $godeg WHERE client_id = '".$_SESSION['client_id']."' AND product_category_name LIKE '%$search%' or notes LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(product_category_id) as jumlah $godeg WHERE client_id = '".$_SESSION['client_id']."' AND product_category_name LIKE '%$search%' or notes LIKE '%$search%'");
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
    $nestedData['product_category_id'] 	= $r['product_category_id'];
    $nestedData['product_category_name']= html_entity_decode($r['product_category_name'], ENT_QUOTES);
    $nestedData['discount']             = $r['discount'].'%';
    $nestedData['notes']                = html_entity_decode($r['notes'], ENT_QUOTES);
    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
    $nestedData['active_status'] 			  = $active_status;
    $nestedData['aksi'] 							  = "<a href='productCategoryDetail?".Encryption::encode($r['product_category_id'])."' class='btn-warning btn-sm'>view</a>";
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
