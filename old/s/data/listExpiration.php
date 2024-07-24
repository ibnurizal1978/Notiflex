<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";

switch ($param[1]) {
  case "total":
    $where = 'date(expired_date) >= curdate() AND archived_status = 0';
    break;
  case "today":
    $where = 'date(expired_date) = curdate() AND archived_status = 0';
    break;
  case "month":
    //$where = 'expired_date >= UNIX_TIMESTAMP(LAST_DAY(CURDATE()) + INTERVAL 1 DAY - INTERVAL 1 MONTH)';
    $where = "(month(expired_date) = month(now()) AND year(expired_date) = year(now())) AND archived_status = 0";
    break;
  case "expired":
    $where = "date(expired_date) < curdate()";
    break;
  case $module_employee:
    $where = "e.type = 'EMPLOYEE' AND archived_status = 0";
    break;
  case $module_vehicle:
    $where = "e.type = 'VEHICLE' AND archived_status = 0";
    break;
  case $module_vendors:
    $where = "e.type = 'VENDORS' AND archived_status = 0";
    break;
  case $module_clients:
    $where = "e.type = 'CLIENTS' AND archived_status = 0";
    break;
  case $module_electronics:
    $where = "e.type = 'ELECTRONICS' AND archived_status = 0";
    break;

  default:
  $where = "e.type = 'CLIENTS'";
    //echo "Your favorite color is neither red, blue, nor green!";
}



$search = @$_POST['search']['value'];

$columns = array(
                0 => 'name',
                1 => 'category_name',
                2 => 'start_date',
                3 => 'expired_date',
                4 => 'expired_in',
                5 => 'held_by',
                6 => 'held_by'
            );
$search_string ="c.name LIKE '%$search%' OR e.name LIKE '%$search%' OR category_name LIKE '%$search%' OR start_date LIKE '%$search%' OR expired_date LIKE '%$search%'";

$select         = "SELECT a.list_id, a.category_id, a.list_expiration_id, category_name, date_format(start_date, '%M %d, %Y') as start_date, date_format(expired_date, '%M %d, %Y') as expired_date, datediff(expired_date, CURDATE()) difference, c.name as held_by, e.name as name, photo_name";
$from           = "FROM tbl_list_expiration a INNER JOIN tbl_category b USING (category_id)  INNER JOIN tbl_list c ON a.held_by = c.id INNER JOIN tbl_list e ON a.list_id = e.id INNER JOIN tbl_list_upload d ON a.list_expiration_id = d.list_expiration_id WHERE $where AND a.client_id = '".$_SESSION['client_id']."'";
$querycount     = $conn->query("SELECT count(e.id) as jumlah $from");
$datacount 		= $querycount->fetch_array();
$totalData 		= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 			= $_POST['length'];
$start 			= $_POST['start'];
$order 			= $columns[$_POST['order']['0']['column']];
$dir 			= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
  $sql1 = "$select $from order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql1);
}else {
  $sql2 = "$select $from AND ($search_string) order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql2);

 	$querycount = $conn->query("SELECT count(e.id) as jumlah $from AND ($search_string)");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {
    $photo_name = $r['photo_name'];

    if($r['difference']<0) { 
      $difference = '<b class="text-danger">ALREADY EXPIRED!</b>';
    } elseif($r['difference']<10) { 
      $difference = '<b class="text-danger">'.$r['difference'].' day(s)</b>';
    }else{
      $difference = '<b class="text-success">'.$r['difference'].' day(s)</b>';
    }

    $nestedData['name']           = $r['name'];
    $nestedData['held_by']        = $r['held_by'];
    $nestedData['category_name']  = $r['category_name'];
    $nestedData['start_date'] 		= $r['start_date'];
    $nestedData['expired_date']   = $r['expired_date'];
    $nestedData['expired_in'] 		= $difference;
    $nestedData['aksi'] 				  = '<a class="btn-sm btn-success" target="_blank" href="../list_upload/'.$photo_name.'">view file</a> <a class="btn-sm btn-warning" href="listExpirationHistory?'.Encryption::encode($r['list_id']).'?'.Encryption::encode($r['category_id']).'">history</a> <a class="btn-info btn-sm" href="listRenew?1?1?'.Encryption::encode($r['list_expiration_id']).'?1?'.$param[1].'">renew</a>';
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
