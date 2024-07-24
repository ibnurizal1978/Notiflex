<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../../checkSession.php";
require_once "../../components.php";
//require_once "access.php";
$tbl1 = 'tbl_object';
$tbl2 = 'tbl_object_expiration';
$tbl3 = 'tbl_object_file';

// this is if action = add
if($param[1]=='add')
{
  
    $name	            =	input_data($_POST['name']);
    $description	    =	input_data($_POST['description']);
    $category_master_id	=	input_data($_POST['category_master_id']);
    $additional_data	=	input_data($_POST['additional_data']);

    if($name == "" || $category_master_id == "" || $additional_data == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    // check if data is exist? reject it
    $s = "SELECT name FROM $tbl1 WHERE name = '".$name."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    $h = mysqli_query($conn,$s);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
        icon: 'error',
        text: '$notif_data_duplicate',
        })</script>";
        exit();
    }


    //insert into tbl_list
    $s1 	= "INSERT INTO $tbl1 SET name = '".$name."', description = '".$description."', category_master_id = '".$category_master_id."', additional_data = '".$additional_data."', created_at = UTC_TIMESTAMP(), user_id = '".$_SESSION['user_id']."', client_id = '".$_SESSION['client_id']."'";
    mysqli_query($conn,$s1);
    $last_id = mysqli_insert_id($conn);

    // add log
    addLog($conn, $_SESSION['user_id'],'ADD OBJECT DATA', 'add id: '.$last_id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'objects';
    });</script>";
    exit();
}


if($param[1]=='edit')
{
  
    $id                 =   input_data(Encryption::decode($_POST['id']));
    $name               =   input_data($_POST['name']);
    $description        =   input_data($_POST['description']);
    $category_master_id =   input_data($_POST['category_master_id']);
    $additional_data    =   input_data($_POST['additional_data']);

    if($id == "" || $name == "" || $category_master_id == "" || $additional_data == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    // check if data is exist? reject it
    $s = "SELECT name FROM $tbl1 WHERE name = '".$name."' AND client_id = '".$_SESSION['client_id']."' AND object_id <> '".$id."' LIMIT 1";
    $h = mysqli_query($conn,$s);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
        icon: 'error',
        text: '$notif_data_duplicate',
        })</script>";
        exit();
    }


    //success
    $s1     = "UPDATE $tbl1 SET name = '".$name."', description = '".$description."', category_master_id = '".$category_master_id."', additional_data = '".$additional_data."', updated_at = UTC_TIMESTAMP() WHERE client_id = '".$_SESSION['client_id']."' AND object_id = '".$id."' LIMIT 1";
    mysqli_query($conn,$s1);

    // add log
    addLog($conn, $_SESSION['user_id'],'UPDATE OBJECT DATA', 'add id: '.$id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'objects';
    });</script>";
    exit();
}


if($param[1]=='addReminderType')
{
  
    $reminder_type_id   =   input_data($_POST['reminder_type_id']);
    $object_id          =   input_data(Encryption::decode($_POST['object_id']));

    if($reminder_type_id == "" || $object_id == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    // check if data is exist? reject it
    $s = "SELECT reminder_type_id FROM $tbl2 WHERE reminder_type_id = '".$reminder_type_id."' AND object_id = '".$object_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
    $h = mysqli_query($conn,$s);
    if(mysqli_num_rows($h)>0) {
        echo "<script>Swal.fire({
        icon: 'error',
        text: '$notif_data_duplicate',
        })</script>";
        exit();
    }


    //success
    //insert
    $s     = "INSERT INTO $tbl2 SET reminder_type_id = '".$reminder_type_id."', object_id = '".$object_id."', created_at = UTC_TIMESTAMP(), client_id = '".$_SESSION['client_id']."'";
    mysqli_query($conn,$s);
    $last_id = mysqli_insert_id($conn);

    // add log
    addLog($conn, $_SESSION['user_id'],'ADD OBJECT EXPIRATION DATA', 'add id: '.$last_id);
    $object_id = Encryption::encode($object_id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'objectsDetail?$object_id';
    });</script>";
    exit();
}

