<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 => 'items_name',
                1 => 'start_date',
                2 => 'expired_date',
                3 => 'expired_in'
            );

$category_id = Encryption::decode($param[1]);
$godeg          = ", DATEDIFF(expired_date, start_date) AS expired_in FROM tbl_items WHERE category_id = '".$category_id."' AND client_id = '".$_SESSION['client_id']."'";
$querycount 		= $conn->query("SELECT count(items_id) as jumlah $godeg");
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
  $sql = "SELECT * $godeg AND (items_name LIKE '%$search%') order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql);

 	$querycount = $conn->query("SELECT count(items_id) as jumlah $godeg AND (items_name LIKE '%$search%')");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $nestedData['no'] 				              = $no;
    $nestedData['items_id'] 	    = $r['items_id'];
    $nestedData['items_name'] 		= $r['items_name'];
    $nestedData['start_date'] 		= $r['start_date'];
    $nestedData['expired_date']     = $r['expired_date'];
    if($r['expired_in'] < 11)
    {
      $expired_in = '<b class=text-danger>'.$r['expired_in'].'</b> day(s)';
    }else{
      $expired_in = '<b class=text-success>'.$r['expired_in'].'</b> day(s)';
    }
    $nestedData['expired_in']     = $expired_in;
    $nestedData['aksi'] 			= "<a href='itemsDetail?".Encryption::encode($r['items_id'])."' class='btn-warning btn-sm'>view</a>";
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
