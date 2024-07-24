<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../../config.php";

$columns = array(
                0 => 'name',
                1 => 'category_master_name',
                2 => 'description',
                3 => 'additional_data',
                4 => 'additional_data'
            );

$id             = 'object_id';   
$select         = "SELECT object_id, name, category_master_name, description, additional_data";
$from           = " FROM tbl_object a INNER JOIN tbl_category_master b USING (category_master_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND a.user_id = '".$_SESSION['user_id']."'";
$querycount     = $conn->query("SELECT count($id) as jumlah $from");
$datacount 		  = $querycount->fetch_array();
$search         = input_data(trim(@$_GET['txt_search']));
$search_query   = "AND (name LIKE '%$search%' OR category_master_name LIKE '%$search%' OR description LIKE '%$search%' OR additional_data LIKE '%$search%')";
$totalData 		  = $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 			    = $_POST['length'];
$start 			    = $_POST['start'];
$order 			    = $columns[$_POST['order']['0']['column']];
$dir 			      = $_POST['order']['0']['dir'];

if($_GET['txt_search']=='')
{
  $sql1 = "$select $from order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql1);
}else {
  $sql2 = "$select $from $search_query order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql2);

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

    $nestedData['name']                 = $r['name'];
    $nestedData['description']          = $r['description'];
    $nestedData['category_master_name'] = $r['category_master_name'];
    $nestedData['additional_data'] 	    = $r['additional_data'];
    $nestedData['aksi'] 				        = '<a class="badge bg-info text-blue-fg text-uppercase" href="objectsDetail?'.Encryption::encode($r['object_id']).'">detail</a> <a class="badge bg-blue text-blue-fg text-uppercase" href="objectsEdit?'.Encryption::encode($r['object_id']).'">edit</a>';
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
