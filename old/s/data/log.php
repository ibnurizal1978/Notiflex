<?php

ini_set('display_errors',1);  error_reporting(E_ALL);
$db_server        = 'localhost';
$db_user          = 'ordb6183_ordermatix';
$db_password      = 'Database@123';
$db_name          = 'ordb6183_db_ordermatix';
$conn 			  = new mysqli($db_server,$db_user,$db_password,$db_name);

$columns = array(
                0 =>'id',
                1 =>'user_id',
                2=> 'notes',
                3=> 'action',
            );

$querycount 		= $conn->query("SELECT count(id) as jumlah FROM tbl_log");
$datacount 			= $querycount->fetch_array();


$totalData 			= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 					= $_POST['length'];
$start 					= $_POST['start'];
$order 					= $columns[$_POST['order']['0']['column']];
$dir 						= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
	$query = $conn->query("SELECT * FROM tbl_log order by $order $dir LIMIT $limit OFFSET $start");
}else {
  $search = $_POST['search']['value'];
  $query = $conn->query("SELECT * FROM tbl_log WHERE notes LIKE '%$search%' or action LIKE '%$search%' order by $order $dir LIMIT $limit OFFSET $start");

 	$querycount = $conn->query("SELECT count(id) as jumlah FROM tbl_log WHERE notes LIKE '%$search%' or action LIKE '%$search%'");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $nestedData['no'] = $no;
    $nestedData['id'] = $r['id'];
    $nestedData['user_id'] = $r['user_id'];
    $nestedData['notes'] = $r['notes'];
    $nestedData['action'] = $r['action'];
    $nestedData['aksi'] = "<a href='#' class='btn-warning btn-sm'>Ubah</a>&nbsp; <a href='#' class='btn-danger btn-sm'>Hapus</a>";
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
