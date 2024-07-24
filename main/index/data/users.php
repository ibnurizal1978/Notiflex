<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../../config.php";

$columns = array(
                0 => 'full_name',
                1 => 'username',
                2 => 'business_name',
                3 => 'active_status',
                4 => 'active_status',
            );

/*condition for query */
/* this is to check, if traffic came from search box? if yes then conditional below is active */
if (@$_GET['s'] == 'Y') {
    @$txt_search   = input_data(trim($_GET['txt_search']));
    $s              = 'Y';
}

$id             = 'user_id';
$select         = "SELECT user_id,username,full_name, date_format(a.last_login,'%d-%m-%Y at %H:%i') AS last_login,a.active_status,b.business_name";
$from           = "FROM tbl_user a INNER JOIN tbl_client b USING (client_id) WHERE a.client_id = '".$_SESSION['client_id']."'";
$querycount 		= $conn->query("SELECT count($id) as jumlah $from");
$search         = input_data(trim(@$_GET['txt_search']));
$search_query   = "AND (full_name LIKE '%$search%' OR username LIKE '%$search%' OR business_name LIKE '%$search%')";
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
  $sql = "$select $from $search_query order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql);

  $sql = "SELECT count($id) as jumlah $from $search_query";
 	$querycount = $conn->query($sql);
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
        $active_status = "<span class='badge badge-outline text-green'>Active</span>";
        break;
      default:
        $active_status = "<span class='badge badge-outline text-danger'>Inactive</span>";
    }

    $nestedData['no'] 				            = $no;
    $nestedData['full_name'] 	            = $r['full_name'];
    $nestedData['username'] 		          = $r['username'];
    $nestedData['business_name']   = $r['business_name'];
    $nestedData['active_status']          = $active_status;
    $nestedData['aksi'] 					        = '<a href=usersDetail?'.Encryption::encode($r[$id]).' class="badge bg-blue text-blue-fg text-uppercase">Edit</a>';
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
