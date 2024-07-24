<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'category_receiver_name',
                1 => 'type',
                2 => 'reminder_for',
                3 =>'active_status',
            );

$godeg          = "FROM tbl_category_receiver WHERE client_id = '".$_SESSION['client_id']."'";
$querycount 		= $conn->query("SELECT count(category_receiver_id) as jumlah $godeg");
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
  $sql = "SELECT * $godeg AND (category_receiver_name LIKE '%$search%') order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql);

 	$querycount = $conn->query("SELECT count(category_receiver_id) as jumlah $godeg AND (category_receiver_name LIKE '%$search%')");
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
    $nestedData['category_receiver_id'] 	    = $r['category_receiver_id'];
    $nestedData['category_receiver_name'] 		= $r['category_receiver_name'];
    $nestedData['type'] 		                = $r['type'];

    /*$s2 = "SELECT category_name, category_child_name, category_detail_name FROM tbl_category a INNER JOIN tbl_category_child b USING (category_id) INNER JOIN tbl_category_detail c USING (category_child_id) INNER JOIN tbl_category_reminder_receiver d USING (category_detail_id) WHERE c.client_id = '".$_SESSION['client_id']."' AND d.category_receiver_id = '".$r['category_receiver_id']."'";
    $h2 = mysqli_query($conn, $s2);
    $hasil = array();
    while($r2 = mysqli_fetch_assoc($h2))
    {
        $hasil[] = $r2['category_name']." &raquo; ".$r2['category_child_name']." &raquo; ".$r2['category_detail_name'].'<br/>';
    }
    $hasil = join($hasil);*/
    
    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
    //$nestedData['reminder_for']                 = $hasil;
    $nestedData['active_status']                = $active_status;
    $nestedData['aksi'] 					    = "<a href='categoryReceiverDetail?".Encryption::encode($r['category_receiver_id'])."' class='btn-warning btn-sm'>view</a>";
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
