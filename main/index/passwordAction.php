<?php
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../../config.php";
require_once "../../checkSession.php";
require_once "../../components.php";
//require_once "access.php";

  
$current	=	input_data(md5($_POST['current']));
$password	=	input_data($_POST['password']);
if($current == "" || $password == "") {
    echo "<script>Swal.fire({
        icon: 'error',
        text: '$notif_data_empty',
    })</script>";
    exit();
}

// check if current password is correct?
$replacements = array(
	'1' => '2',
	'2' => '3',
	'3' => '4',
	'4' => '5',
	'5' => '6',
	'a' => 'b',
	'b' => 'c',
	'c' => 'd',
);
$txt_new_password 		= strtr($current, $replacements);

$s	= "select user_id from tbl_user WHERE substr(password,6,100) = '" . $txt_new_password . "' LIMIT 1";
$h	= mysqli_query($conn, $s);
if (mysqli_num_rows($h) == 0) {
    echo "<script>Swal.fire({
        icon: 'error',
        text: 'Current password is incorrect',
    })</script>";
    exit();
}

//now check the password method
function valid_pass($password) {
    if (!preg_match_all('$\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\s*$', $password))
        return FALSE;
    return TRUE;
}

if(!valid_pass($password)) {
    echo "<script>Swal.fire({
        icon: 'error',
        text: 'Password minimal 6 karakter dan harus berisi minimal 1 huruf besar, 1 huruf kecil',
    })</script>";
    exit();
}

if(strlen($password)<6) {
    echo "<script>Swal.fire({
        icon: 'error',
        text: 'Password minimal 6 karakter',
    })</script>";
    exit();
}

//generate new password
$replacements = array('1' => '2',
'2' => '3', 
'3' => '4',
'4' => '5',
'5' => '6',
'a' => 'b',
'b' => 'c',
'c' => 'd',
);
$capcay 			= substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxysABCDEFGHIJKLMNOPQRSTUVWXYZ", 5)), 0, 5);
$acak				= substr($capcay,0,6);
$txt_password2 		= $acak.md5($password);
$txt_new_password3 	= strtr($txt_password2,$replacements);

$s = "UPDATE tbl_user SET password ='".$txt_new_password3."', updated_at = UTC_TIMESTAMP() WHERE user_id = '".$_SESSION['user_id']."' LIMIT 1";
mysqli_query($conn, $s);

// add log
addLog($conn, $_SESSION['user_id'],'CHANGE PASSWORD', 'change password for user id: '.$_SESSION['user_id']);

echo "<script>Swal.fire({
    icon: 'success',
    text: '$notif_data_success',
}).then(function() {
    window.location = 'index';
});</script>";
exit();