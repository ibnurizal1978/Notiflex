<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'user_id',
                1 =>'username',
                2 => 'full_name',
                3 => 'role_name',
                4 => 'active_status'
            );
$client_id = Encryption::decode($param[1]);
$godeg          = 'FROM tbl_user a INNER JOIN tbl_user_role b USING (role_id)';
$querycount 		= $conn->query("SELECT count(user_id) as jumlah $godeg WHERE owner_id = '".$_SESSION['client_id']."' AND client_id = '".$client_id."'");
$datacount 			= $querycount->fetch_array();
$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT * $godeg WHERE owner_id = '".$_SESSION['client_id']."' AND client_id = '".$client_id."' order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $sql = "SELECT * $godeg WHERE owner_id = '".$_SESSION['client_id']."' AND client_id = '".$client_id."' AND (username LIKE '%$search%' or full_name LIKE '%$search%' or role_name LIKE '%$search%') order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql);

 	$querycount = $conn->query("SELECT count(user_id) as jumlah $godeg WHERE owner_id = '".$_SESSION['client_id']."' AND client_id = '".$client_id."' AND (username LIKE '%$search%' or full_name LIKE '%$search%' or role_name LIKE '%$search%')");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $nestedData['no'] 						  = $no;
    $nestedData['user_id'] 	        = $r['user_id'];
    $nestedData['full_name'] 				= $r['full_name'];
    $nestedData['username']         = $r['username'];
    $nestedData['role_name']        = $r['role_name'];
    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
    $nestedData['active_status']    = $active_status;
    $nestedData['aksi'] 					  = "<a href='userDetail?".Encryption::encode($r['user_id'])."' class='btn-warning btn-sm'>view</a>";
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
