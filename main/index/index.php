<?php
require_once 'header.php';
?>

<div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                  Overview
                </div>
                <h2 class="page-title">
                  Dashboard
                </h2>
              </div>
            </div>
          </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
          <div class="container-xl">
            <div class="row">
              <div class="col-12 col-md-6 col-lg">
                <h2 class="mb-3 text-primary">All Expiration Data</h2>
                <div class="mb-4">
                  <div class="row row-cards">
     
                    <?php
                    $s = "SELECT archived_status, date_format(start_date, '%M %d, %Y') as start_date, date_format(expired_date, '%b %d, %Y') as expired_date, datediff(expired_date, CURDATE()) difference, object_id, a.reminder_type_id, name, category_master_name, reminder_type_name FROM tbl_object_expiration a INNER JOIN tbl_object b USING (object_id) INNER JOIN tbl_reminder_type c ON a.reminder_type_id = c.reminder_type_id INNER JOIN tbl_category_master d ON c.category_master_id = d.category_master_id WHERE a.client_id = '".$_SESSION['client_id']."' AND archived_status = 0 ORDER BY object_expiration_id DESC LIMIT 10";
                    $h = mysqli_query($conn, $s);
                    if(mysqli_num_rows($h) == 0)
                    {
                        echo '<span class="text-primary list-group-item">Yeayy! No data for this column.</span>';
                    }else{
                      while($r = mysqli_fetch_assoc($h))
                      {
                    ?>

                    <div class="col-12">
                      <div class="card card-sm">
                        <div class="card-status-top bg-primary"></div>
                        <div class="card-body">
                          <h3 class="card-title"><?php echo $r['name'] ?></h3>
                          <div class="text-secondary"><?php echo $r['category_master_name'].' &raquo; '.$r['reminder_type_name'] ?></div>
                          <div class="mt-4">
                            <div class="row">
                              <div class="col-auto">
                                <a href="#" class="link-primary">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                  <?php echo $r['expired_date'] ?>
                                </a>
                              </div>
                              <div class="col-auto text-primary">
                                <a href="objectsDetail?<?php echo Encryption::encode($r['object_id']) ?>?<?php echo Encryption::encode($r['reminder_type_id']) ?>?<?php echo Encryption::encode($r['reminder_type_name']) ?>">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path></svg>
                                  view detail
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php }} ?>

                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg">
                <h2 class="mb-3 text-green">This Month's Expired</h2>
                <div class="mb-4">
                  <div class="row row-cards">
                    
                    <?php
                    $s = "SELECT archived_status, date_format(start_date, '%M %d, %Y') as start_date, date_format(expired_date, '%b %d, %Y') as expired_date, datediff(expired_date, CURDATE()) difference, object_id, a.reminder_type_id, name, category_master_name, reminder_type_name FROM tbl_object_expiration a INNER JOIN tbl_object b USING (object_id) INNER JOIN tbl_reminder_type c ON a.reminder_type_id = c.reminder_type_id INNER JOIN tbl_category_master d ON c.category_master_id = d.category_master_id WHERE a.client_id = '".$_SESSION['client_id']."' AND month(expired_date) = month(now()) AND year(expired_date) = year(now()) AND archived_status = 0 ORDER BY object_expiration_id DESC LIMIT 10";
                    $h = mysqli_query($conn, $s);
                    if(mysqli_num_rows($h) == 0)
                    {
                        echo '<span class="text-primary list-group-item">Yeayy! No data for this column.</span>';
                    }else{
                      while($r = mysqli_fetch_assoc($h))
                      {
                    ?>

                    <div class="col-12">
                      <div class="card card-sm">
                        <div class="card-status-top bg-green"></div>
                        <div class="card-body">
                          <h3 class="card-title"><?php echo $r['name'] ?></h3>
                          <div class="text-secondary"><?php echo $r['category_master_name'].' &raquo; '.$r['reminder_type_name'] ?></div>
                          <div class="mt-4">
                            <div class="row">
                              <div class="col-auto">
                                <a href="#" class="link-green">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                  <?php echo $r['expired_date'] ?>
                                </a>
                              </div>
                              <div class="col-auto text-green">
                                <a href="objectsDetail?<?php echo Encryption::encode($r['object_id']) ?>?<?php echo Encryption::encode($r['reminder_type_id']) ?>?<?php echo Encryption::encode($r['reminder_type_name']) ?>">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path></svg>
                                  view detail
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php }} ?>

                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg">
                <h2 class="mb-3 text-warning">This Week's Expired</h2>
                <div class="mb-4">
                  <div class="row row-cards">
                    
                    <?php
                    $s = "SELECT archived_status, date_format(start_date, '%M %d, %Y') as start_date, date_format(expired_date, '%b %d, %Y') as expired_date, datediff(expired_date, CURDATE()) difference, object_id, a.reminder_type_id, name, category_master_name, reminder_type_name FROM tbl_object_expiration a INNER JOIN tbl_object b USING (object_id) INNER JOIN tbl_reminder_type c ON a.reminder_type_id = c.reminder_type_id INNER JOIN tbl_category_master d ON c.category_master_id = d.category_master_id WHERE a.client_id = '".$_SESSION['client_id']."' AND month(expired_date) = month(now()) AND year(expired_date) = year(now()) AND YEARWEEK(expired_date, 1) = YEARWEEK(CURDATE(), 1) AND archived_status = 0 ORDER BY object_expiration_id DESC LIMIT 10";
                    $h = mysqli_query($conn, $s);
                    if(mysqli_num_rows($h) == 0)
                    {
                        echo '<span class="text-primary list-group-item">Yeayy! No data for this column.</span>';
                    }else{
                      while($r = mysqli_fetch_assoc($h))
                      {
                    ?>

                    <div class="col-12">
                      <div class="card card-sm">
                        <div class="card-status-top bg-warning"></div>
                        <div class="card-body">
                          <h3 class="card-title"><?php echo $r['name'] ?></h3>
                          <div class="text-secondary"><?php echo $r['category_master_name'].' &raquo; '.$r['reminder_type_name'] ?></div>
                          <div class="mt-4">
                            <div class="row">
                              <div class="col-auto">
                                <a href="#" class="link-warning">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                  <?php echo $r['expired_date'] ?>
                                </a>
                              </div>
                              <div class="col-auto text-warning">
                                <a href="objectsDetail?<?php echo Encryption::encode($r['object_id']) ?>?<?php echo Encryption::encode($r['reminder_type_id']) ?>?<?php echo Encryption::encode($r['reminder_type_name']) ?>">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path></svg>
                                  view detail
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php }} ?>

                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg">
                <h2 class="mb-3 text-danger">Past Due!</h2>
                <div class="mb-4">
                  <div class="row row-cards">

                    <?php
                    $s = "SELECT archived_status, date_format(start_date, '%M %d, %Y') as start_date, date_format(expired_date, '%b %d, %Y') as expired_date, datediff(expired_date, CURDATE()) difference, object_id, a.reminder_type_id, name, category_master_name, reminder_type_name FROM tbl_object_expiration a INNER JOIN tbl_object b USING (object_id) INNER JOIN tbl_reminder_type c ON a.reminder_type_id = c.reminder_type_id INNER JOIN tbl_category_master d ON c.category_master_id = d.category_master_id WHERE a.client_id = '".$_SESSION['client_id']."' AND date(expired_date) < curdate() AND archived_status = 0 ORDER BY object_expiration_id DESC LIMIT 10";
                    $h = mysqli_query($conn, $s);
                    if(mysqli_num_rows($h) == 0)
                    {
                        echo '<span class="text-primary list-group-item">Yeayy! No data for this column.</span>';
                    }else{
                      while($r = mysqli_fetch_assoc($h))
                      {
                    ?>

                    <div class="col-12">
                      <div class="card card-sm">
                        <div class="card-status-top bg-danger"></div>
                        <div class="card-body">
                          <h3 class="card-title"><?php echo $r['name'] ?></h3>
                          <div class="text-secondary"><?php echo $r['category_master_name'].' &raquo; '.$r['reminder_type_name'] ?></div>
                          <div class="mt-4">
                            <div class="row">
                              <div class="col-auto">
                                <a href="#" class="link-danger">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                  <?php echo $r['expired_date'] ?>
                                </a>
                              </div>
                              <div class="col-auto text-danger">
                                <a href="objectsDetail?<?php echo Encryption::encode($r['object_id']) ?>?<?php echo Encryption::encode($r['reminder_type_id']) ?>?<?php echo Encryption::encode($r['reminder_type_name']) ?>">
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path></svg>
                                  view detail
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php }} ?>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    
<?php require_once 'footer.php' ?>

