<?php
require_once 'inc/header.php';

$cat = $param[1];
switch ($cat) {
  case "total":
    $title = "Total Expiration Data";
    break;
  case "today":
    $title = "Expired Today";
    break;
  case "month":
    $title = "Expired In This Month";
    break;
  case "expired":
    $title = "Already Expired!";
    break;
  case $module_employee:
    $title = $module_employee;
    break;
  case $module_vehicle:
    $title = $module_vehicle;
    break;
  case $module_vendors:
    $title = $module_vendors;
    break;
  case $module_clients:
    $title = $module_clients;
    break;
  case $module_electronics:
    $title = $module_electronics;
    break;

  default:
  $title = "a";
    //echo "Your favorite color is neither red, blue, nor green!";
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Expiration List: <b class="text-info"><?php echo $title ?></a></h3>  
            </div>
            <div class="card-body">
              <table id="example1" class="table table-hover table-product">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Expiration Category</th>
                    <th>Start Date</th>
                    <th>Expired Date</th>
                    <th>Expired In</th>
                    <th>Held by</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>


<?php
require_once 'inc/footer.php';
require_once 'inc/data_table.php';
?>
<script>
  $(function () {
    $("#example1").DataTable({
      dom: 'Bfrtip',
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      lengthChange: false,
      ajax:{
               "url": "data/listExpiration?<?php echo $cat ?>",
               "dataType": "json",
               "type": "POST"
             },

            columns: [
            { "data": "name"},
            { "data": "category_name" },
            { "data": "start_date" },
            { "data": "expired_date" },
            { "data": "expired_in" },
            { "data": "held_by" },
            { "data": "aksi" }
            ],

      buttons: ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>

</div></div>

<?php require_once 'inc/footer.php' ?>