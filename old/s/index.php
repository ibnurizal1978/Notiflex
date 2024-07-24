<?php
require_once 'inc/header.php';
include '../assets/plugins/calendar/calendar.php';

$calendar = new Calendar();
//$calendar->add_event('Birthday', '2023-05-03', 1, 'green');
//$calendar->add_event('Doctors', '2023-05-04', 1, 'red');
//$calendar->add_event('Holiday', '2023-05-16', 2);

$s = "SELECT category_name, expired_date, datediff(expired_date, CURDATE()) difference FROM tbl_list_expiration a INNER JOIN tbl_category b USING (category_id) WHERE month(expired_date) = month(now()) AND year(expired_date) = year(now()) AND archived_status = 0 AND a.client_id = '".$_SESSION['client_id']."'";
$h = mysqli_query($conn, $s);
while($r = mysqli_fetch_assoc($h))
{
    if($r['difference']<10)
    {
        $color = 'red';
    }else{
        $color = 'yellow';
    }

    $calendar->add_event('<a href=godeg class="text-white">'.$r['category_name'].'</a>', $r['expired_date'], 1, $color);
}


$s1 = "SELECT count(list_expiration_id) as total FROM tbl_list_expiration WHERE date(expired_date) >= curdate() AND archived_status = 0 AND client_id = '".$_SESSION['client_id']."'";
$h1 = mysqli_query($conn, $s1);
$r1 = mysqli_fetch_assoc($h1);

$s2 = "SELECT count(list_expiration_id) as total FROM tbl_list_expiration WHERE date(expired_date) = curdate() AND archived_status = 0 AND client_id = '".$_SESSION['client_id']."'";
$h2 = mysqli_query($conn, $s2);
$r2 = mysqli_fetch_assoc($h2);

$s3 = "SELECT count(list_expiration_id) as total FROM tbl_list_expiration WHERE month(expired_date) = month(now()) AND year(expired_date) = year(now()) AND archived_status = 0 AND client_id = '".$_SESSION['client_id']."'";
$h3 = mysqli_query($conn, $s3);
$r3 = mysqli_fetch_assoc($h3);

$s4 = "SELECT count(list_expiration_id) as total FROM tbl_list_expiration WHERE date(expired_date) < curdate() AND archived_status = 0 AND client_id = '".$_SESSION['client_id']."'";
$h4 = mysqli_query($conn, $s4);
$r4 = mysqli_fetch_assoc($h4);
?>
<link href="../assets/plugins/calendar/calendar.css" rel="stylesheet" type="text/css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
  
            <!-- Top Statistics -->
            <div class="row">
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-default card-mini text-white bg-success">
                        <a href="listExpiration?total">
                            <div class="card-header">
                                <h2><?php echo $r1['total'] ?></h2>
                                <div class="sub-title">
                                    <span class="mr-1"><b>Total Expiration Data</b></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-default card-mini text-white bg-info">
                        <a href="listExpiration?today">
                            <div class="card-header">
                                <h2><?php echo $r2['total'] ?></h2>
                                <div class="sub-title">
                                    <span class="mr-1"><b>Expired Today</b></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-default card-mini text-white bg-warning">
                        <a href="listExpiration?month">
                            <div class="card-header">
                                <h2><?php echo $r3['total'] ?></h2>
                                <div class="sub-title">
                                    <span class="mr-1"><b>Expired in this Month</b></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card card-default card-mini text-white bg-danger">
                        <a href="listExpiration?expired">
                            <div class="card-header">
                                <h2><?php echo $r4['total'] ?></h2>
                                <div class="sub-title">
                                    <span class="mr-1"><b>Already Expired!</b></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!--end statistic-->
            <hr/>

            <div class="row">
                <div class="text-center"><h1>Expiration in this Month</h1></div>
			    <?=$calendar?>
		    </div>
        </div>
    </section>

<?php require_once 'inc/footer.php'; ?>