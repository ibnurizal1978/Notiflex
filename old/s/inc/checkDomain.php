<?php
session_start();
require_once "../../config.php";

$id             = input_data($_GET['id']);
$curl = curl_init();
curl_setopt_array($curl, [
CURLOPT_URL => $domain_url."?domain=".$id."&format=json&_forceRefresh=0",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => "",
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 30,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => "GET",
CURLOPT_HTTPHEADER => [
    "X-RapidAPI-Host: ".$rapid_url,
    "X-RapidAPI-Key: ".$rapid_key
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$data = json_decode($response);

if ($err)
{
    echo $err;
}elseif($data->expires == null)
{
    echo "<p class=text-danger>Did you type your domain name correctly? Otherwise, there is something wrong with the server. Please try again in a few minutes</p>";
}else{
    echo "<br/><br/><b><span class=text-success>Domain name:</span> ".$id."</b>";
    echo "<br/>Expired date: <b>".date("M d, Y", strtotime($data->created))."</b>";
    echo "<br/>Expired date: <b>".date("M d, Y", strtotime($data->expires))."</b>";
    echo '<br/><br/>If this data is correct, click "Save" button to continue. Otherwise type new domain name and click on button "Check Expiry Date".';
    echo "<input type=hidden name=start_date value = ".date("Y-m-d", strtotime($data->created))." />";
    echo "<input type=hidden name=expired_date value = ".date("Y-m-d", strtotime($data->expires))." />";
}
?>
