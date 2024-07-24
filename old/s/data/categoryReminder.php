<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";
$columns = array(
                0 =>'category_detail_name',
                1 =>'reminder_day',
                2 =>'reminder_method',
            );

$godeg          = "FROM tbl_category_detail WHERE client_id = '".$_SESSION['client_id']."'";
$querycount 		= $conn->query("SELECT count(category_detail_id) as jumlah $godeg");
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
  $sql = "SELECT * $godeg AND (category_detail_name LIKE '%$search%') order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql);

 	$querycount = $conn->query("SELECT count(category_detail_id) as jumlah $godeg AND (category_detail_name LIKE '%$search%')");
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
    $nestedData['category_reminder_id'] 	    = $r['category_reminder_id'];
    $nestedData['category_detail_name'] 		= $r['category_detail_name'];

    $s2 = "SELECT reminder_day, reminder_method FROM tbl_category_reminder WHERE category_detail_id = '".$r['category_detail_id']."' LIMIT 1";
    $h2 = mysqli_query($conn, $s2);

    $hasil = array();
    while($r2 = mysqli_fetch_assoc($h2))
    {
        $hasil[] = $r2['reminder_day']." day(s) using ".$r2['reminder_method']."<br/>";
    }
    $hasil = join($hasil);

    $nestedData['reminder_day'] 		        = $hasil;
    $nestedData['reminder_method'] 		        = $r['reminder_method'];
    $nestedData['aksi'] 					  = "<a href='categoryReminderDetail?".Encryption::encode($r['category_reminder_id'])."' class='btn-warning btn-sm'>view</a>";
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
