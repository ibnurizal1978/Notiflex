<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'warehouse_name',
                1 =>'pallet_name',
                2 =>'qty',
                3 => 'tambah',
                4 => 'kurang',
                5 => 'reserved',
                6 => 'order_code',
                7 => 'notes',
                8 => 'created_at',
                9 => 'full_name',
            );

//SELECT c.name as warehouse_name, d.name as pallet_name, qty, CASE WHEN qty_action = 'ADD' THEN qty_action ELSE NULL END AS Tambah, CASE WHEN qty_action = 'REMOVE' OR 'RESERVED' THEN qty_action ELSE NULL END AS Kurang, CASE WHEN qty_action = 'RESERVED' THEN qty_action ELSE NULL END AS Reserved, order_code, date_format(a.created_at, '%d-%m-%Y at %H:%i') as created_at, full_name FROM tbl_product_qty a INNER JOIN tbl_user b USING (user_id) INNER JOIN tbl_warehouse_master c using (warehouse_master_id) INNER JOIN tbl_warehouse_pallet d USING (warehouse_pallet_id) where product_id = 1

$product_id = Encryption::decode($param[1]);
$godeg          = "FROM tbl_product_qty a INNER JOIN tbl_user b USING (user_id) INNER JOIN tbl_warehouse_master c USING (warehouse_master_id) INNER JOIN tbl_warehouse_pallet d USING (warehouse_pallet_id)";
$querycount 		= $conn->query("SELECT count(product_qty_id) as jumlah $godeg WHERE a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."'");
$datacount 			= $querycount->fetch_array();
if(isset($datacount['jumlah'])) { $totalData = $datacount['jumlah']; }else{ $totalData = 0; }
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT c.name as warehouse_name, d.name as pallet_name, qty, CASE WHEN qty_action = 'ADD' THEN qty_action ELSE NULL END AS tambah, CASE WHEN qty_action = 'REMOVE' OR 'RESERVED' THEN qty_action ELSE NULL END AS kurang, CASE WHEN qty_action = 'RESERVED' THEN qty_action ELSE NULL END AS reserved, order_code, date_format(DATE_ADD(a.created_at, INTERVAL '".$_SESSION['selisih']."' hour), '%M %d, %Y at %H:%i') as date, full_name, a.notes $godeg WHERE a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."' order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT c.name as warehouse_name, d.name as pallet_name, qty, CASE WHEN qty_action = 'ADD' THEN qty_action ELSE NULL END AS tambah, CASE WHEN qty_action = 'REMOVE' OR 'RESERVED' THEN qty_action ELSE NULL END AS kurang, CASE WHEN qty_action = 'RESERVED' THEN qty_action ELSE NULL END AS reserved, order_code, date_format(DATE_ADD(a.created_at, INTERVAL '".$_SESSION['selisih']."' hour), '%M %d, %Y at %H:%i') as date, full_name, a.notes $godeg WHERE a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."' AND (a.order_code LIKE '%$search%' or a.notes LIKE '%$search%' or a.qty_action LIKE '%$search%' or c.name LIKE '%$search%' or qty LIKE '%$search%') order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(product_qty_id) as jumlah $godeg WHERE is_temp = 0 AND a.client_id = '".$_SESSION['client_id']."' AND product_id = '".$product_id."' AND (a.order_code LIKE '%$search%' or a.notes LIKE '%$search%' or a.qty_action LIKE '%$search%' or c.name LIKE '%$search%' or qty LIKE '%$search%')");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
   /* if($r['qty_action'] == 'ADD') {
        $qty_action = '<span class="badge badge-success">ADD</span>';
    }elseif($r['qty_action'] == 'REMOVE') {
        $qty_action = '<span class="badge badge-danger">REMOVE</span>';
    }else{
        $qty_action = '<span class="badge badge-info">RESERVED</span>';
    }*/
    $nestedData['no'] 				      = $no;
    $nestedData['warehouse_name']   = html_entity_decode($r['warehouse_name'], ENT_QUOTES);
    $nestedData['pallet_name']      = html_entity_decode($r['pallet_name'], ENT_QUOTES);
    $nestedData['qty'] 	            = $r['qty'];
    $nestedData['tambah'] 	        = '<span class="badge badge-success">'.$r['tambah'].'</span>';
    $nestedData['kurang'] 	        = '<span class="badge badge-danger">'.$r['kurang'].'</span>';
    $nestedData['reserved'] 	      = '<span class="badge badge-warning">'.$r['reserved'].'</span>';
    //$nestedData['qty_action'] 	    = $qty_action;
    $nestedData['date'] 	          = $r['date'];
    $nestedData['by'] 	            = $r['full_name'];
    $nestedData['notes'] 	          = $r['notes'];
    $nestedData['order_code'] 	    = $r['order_code'];
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
