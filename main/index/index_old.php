<?php
require_once 'header.php';

//query for row 1
$s1 = "SELECT count(client_id) as total FROM tbl_client WHERE owner_id = '".$_SESSION['client_id']."'";
$h1 = mysqli_query($conn, $s1);
$r1 = mysqli_fetch_assoc($h1);

//query for row 2
$s2 = "SELECT count(warehouse_id) as total FROM tbl_warehouse WHERE client_id = '".$_SESSION['client_id']."'";
$h2 = mysqli_query($conn, $s2);
$r2 = mysqli_fetch_assoc($h2);

//query for row 3
$s3 = "SELECT count(order_detail_id) as total FROM tbl_order_detail WHERE owner_id = '".$_SESSION['client_id']."'";
$h3 = mysqli_query($conn, $s3);
$r3 = mysqli_fetch_assoc($h3);

//query for row 4
$s4 = "SELECT count(order_detail_id) as total FROM tbl_order_detail WHERE order_detail_status = 'TERKIRIM' AND owner_id = '".$_SESSION['client_id']."'";
$h4 = mysqli_query($conn, $s4);
$r4 = mysqli_fetch_assoc($h4);

//report graph
$s5 = "SELECT count(order_detail_id) as total FROM tbl_order_detail WHERE owner_id = '".$_SESSION['client_id']."' AND year(order_detail_date) = YEAR(CURDATE()) GROUP BY month(order_detail_date)";
$h5 = mysqli_query($conn, $s5);
$dataOrder    = "";
while($r5     = mysqli_fetch_assoc($h5)) {
  $dataOrder .= $r5['total'].",";
}
$dataOrder    = substr($dataOrder,0,-1);
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
            <div class="row row-deck row-cards">     

              <div class="col-12">
                <!-- card for 4 row top -->
                <div class="row row-cards">
                  <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path></svg>
                            </span>
                          </div>
                          <div class="col">
                            <div class="font-weight-medium">
                              <?php echo $r1['total'].' buyers listed'; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <span class="bg-green text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-building-warehouse" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 21v-13l9 -4l9 4v13"></path><path d="M13 13h4v8h-10v-6h6"></path><path d="M13 21v-9a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1v3"></path></svg>
                            </span>
                          </div>
                          <div class="col">
                            <div class="font-weight-medium">
                            <?php echo $r2['total'].' warehouse listed'; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <span class="bg-purple text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M11.5 17h-5.5v-14h-2"></path><path d="M6 5l14 1l-1 7h-13"></path><path d="M15 19l2 2l4 -4"></path></svg>
                            </span>
                          </div>
                          <div class="col">
                            <div class="font-weight-medium">
                            <?php echo number_format($r3['total'],0,",",".").' order created'; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                      <div class="card-body">
                        <div class="row align-items-center">
                          <div class="col-auto">
                            <span class="bg-facebook text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box-seam" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5"></path><path d="M12 12l8 -4.5"></path><path d="M8.2 9.8l7.6 -4.6"></path><path d="M12 12v9"></path><path d="M12 12l-8 -4.5"></path></svg>
                            </span>
                          </div>
                          <div class="col">
                            <div class="font-weight-medium">
                            <?php echo number_format($r4['total'],0,",",".").' order sent'; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end card for row top -->
              </div>

              <!-- go to 2nd row -->
              <!-- top 5 buyer -->
              <div class="col-md-12 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Top 5 Buyer</h3>
                  </div>
                  <div class="table-responsive">
                    <table class="table card-table table-vcenter">
                      <?php
                      //top 5 buyer
                      $s = "SELECT count(order_detail_id) as total, client_business_name FROM tbl_order_detail a INNER JOIN tbl_client b USING (client_id) WHERE a.owner_id = '".$_SESSION['client_id']."' GROUP BY client_id ORDER BY total DESC LIMIT 5";
                      $h = mysqli_query($conn, $s);
                      while($r = mysqli_fetch_assoc($h))
                      {

                        $s2 = "SELECT count(order_detail_id) as total FROM tbl_order_detail WHERE owner_id = '".$_SESSION['client_id']."' AND order_detail_status = 'TERKIRIM' GROUP BY client_id ORDER BY total DESC LIMIT 5";
                        $h2 = mysqli_query($conn, $s2);
                        $r2 = mysqli_fetch_assoc($h2);
                      ?>
                      <tr>
                        <td class="w-100">
                          <a href="#" class="text-reset"><?php echo $r['client_business_name'] ?></a>
                        </td>
                        <td class="text-nowrap text-secondary">
                          <!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path><path d="M11.5 17h-5.5v-14h-2"></path><path d="M6 5l14 1l-1 7h-13"></path><path d="M15 19l2 2l4 -4"></path></svg>
                          <?php echo number_format($r['total'],0,",",".").' order'; ?>
                        </td>
                        <td class="text-nowrap text-success">
                          <!-- Download SVG icon from http://tabler-icons.io/i/check -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                          <?php echo number_format($r2['total'],0,",",".").' order sent'; ?>
                        </td>
                      </tr>
                      <?php } ?>
                    </table>
                  </div>
                </div>
              </div>
              <!-- end top 5 buyer -->

              <!-- graph order by month -->
              <div class="col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <h3 class="card-title">Order by Month</h3>
                    <div id="chart-mentions" class="chart-lg"></div>
                  </div>
                </div>
              </div>
              <!-- end graph order by month -->

              <!-- most ordered products -->
              <div class="col-md-6 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Top 5 Ordered Product</h3>
                  </div>
                  <table class="table card-table table-vcenter">
                    <thead>
                      <tr>
                        <th>Product</th>
                        <th colspan="2">Order Qty</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      //top 5 ordered products
                      $s = "SELECT count(order_detail_id) as total, product_name FROM tbl_order_detail WHERE owner_id = '".$_SESSION['client_id']."' GROUP BY product_id ORDER BY total DESC LIMIT 5";
                      $h = mysqli_query($conn, $s);
                      while($r = mysqli_fetch_assoc($h))
                      {
                      ?>
                      <tr>
                        <td><?php echo $r['product_name'] ?></td>
                        <td><?php echo number_format($r['total'],0,",",".") ?></td>
                        <td class="w-50">
                          <div class="progress progress-xs">
                            <div class="progress-bar bg-primary" style="width: <?php echo $r['total']/100 ?>%"></div>
                          </div>
                        </td>
                      </tr>
                     <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- end most ordered products -->

              <!-- ordered year by year -->
              <div class="col-md-6 col-lg-6">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Last 5 Years Order</h3>
                  </div>
                  <table class="table card-table table-vcenter">
                    <thead>
                      <tr>
                        <th>Year</th>
                        <th colspan="2">Order Qty</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      //ordered YnY last 5 years
                      $s = "SELECT count(order_detail_id) as total, date_format(order_detail_date, '%Y') as order_detail_date FROM tbl_order_detail WHERE owner_id = '".$_SESSION['client_id']."' GROUP BY date_format(order_detail_date, '%Y') ORDER BY date_format(order_detail_date, '%Y') DESC LIMIT 5";
                      $h = mysqli_query($conn, $s);
                      while($r = mysqli_fetch_assoc($h))
                      {
                      ?>
                      <tr>
                        <td><?php echo $r['order_detail_date'] ?></td>
                        <td><?php echo number_format($r['total'],0,",",".") ?></td>
                        <td class="w-50">
                          <div class="progress progress-xs">
                            <div class="progress-bar bg-primary" style="width: <?php echo $r['total']/1000 ?>%"></div>
                          </div>
                        </td>
                      </tr>
                     <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- end ordered year by year -->

              

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