<script src="../../assets/libs/apexcharts/dist/apexcharts.min.js?1692870487" defer></script>
<?php
//this is to show month on bar chart
$month = '';
for($m=1; $m<=date('m'); ++$m){
  $month .= "'".date('F', mktime(0, 0, 0, $m, 1))."',";
}
$month = substr($month,0,-1);
?>
<script>
      // @formatter:off
      document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('chart-mentions'), {
      		chart: {
      			type: "bar",
      			fontFamily: 'inherit',
      			height: 240,
      			parentHeightOffset: 0,
      			toolbar: {
      				show: false,
      			},
      			animations: {
      				enabled: false
      			},
      			stacked: true,
      		},
      		plotOptions: {
      			bar: {
      				columnWidth: '50%',
      			}
      		},
      		dataLabels: {
      			enabled: false,
      		},
      		fill: {
      			opacity: 1,
      		},
      		series: [{
      			name: "Order",
      			data: [<?php echo $dataOrder ?>]
      		}],
      		tooltip: {
      			theme: 'dark'
      		},
      		grid: {
      			padding: {
      				top: -20,
      				right: 0,
      				left: -4,
      				bottom: -4
      			},
      			strokeDashArray: 4,
      			xaxis: {
      				lines: {
      					show: true
      				}
      			},
      		},
      		xaxis: {
      			labels: {
      				padding: 0,
      			},
      			tooltip: {
      				enabled: false
      			},
      			axisBorder: {
      				show: false,
      			},
      			type: 'text',
      		},
      		yaxis: {
      			labels: {
      				padding: 4
      			},
      		},
      		labels: [
      		  <?php echo $month; ?>
      		],
      		colors: [tabler.getColor("primary"), tabler.getColor("primary", 0.8), tabler.getColor("green", 0.8)],
      		legend: {
      			show: false,
      		},
      	})).render();
      });
      // @formatter:on
    </script>