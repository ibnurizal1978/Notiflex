<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../../config.php";

$columns = array(
                0 => 'product_name',
                1 => 'product_material',
                2 => 'warehouse_name',
                3 => 'active_status',
                4 => 'active_status',
            );

/*condition for query */
/* this is to check, if traffic came from search box? if yes then conditional below is active */
if (@$_GET['s'] == 'Y') {
    @$txt_search   = input_data(trim($_GET['txt_search']));
    $s              = 'Y';
}

$select         = "SELECT product_id, product_name, product_material, warehouse_name, active_status";
$godeg          = "FROM tbl_product a INNER JOIN tbl_warehouse b USING (warehouse_id) WHERE a.client_id = '".$_SESSION['client_id']."'";
$querycount 		= $conn->query("SELECT count(a.client_id) as jumlah $godeg");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if($_GET['txt_search']=='')
{
	$query = $conn->query("$select $godeg order by $order $dir LIMIT $limit OFFSET $start");
}else {
    $search = input_data(trim($_GET['txt_search']));
    $sql = "$select $godeg AND (product_name LIKE '%$search%' OR product_material LIKE '%$search%' OR warehouse_name LIKE '%$search%') order by $order $dir LIMIT $limit OFFSET $start";
    $query = $conn->query($sql);

    $querycount = $conn->query("SELECT count(a.product_id) as jumlah $godeg AND (product_name LIKE '%$search%' OR product_material LIKE '%$search%' OR warehouse_name LIKE '%$search%')");
    $datacount = $querycount->fetch_array();
    $totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {

    /* create a label for order detail status */
    switch ($r['active_status']) {
      case 1:
        $active_status = "<span class='badge badge-outline text-green'>AKTIF</span>";
        break;
      default:
        $active_status = "<span class='badge badge-outline text-danger'>NON-AKTIF</span>";
    }

    // filter product name from weird character
    $product_name = mb_convert_encoding($r['product_name'], "UTF-8");

    $nestedData['no'] 				= $no;
    $nestedData['product_name'] 	= $product_name;
    $nestedData['product_material'] = $r['product_material'];
    $nestedData['warehouse_name']   = $r['warehouse_name'];
    $nestedData['active_status']    = $active_status;
    $nestedData['aksi'] 		    = '<a href=productsDetail?'.Encryption::encode($r['product_id']).' class="badge bg-blue text-blue-fg text-uppercase">Edit</a>';
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
