<?php
session_start();
$_SESSION['user_time']=time();

function isSessionExpired()
{
   if(time()-$_SESSION['user_time']>(15*60))
   { 
		header("Location: ../../logout?s=2");
   }
}

if($_SESSION['user_id'] == '') {
	header("Location: ../../logout?s=2");
}
?>