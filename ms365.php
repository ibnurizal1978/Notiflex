<?php

$appid = "7fbce858-2bd3-452f-b401-200ed09f0e14";
$tennantid = "edf68141-0b67-423f-952b-e4bea4386d28";
$secret = "5bc650b8-fa36-445c-8789-f63bda6211ea";
$login_url ="https://login.microsoftonline.com/".$tennantid."/oauth2/v2.0/authorize";


session_start ();

$_SESSION['state']=session_id();

echo "<h1>MS OAuth2.0 Demo </h1><br>";

if (isset ($_SESSION['msatg'])){
   $domain = explode('@', $_SESSION["username"]);
   echo "<h2>Authenticated ".$_SESSION["uname"]." </h2><br> ";
   echo "<h2>ID ".$_SESSION["id"]." </h2><br> ";
   echo "<h2>company ID ".$_SESSION["company_id"]." </h2><br> ";
   echo "<h2>company name ".$_SESSION["company_name"]." </h2><br> ";
   echo "<h2>username ".$_SESSION["username"]." </h2><br> ";
   echo "<h2>domain ".$domain[1]." </h2><br> ";
   echo '<p><a href="?action=logout">Log Out</a></p>';
} //end if session

else   echo '<h2><p>You can <a href="?action=login">Log In</a> with Microsoft</p></h2>';

if ($_GET['action'] == 'login'){
   $params = array ('client_id' =>$appid,
      'redirect_uri' =>'https://getnotiflex.com/apps/ms365',
      'response_type' =>'token',
       'response_mode' =>'form_post',
      'scope' =>'https://graph.microsoft.com/User.Read',
      'state' =>$_SESSION['state']);
   header ('Location: '.$login_url.'?'.http_build_query ($params));
}

if (array_key_exists ('access_token', $_POST))
{
   $_SESSION['t'] = $_POST['access_token'];
   $t = $_SESSION['t'];

   $ch = curl_init ();
   curl_setopt ($ch, CURLOPT_HTTPHEADER, array ('Authorization: Bearer '.$t, 'Content-type: application/json'));
   curl_setopt ($ch, CURLOPT_URL, "https://graph.microsoft.com/v1.0/me/");
   curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
   $r1 = json_decode (curl_exec ($ch), 1);

   $ch2 = curl_init ();
   curl_setopt ($ch2, CURLOPT_HTTPHEADER, array ('Authorization: Bearer '.$t, 'Content-type: application/json'));
   curl_setopt ($ch2, CURLOPT_URL, "https://graph.microsoft.com/v1.0/organization/");
   //curl_setopt ($ch2, CURLOPT_URL, "https://graph.microsoft.com/v1.0/users/".$r1['id']);
   curl_setopt ($ch2, CURLOPT_RETURNTRANSFER, 1);
   $r2 = json_decode (curl_exec ($ch2), 1);


   if (array_key_exists ('error', $r1))
   {  
      var_dump ($r1['error']);    
      die();
   }else {
      $_SESSION['msatg'] = 1;  //auth and verified
      $_SESSION['uname'] = $r1["displayName"];
      $_SESSION['id'] = $r1["id"];
      $_SESSION['company_id'] = $r2['id'];
      $_SESSION['company_name'] = $r2->displayName;
      $_SESSION['username'] = $r1["mail"];
   }

   var_dump($r2);
   curl_close ($ch);
   //header ('Location: https://getnotiflex.com/apps/ms365'); // ini redirect kalau sukses login
}

if ($_GET['action'] == 'logout'){
   unset ($_SESSION['msatg']);
   header ('Location: https://getnotiflex.com/apps/ms365'); //saat logout beneran harus ke page logout atau login
}
?>
<a href='https://outlook.office365.com/owa/calendar/Pertemuanparaleluhurmataram@backup365.id/bookings/'>Schedule online</a>