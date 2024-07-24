<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../../config.php";

$columns = array(
                0 =>'group_name',
                1 => 'group_description',
                2 => 'active_status',
                3 => 'active_status'
            );

/*condition for query */
/* this is to check, if traffic came from search box? if yes then conditional below is active */
if (@$_GET['s'] == 'Y') {
    @$txt_search   = input_data(trim($_GET['txt_search']));
    $s              = 'Y';
}

$id             = 'group_receiver_id';
$select         = "SELECT group_receiver_id, group_name, group_description";
$from           = "FROM tbl_group_receiver WHERE client_id = '".$_SESSION['client_id']."'";
$querycount 		= $conn->query("SELECT count($id) as jumlah $from");
$search         = input_data(trim(@$_GET['txt_search']));
$search_query   = "AND (group_name LIKE '%$search%' or group_description LIKE '%$search%')";
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

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
    $nestedData['group_name']           = $r['group_name'];
    $nestedData['group_description']    = $r['group_description'];
    $nestedData['aksi'] 			    = '<a href=groupReceiverDetail?'.Encryption::encode($r[$id]).' class="badge bg-blue text-blue-fg text-uppercase">Edit</a>';
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
