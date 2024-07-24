<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'name',
                1 => 'email',
                2 => 'phone',
                3 => 'position',
                4 => 'active_status' 
            );

$godeg          = "FROM tbl_employee WHERE client_id = '".$_SESSION['client_id']."'";
$querycount 		= $conn->query("SELECT count(employee_id) as jumlah $godeg");
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
  $sql = "SELECT * $godeg AND (name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR position LIKE '%$search%') order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql);

 	$querycount = $conn->query("SELECT count(employee_id) as jumlah $godeg AND (name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR position LIKE '%$search%')");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $nestedData['no'] 	        = $no;
    $nestedData['employee_id'] 	= $r['employee_id'];
    $nestedData['name']    = $r['name'];
    $nestedData['position']  = $r['position'];
    $nestedData['email'] 		= $r['email'];
    $nestedData['phone'] 		= $r['phone'];
    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
    $nestedData['active_status']    = $active_status;
    $nestedData['aksi'] 					  = "<a href='employeeDetail?".Encryption::encode($r['employee_id'])."' class='btn-warning btn-sm'>view</a>";
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
