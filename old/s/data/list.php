<?php
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../inc/check_session.php";

$title = base64_decode($param[1]);
$search = @$_POST['search']['value'];
if($title == $module_employee) {
$columns = array(
                0 =>'name',
                1 => 'email',
                2 => 'phone',
                3 => 'position',
                4 => 'active_status',
                5 => 'active_status' 
            );
$search_string ="name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR position LIKE '%$search%'";
}

if($title == $module_vehicle) {
$columns = array(
                0 =>'name',
                1 => 'vehicle_type',
                2 => 'vehicle_year',
                3 => 'vehicle_plate',
                4 => 'active_status',
                5 => 'active_status' 
            );
$search_string ="name LIKE '%$search%' OR vehicle_type LIKE '%$search%' OR vehicle_year LIKE '%$search%' OR vehicle_plate LIKE '%$search%'";
}

if($title == $module_vendors || $title == $module_clients) {
  $columns = array(
                  0 =>'name',
                  1 => 'email',
                  2 => 'phone',
                  3 => 'active_status',
                  4 => 'active_status' 
              );
  $search_string ="name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR position LIKE '%$search%'";
}

if($title == $module_electronics) {
  $columns = array(
                  0 =>'name',
                  1 => 'electronic_type',
                  2 => 'serial_number',
                  3 => 'active_status',
                  4 => 'active_status' 
              );
  $search_string ="name LIKE '%$search%' OR electronic_type LIKE '%$search%' OR serial_number LIKE '%$search%'";
}

if($title == $module_domain) {
  $columns = array(
                  0 =>'name',
                  1 => 'start_date',
                  2 => 'expired_date' 
              );
  $search_string ="name LIKE '%$search%'";
}

if($title == $module_domain)
{
  $sql            = "FROM tbl_list a INNER JOIN tbl_list_expiration b ON a.id = b.list_id WHERE a.client_id = '".$_SESSION['client_id']."' AND type = '".$title."'";
}else{
  $sql            = "FROM tbl_list WHERE client_id = '".$_SESSION['client_id']."' AND type = '".$title."'";
}

$querycount     = $conn->query("SELECT count(id) as jumlah $sql");
$datacount 		= $querycount->fetch_array();
$totalData 		= $datacount['jumlah'];
$totalFiltered 	= $totalData;
$limit 			= $_POST['length'];
$start 			= $_POST['start'];
$order 			= $columns[$_POST['order']['0']['column']];
$dir 			= $_POST['order']['0']['dir'];

if(empty($_POST['search']['value']))
{
    $sql1 = "SELECT * $sql order by $order $dir LIMIT $limit OFFSET $start";
	$query = $conn->query($sql1);
}else {
  //$search = $_POST['search']['value'];
  //$search_string ="name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR position LIKE '%$search%'";
  $sql2 = "SELECT * $sql AND ($search_string) order by $order $dir LIMIT $limit OFFSET $start";
  $query = $conn->query($sql2);

 	$querycount = $conn->query("SELECT count(id) as jumlah $sql AND ($search_string)");
	$datacount = $querycount->fetch_array();
 	$totalFiltered = $datacount['jumlah'];
}

$data = array();
if(!empty($query))
{
	$no = $start + 1;
  while ($r = $query->fetch_array())
  {

    if($title == $module_employee) {
    $nestedData['no'] 	        = $no;
    $nestedData['id'] 	        = $r['id'];
    $nestedData['name']         = $r['name'];
    $nestedData['position']     = $r['position'];
    $nestedData['email'] 		= $r['email'];
    $nestedData['phone'] 		= $r['phone'];
    }

    if($title == $module_vehicle) {
        $nestedData['no'] 	        = $no;
        $nestedData['id'] 	        = $r['id'];
        $nestedData['name']         = $r['name'];
        $nestedData['vehicle_type']     = $r['vehicle_type'];
        $nestedData['vehicle_plate'] 		= $r['vehicle_plate'];
        $nestedData['vehicle_year'] 		= $r['vehicle_year'];
    }

    if($title == $module_vendors || $title == $module_clients) {
      $nestedData['no'] 	        = $no;
      $nestedData['id'] 	        = $r['id'];
      $nestedData['name']         = $r['name'];
      $nestedData['email'] 		= $r['email'];
      $nestedData['phone'] 		= $r['phone'];
    }

    if($title == $module_electronics) {
      $nestedData['no'] 	        = $no;
      $nestedData['id'] 	        = $r['id'];
      $nestedData['name']         = $r['name'];
      $nestedData['electronic_type']     = $r['electronic_type'];
      $nestedData['serial_number'] 		= $r['serial_number'];
    }

    if($title == $module_domain) {
      $nestedData['no'] 	        = $no;
      $nestedData['id'] 	        = $r['id'];
      $nestedData['name']         = $r['name'];
      $nestedData['start_date']   = $r['start_date'];
      $nestedData['expired_date'] = $r['expired_date'];
    }

    if($r['active_status'] == 1)
    {
      $active_status = "<label class='badge badge-pill badge-success'>Active</a>";
    }else{
      $active_status = "<label class='badge badge-pill badge-danger'>Inactive</a>";
    }
    $nestedData['active_status']    = $active_status;
    //$nestedData['aksi'] 					  = "<a href='listDetail?".Encryption::encode($r['id'])."' class='btn-warning btn-sm'>view</a>";
    $nestedData['aksi'] 					  = "<a href='listDetail?".$param[1]."?".Encryption::encode($r['id'])."' class='btn-warning btn-sm'>view</a>";
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
