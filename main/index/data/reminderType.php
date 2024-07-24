<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../../config.php";

$columns = array(
                0 => 'reminder_type_name',
                1 => 'category_master_name',
                2 => 'category_master_name',
            );

/*condition for query */
/* this is to check, if traffic came from search box? if yes then conditional below is active */
if (@$_GET['s'] == 'Y') {
    @$txt_search   = input_data(trim($_GET['txt_search']));
    $s              = 'Y';
}

$id             = 'reminder_type_id';
$select         = "SELECT reminder_type_id, reminder_type_name, category_master_id, category_master_name";
$from           = "FROM tbl_reminder_type a INNER JOIN tbl_category_master b USING (category_master_id) WHERE a.client_id = '".$_SESSION['client_id']."'";
$querycount 	= $conn->query("SELECT count($id) as jumlah $from");
$search         = input_data(trim(@$_GET['txt_search']));
$search_query   = "AND (reminder_type_name LIKE '%$search%' OR category_master_name LIKE '%$search%')";
$datacount 		= $querycount->fetch_array();
$totalData 		= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 			= $_POST['length'];
$start 			= $_POST['start'];
$order 			= $columns[$_POST['order']['0']['column']];
$dir 			= $_POST['order']['0']['dir'];

if($_GET['txt_search']=='')
{
	$query = $conn->query("$select $from order by $order $dir LIMIT $limit OFFSET $start");
}else {
  //$search = input_data(trim($_GET['txt_search']));
  $sql = "$select $from $search_query order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql);

 	$querycount = $conn->query("SELECT count($id) as jumlah $from $search_query");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $nestedData['no'] 				    = $no;
    $nestedData['reminder_type_name']   = $r['reminder_type_name'];
    $nestedData['category_master_name'] = $r['category_master_name'];
    $nestedData['aksi'] 			    = '<a href=reminderTypeDetail?'.Encryption::encode($r[$id]).' class="badge bg-blue text-blue-fg text-uppercase">Edit</a>';
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
