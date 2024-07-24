<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";

$title = base64_decode($param[1]);
if($title == 'EMPLOYEE' && @$param[2] == 1)
{
  $type = 'EMPLOYEE CERTIFICATION';
}elseif($title == 'EMPLOYEE' && @$param[2] == 2)
{
  $type = 'EMPLOYEE CONTRACT';
}else{
  $type = $title;
}

$columns = array(
                0 => 'category_name',
                1 => 'category_description',
                2 => 'category_duration',
                3 => 'active_status',
            );

$godeg          = "FROM tbl_category WHERE category_type = '".$type."' AND client_id = '".$_SESSION['client_id']."'";
$querycount 		= $conn->query("SELECT count(category_id) as jumlah $godeg");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT * $godeg order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $sql = "SELECT * $godeg AND (category_name LIKE '%$search%' OR category_description LIKE '%$search%' OR category_duration LIKE '%$search%') order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql);

 	$querycount = $conn->query("SELECT count(category_id) as jumlah $godeg AND (category_name LIKE '%$search%' OR category_description LIKE '%$search%' OR category_duration LIKE '%$search%')");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $nestedData['no'] 				          = $no;
    $nestedData['category_id'] 	        = $r['category_id'];
    $nestedData['category_name'] 		    = $r['category_name'];
    $nestedData['category_description'] = $r['category_description'];
    $nestedData['category_duration'] 		= $r['category_duration']." ".$r['category_duration_cycle']."(s)";
    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
    $nestedData['active_status']    = $active_status;
    $nestedData['aksi'] 					  = "<a href='categoryDetail?".$param[1]."?".@$param[2]."?".Encryption::encode($r['category_id'])."' class='btn-warning btn-sm'>view</a>";
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