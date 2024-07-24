<?php
//ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../../checkSession.php";
require_once "../../components.php";
//require_once "access.php";

// this is if action = add
if($param[1]=='add')
{
  
    $product_name	        =	input_data($_POST['product_name']);
    $product_sku		    =	input_data($_POST['product_sku']);
    $product_minimum_order  =	input_data($_POST['product_minimum_order']);
    $product_unit	        =	input_data($_POST['product_unit']);
    $product_price	        =	input_data($_POST['product_price']);
    $product_color	        =	input_data($_POST['product_color']);
    $product_material	    =	input_data($_POST['product_material']);
    $warehouse_id	        =	input_data($_POST['warehouse_id']);
    $product_description	=	input_data($_POST['product_description']);
    @$product_price         = str_replace(',', '', @$product_price);
    $product_sku            = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $product_sku);
    $product_name           = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $product_name);

    if($product_name == "" || $product_sku == "" || $warehouse_id == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    // check if product_name is exist? reject it
    $s = "SELECT product_name FROM tbl_product WHERE client_id = '".$_SESSION['client_id']."' AND product_name = '".$product_name."' LIMIT 1";
    $h = mysqli_query($conn,$s);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
        icon: 'error',
        text: 'Nama ini sudah terdaftar',
        })</script>";
        exit();
    }

    //success, insert data
    $s 	= "INSERT INTO tbl_product SET product_name ='".$product_name."', product_sku='".$product_sku."', product_minimum_order='".$product_minimum_order."', product_unit='".$product_unit."', product_price='".$product_price."', product_color='".$product_color."',product_material='".$product_material."', warehouse_id='".$warehouse_id."', created_at = UTC_TIMESTAMP(),  active_status='1', product_description='".$product_description."', client_id = '".$_SESSION['client_id']."'";
    mysqli_query($conn,$s);
    $last_id = mysqli_insert_id($conn);

    // add log
    addLog($conn, $_SESSION['user_id'],'ADD PRODUCT', 'add product id: '.$last_id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'products';
    });</script>";
    exit();
}



// this is if action = edit
if($param[1]=='edit')
{
  
    $product_id		        =	input_data(Encryption::decode($_POST['product_id']));
    $product_name	        =	input_data($_POST['product_name']);
    $product_sku		    =	input_data($_POST['product_sku']);
    $product_minimum_order  =	input_data($_POST['product_minimum_order']);
    $product_unit	        =	input_data($_POST['product_unit']);
    $product_price	        =	input_data($_POST['product_price']);
    $product_color	        =	input_data($_POST['product_color']);
    $product_material	    =	input_data($_POST['product_material']);
    $warehouse_id	        =	input_data($_POST['warehouse_id']);
    $product_description	=	input_data($_POST['product_description']);
    $active_status	        =	input_data($_POST['active_status']);
    @$product_price         = str_replace(',', '', @$product_price);
    $product_sku            = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $product_sku);
    $product_name           = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $product_name);

    if($product_name == "" || $product_sku == "" || $warehouse_id == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    //check if data is exist?
    $sql 	= "SELECT product_name FROM tbl_product WHERE client_id = '".$_SESSION['client_id']."' AND product_name = '".$product_name."' AND product_id <> '".$product_id."' LIMIT 1";
    $h 		= mysqli_query($conn,$sql);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
            icon: 'error',
            text: 'Nama ini sudah terdaftar',
        })</script>";
        exit();
    }

    //success
    $s = "UPDATE tbl_product SET product_name ='".$product_name."', product_sku='".$product_sku."', product_minimum_order='".$product_minimum_order."', product_unit='".$product_unit."', product_price='".$product_price."', product_color='".$product_color."',product_material='".$product_material."', warehouse_id='".$warehouse_id."',updated_at = UTC_TIMESTAMP(),  active_status='".$active_status."', product_description='".$product_description."' WHERE product_id = '".$product_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    mysqli_query($conn, $s);

    $s2 = "UPDATE tbl_order_detail SET product_name ='".$product_name."', product_sku='".$product_sku."', product_unit='".$product_unit."', warehouse_id='".$warehouse_id."' WHERE product_id = '".$product_id."' AND owner_id = '".$_SESSION['client_id']."'";
    mysqli_query($conn, $s2);

    $s3 = "UPDATE tbl_order_detail_history SET product_name ='".$product_name."', product_sku='".$product_sku."', product_unit='".$product_unit."', warehouse_id='".$warehouse_id."' WHERE product_id = '".$product_id."' AND owner_id = '".$_SESSION['client_id']."'";
    mysqli_query($conn, $s3);

    // add log
    addLog($conn, $_SESSION['user_id'],'EDIT PRODUCT', 'edit product id: '.$product_id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'products';
    });</script>";
    exit();
}

//this is for add a product using upload file 
if($param[1]=='upload')
{

    $ext = end((explode(".", $_FILES["file"]["name"])));
    if($ext <> 'xlsx')
    {
        echo "<script>Swal.fire({
            icon: 'success',
            text: 'File harus berekstensi .xlsx',
        }).then(function() {
            window.location = 'products';
        });</script>";
        exit();
    }

    require('../../assets/plugins/spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
    require('../../assets/plugins/spreadsheet-reader-master/SpreadsheetReader.php');
  
    //upload data excel kedalam folder uploads
    $target_dir = "../../uploads/".basename($_FILES['excel_file']['name']);
    move_uploaded_file($_FILES['excel_file']['tmp_name'],$target_dir);

    $Reader = new SpreadsheetReader($target_dir);
  
    foreach ($Reader as $Key => $Row)
    {
        // import data excel mulai baris ke-2 (karena ada header pada baris 1)
        if ($Key < 1) continue;

        $product_name          = $Row[0];
        $product_sku           = $Row[1];
        $product_minimum_order = $Row[2];
        $product_unit          = $Row[3];
        $product_price         = $Row[4];
        $product_color         = $Row[5];
        $product_warehouse     = $Row[6];
        $product_description   = $Row[7];
    
        $s = "SELECT product_name FROM tbl_product WHERE client_id = '".$_SESSION['client_id']."' AND product_name = '".$product_name."' LIMIT 1";
        $h = mysqli_query($conn, $s);
        if(mysqli_num_rows($h) == 0)
        {
            $s = "INSERT INTO tbl_product (client_id,product_name,product_sku,product_minimum_order,product_unit,product_price,product_color,warehouse_id,product_description,created_at,active_status) VALUES ('".$_SESSION['client_id']."', '".$product_name."','".$product_sku."','".$product_minimum_order."','".$product_unit."','".$product_price."','".$product_color."','".$product_warehouse."','".$product_description."',UTC_TIMESTAMP(),1)";
            mysqli_query($conn, $s);
        }
    }

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'products';
    });</script>";
    exit();
}

//this is for product link
if($param[1]=='link')
{
    $product_id	=	input_data($_POST['product_id']);
    $client_id	=	input_data($_POST['client_id']);

    $s = "UPDATE tbl_product SET product_link_id='".$client_id."' WHERE product_id = '".$product_id."' AND client_id='".$_SESSION['client_id']."' LIMIT 1";
    mysqli_query($conn, $s);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    })</script>";
    exit();
}