// this is if action = addExpirationData, upload data expiration with document
if($param[1]=='addExpirationData')
{
  
    $reminder_type_id   =   input_data(@$_POST['reminder_type_id']);
    $description        =   input_data(@$_POST['description']);
    $object_id          =   input_data(Encryption::decode(@$_POST['object_id']));
    $start_date         =   input_data(@$_POST['start_date']);
    $expired_date       =   input_data(@$_POST['expired_date']);
    $expired_day        =   input_data(@$_POST['expired_day']);
    $expired_duration   =   input_data(@$_POST['expired_duration']);

    if($reminder_type_id == "" || $start_date == "") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: '$notif_data_empty',
        })</script>";
        exit();
    }

    //file larger than 5MB? reject it
    if ($_FILES['list_upload']['size'] > 4900000) {
        echo "<script>Swal.fire({
            icon: 'error',
            text: 'File size should not more than 5 MB',
            })</script>";
            exit();
    }

    //file didn't upload? reject it
    if($_FILES["list_upload"]["error"] != 0) {
        echo "<script>Swal.fire({
            icon: 'error',
            text: 'Please upload document or photo for evidence.',
            })</script>";
            //echo 'ayam goreng -> '.$_FILES["list_upload"]["error"];
             exit();
    }


    //prepare file attribute
    $temp               = explode(".", $_FILES["list_upload"]["name"]);
    $file_name          = $_FILES['list_upload']['name'];
    $target_dir         = '../../uploads/';
    $imageFileType      = strtolower($temp[1]);
    $extensions_arr     = array("jpg","jpeg","png","gif");
  
    if($imageFileType == "exe" || $imageFileType == "apk" || $imageFileType == "bat") {
        echo "<script>Swal.fire({
            icon: 'error',
            text: 'Do not upload exe, bat or apk file!',
            })</script>";
            exit();
    }

    //if user choose expired date, it will follow date format from frontend. otherwise must follow alternative expired date
    if($expired_date <> '')
    {
        $exp_date = "'".$expired_date."'";
    }else{
        $exp_date = "DATE_ADD('".$start_date."', INTERVAL ".$expired_day." ".$expired_duration.")";
    }

    //success
    //upload file
    move_uploaded_file($_FILES["list_upload"]["tmp_name"], $target_dir.$file_name);

    //check if the same reminder_type_id insert to this object? If yes so previous same data should be set to archived_status = 1 because we must assume the new data is updated data.
    $s = "SELECT reminder_type_id FROM $tbl2 WHERE reminder_type_id = '".$reminder_type_id."' AND object_id = '".$object_id."' AND archived_status = 0";
    $h = mysqli_query($conn, $s);
    if(mysqli_num_rows($h) ==1)
    {
        //set existing record with archived_status = 0 to 1
        $s = "UPDATE $tbl2 SET archived_status = 1 WHERE reminder_type_id = '".$reminder_type_id."' AND object_id = '".$object_id."'";
        mysqli_query($conn, $s);
    }

    //insert into tbl_object_expiration
    $s1     = "INSERT INTO $tbl2 SET reminder_type_id = '".$reminder_type_id."', object_id = '".$object_id."', description = '".$description."', start_date = '".$start_date."', expired_date = ".$exp_date.", created_at = UTC_TIMESTAMP(), user_id = '".$_SESSION['user_id']."', client_id = '".$_SESSION['client_id']."'";
    mysqli_query($conn,$s1);
    $last_id = mysqli_insert_id($conn);

    //insert into tbl_object_file
    $s2 = "INSERT INTO $tbl3 SET object_expiration_id = '".$last_id."', file_name = '".$file_name."', client_id = '".$_SESSION['client_id']."', user_id = '".$_SESSION['user_id']."', created_at = UTC_TIMESTAMP()";
    mysqli_query($conn, $s2);

    // add log
    addLog($conn, $_SESSION['user_id'],'ADD OBJECT EXPIRATION DATA', 'add id: '.$last_id);
    $object_id          =   Encryption::encode($object_id);

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'objectsDetail?$object_id';
    });</script>";
    exit();
}


//this is to determine who can access read or write
if($param[1]=='access')
{
  
    $id         =   input_data(Encryption::decode($_POST['id']));
    $user_id    =   input_data($_POST['user_id']);
    $access     =   input_data($_POST['access']);

    mysqli_query($conn, "DELETE FROM tbl_object_access WHERE user_id = '".$_POST['user_id']."' LIMIT 1");
    $s  = "INSERT INTO tbl_object_access(object_id, user_id, client_id, access, created_at) VALUES ('".$id."', '".$user_id."', '".$_SESSION['client_id']."', '".$access."', UTC_TIMESTAMP())";
            mysqli_query($conn,$s);

    // add log
    addLog($conn, $_SESSION['user_id'],'MANAGE ACCESS', 'given id: '.$user_id);
    $object_id          =   Encryption::encode($id);            

    echo "<script>Swal.fire({
        icon: 'success',
        text: '$notif_data_success',
    }).then(function() {
        window.location = 'objectsEdit?$object_id';
    });</script>";
    exit();
}

//this is to delete or revoke access
if($param[1]=='removeAccess')
{

    $id         =   input_data(Encryption::decode($param['2']));
    $user_id    =   input_data(Encryption::decode($param['3']));

    mysqli_query($conn, "DELETE FROM tbl_object_access WHERE user_id = '".$user_id."' AND object_id = '".$id."' LIMIT 1");

    // add log
    addLog($conn, $_SESSION['user_id'],'REVOKE ACCESS', 'revoke id: '.$user_id);
    $object_id          =   Encryption::encode($id);            

    echo "<script>";
    echo "alert('Remove success'); window.location.href=history.back()";
    echo "</script>";
    exit();

}