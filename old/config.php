<?php
//set timezone jadi default
date_default_timezone_set('UTC');
$param = explode("?", $_SERVER['REQUEST_URI']);
$base_url = 'https://getnotiflex.com/';
//encrypt querystring
class Encryption{

    /**
    *
    *
    * ----------------------------------------------------
    * @param string
    * @return string
    *
    **/
    public static function safe_b64encode($string='') {
        $data = base64_encode($string);
        $data = str_replace(['+','/','='],['-','_',''],$data);
        return $data;
    }

    /**
    *
    *
    * -------------------------------------------------
    * @param string
    * @return string
    *
    **/
    public static function safe_b64decode($string='') {
        $data = str_replace(['-','_'],['+','/'],$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
    *
    *
    * ------------------------------------------------------------------------------------------
    * @param string
    * @return string
    *
    **/
    public static function encode($value=false){
        if(!$value) return false;
        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($iv_size);
        $crypttext = openssl_encrypt($value, 'aes-256-cbc', 'ayamgoreng', OPENSSL_RAW_DATA, $iv);
        return self::safe_b64encode($iv.$crypttext);
    }

    /**
    *
    *
    * ---------------------------------
    * @param string
    * @return string
    *
    **/
    public static function decode($value=false){
        if(!$value) return false;
        $crypttext = self::safe_b64decode($value);
        $iv_size = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($crypttext, 0, $iv_size);
        $crypttext = substr($crypttext, $iv_size);
        if(!$crypttext) return false;
        $decrypttext = openssl_decrypt($crypttext, 'aes-256-cbc', 'ayamgoreng', OPENSSL_RAW_DATA, $iv);
        return rtrim($decrypttext);
    }
}

/*===== FUNCTIONS ======*/
function input_data($data)
{
$filter = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));
return $filter;
}

function addLog($conn, $user_id, $action, $notes)
{
  $s = "INSERT INTO tbl_log SET user_id = '".$user_id."', created_at = UTC_TIMESTAMP(), action = '".$action."', notes = '".$notes."'";
  mysqli_query($conn, $s);
}


/* get file name */
$url = $_SERVER['PHP_SELF'];
$filename = pathinfo(parse_url($url, PHP_URL_PATH));
//ini kalau mau ambil nama file aja
$file 	= $filename['filename'];

//module name
$module_employee    = 'EMPLOYEE';
$module_clients     = 'CLIENTS';
$module_vehicle     = 'VEHICLE';
$module_vendors     = 'VENDORS';
$module_electronics = 'ELECTRONICS';
$module_tools       = 'TOOLS'; 
$module_server      = 'SERVER'; 
$module_domain      = 'DOMAIN'; 
$module_ssl         = 'SSL';
$module_software    = 'SOFTWARE';  

// parameter for popup Message
$data_empty     = 'Please fill form';
$data_duplicate = 'There is a duplicate data';
$data_success   = 'Successfully add or edit data';
$data_deleted   = 'Successfully deleting data';

//domain check api
$domain_url     = 'https://zozor54-whois-lookup-v1.p.rapidapi.com';
$rapid_host     = 'zozor54-whois-lookup-v1.p.rapidapi.com';
$rapid_key      = '02ec706f6bmsh1d7d428dc944f17p14eba7jsn55072fabdd38';

//etc
$important_star = '<b class=text-danger>*</b>';
$important_text = '<br/>The red mark (<b class=text-danger>*</b>) sign is mandatory<br/><br/>';

//button
$btn_save       = '<input type="submit" class="btn btn-primary btn-pill" value="Save" onclick="return confirm(\'Are you sure all data is correct? Click Cancel if you want to re-input data\');" />';
$btn_cancel     = '<button type="button" class="btn btn-smoke btn-pill" data-dismiss="modal">Cancels</button>';
$btn_update     = '<input type="submit" class="btn btn-primary mb-2 btn-pill" value="Update" onclick="return confirm(\'Are you sure all data is correct? Click Cancel if you want to re-input data\');" />';
$btn_close      = '<button type="button" class="btn btn-primary btn-pill" data-dismiss="modal">Close</button>';
$btn_renew      = '<input type="submit" class="btn btn-primary mb-2 btn-pill" value="Renew" onclick="return confirm(\'Are you sure all data is correct? Click Cancel if you want to re-input data\');" />';

//for email smtp
$smtp_secure_method = 'tls';
$smtp_host          = 'smtp.mailersend.net';
$smtp_username      = 'MS_xXxvmf@getnotiflex.com';
$smtp_password      = 'C0GpyJzciQuU5MLg';
$smtp_sender_email  = 'no-reply@getnotiflex.com';
$smtp_sender_name   = 'Notiflex';
$smtp_footer        = '<br/><br/><br/>Cheers,<br/>Your Notiflex Buddy.<br/><br/><hr/>Please, do not reply this email as this is an automatic system.<br/>Notiflex is a reminder system for warranty, contract, car maintenance and else. You may find us in <a href="https://getnotiflex.com">here</a>.';

//database
$host     = "192.168.10.3";
$username = "local";
$password = "Database@123";
$db       = "db_notiflex";
$conn     = new mysqli($host, $username, $password, $db);
//user: local@localhost, pass Database123;
?>
