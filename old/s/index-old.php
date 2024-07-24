<?php require_once 'inc/header.php' ?>
<script type="text/javascript" src="../assets/plugins/chartjs/Chart.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">

        <div class="col-xl-12">
          <div class="card card-default">
            <div class="card-header">
              <h2>This Month Revenue</h2>
            </div>
            <div class="card-body">
              <div class="chart-wrapper">
                <canvas id="chart1"></canvas>
                <?php
                $product_name = "";
                $qty          = "";
                $s1 = "SELECT date(check_out_date) as month, sum(sub_total_after_tax) as total FROM `tbl_order_detail` a INNER JOIN tbl_order b USING (order_code) WHERE order_status <> 'IN CART' GROUP BY date(check_out_date) ORDER BY date(check_out_date)";
                $h1 = mysqli_query($conn, $s1);
                while($r1 = mysqli_fetch_assoc($h1))
                {
                  $product_name .= '"'.$r1['month'].'",';
                  $qty          .= $r1['total'].",";
                }
                $product_name = substr($product_name,0,-1);
                $qty          = substr($qty,0,-1);
                ?>

                <script>
              		var ctx = document.getElementById("chart1").getContext('2d');
              		var myChart = new Chart(ctx, {
              			type: 'line',
              			data: {
              				labels: [<?php echo $product_name ?>],
              				datasets: [{
              					label: '',
              					data: [<?php echo $qty ?>],
              					backgroundColor: [
              					'rgba(54, 162, 235, 0.2)',
              					'rgba(51, 162, 235, 0.2)',
              					'rgba(255, 206, 86, 0.2)',
              					'rgba(75, 192, 192, 0.2)'
              					],
              					borderColor: [
              					'rgba(54, 162, 235,1)',
              					'rgba(51, 162, 235, 1)',
              					'rgba(255, 206, 86, 1)',
              					'rgba(75, 192, 192, 1)'
              					],
              					borderWidth: 1
              				}]
              			},
              			options: {
                      responsive: true,
              				scales: {
              					yAxes: [{
              						ticks: {
              							beginAtZero:true
              						}
              					}]
              				}
              			}
              		});
              	</script>

              </div>
            </div>
          </div>
        </div>


        <div class="col-lg-6">
          <div class="card">
            <div class="card-header border-0">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Top 5 Sold Products</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="d-flex">
                <canvas id="chart2"></canvas>
                <?php
                $product_name = "";
                $qty          = "";
                $s1 = "SELECT product_name, sum(qty) as qty FROM `tbl_order_detail` a INNER JOIN tbl_order b USING (order_code) WHERE order_status <> 'IN CART' GROUP BY product_id ORDER BY qty desc LIMIT 5";
                $h1 = mysqli_query($conn, $s1);
                while($r1 = mysqli_fetch_assoc($h1))
                {
                  $product_name .= '"'.$r1['product_name'].'",';
                  $qty          .= $r1['qty'].",";
                }
                $product_name = substr($product_name,0,-1);
                $qty          = substr($qty,0,-1);
                ?>

                <script>
              		var ctx = document.getElementById("chart2").getContext('2d');
              		var myChart = new Chart(ctx, {
              			type: 'horizontalBar',
              			data: {
              				labels: [<?php echo $product_name ?>],
              				datasets: [{
              					label: '',
              					data: [<?php echo $qty ?>],
              					backgroundColor: [
              					'rgba(255, 99, 132, 0.2)',
              					'rgba(54, 162, 235, 0.2)',
              					'rgba(255, 206, 86, 0.2)',
                        'rgba(225, 176, 86, 0.2)',
              					'rgba(75, 192, 192, 0.2)'
              					],
              					borderColor: [
              					'rgba(255,99,132,1)',
              					'rgba(54, 162, 235, 1)',
              					'rgba(255, 206, 86, 1)',
                        'rgba(225, 176, 86, 1)',
              					'rgba(75, 192, 192, 1)'
              					],
              					borderWidth: 1
              				}]
              			},
              			options: {
              				scales: {
              					yAxes: [{
              						ticks: {
                            min: 10,
              							beginAtZero:true
              						}
              					}]
              				}
              			}
              		});
              	</script>
              </div>
            </div>
          </div>
          <!-- /.card -->
          <br/>
          <div class="card">
            <div class="card-header border-0">
              <h3 class="card-title">Top 5 Outstanding Payment</h3>
            </div>
            <div class="card-body table-responsive p-0">
              <?php
              $s3 = "SELECT business_name, sum(sub_total_after_tax) as total FROM tbl_order_detail a INNER JOIN tbl_order b USING (order_code) INNER JOIN tbl_client c ON c.client_id = a.client_id WHERE payment_status <> 'PAID' GROUP BY a.client_id ORDER BY total DESC";
              $h3 = mysqli_query($conn, $s3);
              if(mysqli_num_rows($h3) > 0)
              {
              ?>
              <table class="table table-striped table-valign-middle">
                <thead>
                <tr>
                  <th>Business Name</th>
                  <th class="text-right">Total Outstanding</th>
                </tr>
                </thead>
                <tbody>
                <?php while($r3 = mysqli_fetch_assoc($h3)) { ?>
                <tr>
                  <td><?php echo $r3['business_name'] ?></td>
                  <td class="text-right"><?php echo $_SESSION['currency'].' '.number_format($r3['total'],0,",","."); ?></td>
                </tr>
                <?php } ?>
                </tbody>
              </table>
              <?php }else{ echo "Congratulations! No outstanding payment from your buyer"; } ?>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col-md-6 -->
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header border-0">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Top 5 Buyer</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="d-flex">
                <canvas id="chart3"></canvas>
                <?php
                $product_name = "";
                $qty          = "";
                $s1 = "SELECT business_name, sum(order_id) as total FROM `tbl_order` a INNER JOIN tbl_client b USING (client_id) WHERE order_status <> 'IN CART' GROUP BY client_id ORDER BY total desc LIMIT 5";
                $h1 = mysqli_query($conn, $s1);
                while($r1 = mysqli_fetch_assoc($h1))
                {
                  $product_name .= '"'.$r1['business_name'].'",';
                  $qty          .= $r1['total'].",";
                }
                $product_name = substr($product_name,0,-1);
                $qty          = substr($qty,0,-1);
                ?>

                <script>
              		var ctx = document.getElementById("chart3").getContext('2d');
              		var myChart = new Chart(ctx, {
              			type: 'doughnut',
              			data: {
              				labels: [<?php echo $product_name ?>],
              				datasets: [{
              					label: '',
              					data: [<?php echo $qty ?>],
              					backgroundColor: [
              					'rgba(255, 99, 132, 0.2)',
              					'rgba(54, 162, 235, 0.2)',
              					'rgba(255, 206, 86, 0.2)',
                        'rgba(225, 176, 86, 0.2)',
              					'rgba(75, 192, 192, 0.2)'
              					],
              					borderColor: [
              					'rgba(255,99,132,1)',
              					'rgba(54, 162, 235, 1)',
              					'rgba(255, 206, 86, 1)',
                        'rgba(225, 176, 86, 1)',
              					'rgba(75, 192, 192, 1)'
              					],
              					borderWidth: 1
              				}]
              			},
              			options: {
              				scales: {
              					yAxes: [{
                          gridLines: {
                              display:false
                          },
              						ticks: {
                            min: 10,
              							beginAtZero:true
              						}
              					}]
              				}
              			}
              		});
              	</script>
              </div>
            </div>
          </div>

          <br/>
          <div class="card">
            <div class="card-header border-0">
              <h3 class="card-title">Top 5 Stock</h3>
            </div>
            <div class="card-body table-responsive p-0">
              <?php
              $s3 = "SELECT product_name, SUM(qty * CASE qty_action WHEN 'ADD' THEN 1 WHEN 'REMOVE' THEN -1 END) AS total FROM tbl_product_qty a INNER JOIN tbl_product b USING (product_id) WHERE a.client_id = '".$_SESSION['client_id']."' group by a.product_id";
              $h3 = mysqli_query($conn, $s3);
              if(mysqli_num_rows($h3) > 0)
              {
              ?>
              <table class="table table-striped table-valign-middle">
                <thead>
                <tr>
                  <th>Product Name</th>
                  <th class="text-right">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php while($r3 = mysqli_fetch_assoc($h3)) { ?>
                <tr>
                  <td><?php echo $r3['product_name'] ?></td>
                  <td class="text-right"><?php echo number_format($r3['total'],0,",","."); ?></td>
                </tr>
                <?php } ?>
                </tbody>
              </table>
              <?php }else{ echo "No qty at all"; } ?>
            </div>
          </div>

        </div>
        <!-- /.col-md-6 -->
       
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once 'inc/footer.php' ?>
