<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['user_id']);
unset($_SESSION['nav_header']);
unset($_SESSION['nav_items']);
session_destroy();
if($_GET['s']==2) {
	header("Location: index?2");
}elseif($_GET['s']=='session') {
	header("Location: index?p=s");
}else{
	header("Location: index");
}
?>
