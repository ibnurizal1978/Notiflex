<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../../config.php";

$columns = array(
                0 => 'name',
                1 => 'reminder_type_name',
                2 => 'expired_date',
                3 => 'difference',
                4 => 'difference'
            );

$id             = 'a.data_id';   
$select         = "SELECT data_expiration_id, reminder_type_id, a.data_id as data_id, name, reminder_type_name, date_format(start_date, '%M %d, %Y') as start_date, date_format(expired_date, '%M %d, %Y') as expired_date, datediff(expired_date, CURDATE()) difference, file_name";
$from           = " FROM tbl_data a INNER JOIN tbl_category_master b USING (category_master_id) INNER JOIN tbl_reminder_type c USING (category_master_id) INNER JOIN tbl_data_expiration d USING (data_id) INNER JOIN tbl_data_file e USING (data_expiration_id) WHERE a.client_id = '".$_SESSION['client_id']."'";
$querycount     = $conn->query("SELECT count($id) as jumlah $from");
$datacount 		  = $querycount->fetch_array();
$search         = input_data(trim(@$_GET['txt_search']));
$search_query   = "AND (name LIKE '%$search%' OR category_master_name LIKE '%$search%' OR reminder_type_name LIKE '%$search%' OR start_date LIKE '%$search%' OR expired_date LIKE '%$search%')";
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
    $photo_name = $r['file_name'];

    if($r['difference']<0) { 
      $difference = '<b class="text-danger">ALREADY EXPIRED!</b>';
    } elseif($r['difference']<10) { 
      $difference = '<b class="text-danger">'.$r['difference'].' day(s)</b>';
    }else{
      $difference = '<b class="text-success">'.$r['difference'].' day(s)</b>';
    }

    $nestedData['name']           = $r['name'];
    $nestedData['reminder_type_name']  = $r['reminder_type_name'];
    $nestedData['start_date'] 		= $r['start_date'];
    $nestedData['expired_date']   = $r['expired_date'];
    $nestedData['difference'] 		= $difference;
    $nestedData['aksi'] 				  = '<a class="badge bg-info text-blue-fg text-uppercase" target="_blank" href="../../uploads/'.$photo_name.'">view file</a> <a class="badge bg-blue text-blue-fg text-uppercase" href="listExpirationHistory?'.Encryption::encode($r['data_id']).'?'.Encryption::encode($r['reminder_type_id']).'">history</a> <a class="badge bg-warning text-blue-fg text-uppercase" href="listRenew?1?1?'.Encryption::encode($r['data_expiration_id']).'?1?'.$param[1].'">renew</a>';
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
