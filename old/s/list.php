<?php
require_once 'inc/header.php';

$title = base64_decode($param[1]);
if($title == $module_employee)
{
  $th = '
  <th>Employee Name</th>
  <th>Email</th>
  <th>Phone</th>
  <th>Position</th>
  <th>Active Status</th>
  <th>Action</th>';

  $columns = '
  { "data": "name" },
  { "data": "email" },
  { "data": "phone" },
  { "data": "position" },
  { "data": "active_status" },
  { "data": "aksi" }';

  $add_new = '
  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">Position</label>
      <input type="text" class="form-control" name="position" />
    </div>
  </div>

  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">Email</label>
      <input type="text" class="form-control" name="email" />
    </div>
  </div>

  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">Phone</label>
      <input type="text" class="form-control" name="phone" />
    </div>
  </div>';

}

if($title == $module_vehicle)
{
  $th = '
  <th>Vehicle Name</th>
  <th>Type</th>
  <th>Year</th>
  <th>Plate Number</th>
  <th>Active Status</th>
  <th>Action</th>';

  $columns = '
  { "data": "name" },
  { "data": "vehicle_type" },
  { "data": "vehicle_year" },
  { "data": "vehicle_plate" },
  { "data": "active_status" },
  { "data": "aksi" }';

  $add_new = '
  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">Vehicle Plate</label>
      <input type="text" class="form-control" name="vehicle_plate" />
    </div>
  </div>

  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">Vehicle Type</label>
      <input type="text" class="form-control" name="vehicle_type" />
    </div>
  </div>

  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">Year</label>
      <input type="text" class="form-control" name="vehicle_year" />
    </div>
  </div>';
}

if($title == $module_vendors || $title == $module_clients)
{
  $th = '
  <th>Name</th>
  <th>Email</th>
  <th>Phone</th>
  <th>Active Status</th>
  <th>Action</th>';

  $columns = '
  { "data": "name" },
  { "data": "email" },
  { "data": "phone" },
  { "data": "active_status" },
  { "data": "aksi" }';

  $add_new = '
  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">Address'.$important_star.'</label>
      <input type="text" class="form-control" name="address" />
    </div>
  </div>

  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">City'.$important_star.'</label>
      <input type="text" class="form-control" name="city" />
    </div>
  </div>

  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">Email'.$important_star.'</label>
      <input type="text" class="form-control" name="email" />
    </div>
  </div>

  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">Phone'.$important_star.'</label>
      <input type="text" class="form-control" name="phone" />
    </div>
  </div>';

}

if($title == $module_electronics)
{
  $th = '
  <th>Device Name</th>
  <th>Type</th>
  <th>Serial Number</th>
  <th>Active Status</th>
  <th>Action</th>';

  $columns = '
  { "data": "name" },
  { "data": "electronic_type" },
  { "data": "serial_number" },
  { "data": "active_status" },
  { "data": "aksi" }';

  $add_new = '
  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">Serial Number</label>
      <input type="text" class="form-control" name="serial_number" />
      <span class="mt-2 d-block">Can be a product code</span>
    </div>
  </div>

  <div class="col-lg-12">
    <div class="form-group">
      <label for="com-name">Device Type</label>
      <input type="text" class="form-control" name="electronic_type" />
    </div>
  </div>';
}

if($title == $module_domain)
{
  $th = '
  <th>Provider</th>
  <th>Start Date</th>
  <th>Renewal Date</th>
  <th>Active Status</th>
  <th>Action</th>';

  $columns = '
  { "data": "name" },
  { "data": "start_date" },
  { "data": "expired_date" },
  { "data": "active_status" },
  { "data": "aksi" }';

  $add_new = '
  <div class="col-lg-12">
    <div class="form-group">
      <br/>
      <p>Click on button "Check Expiry Date" before saving the data</p>
      <a href="#" onClick="change(this)" id="checkDomain" class="btn btn-info">Check Expiry Date</a>
    </div>
  </div>';
}

$array_menu = array($module_domain);
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
              <h3 class="card-title"><?php echo $title; ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add"> Add</button>
                <?php if($title <> $module_domain) { ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add2"> Upload</button>
                <?php } ?>
              </h3>  
            </div>
            <div class="card-body">
              <table id="example1" class="table table-hover table-product">
                <thead>
                <tr>
                  <?php echo $th ?>
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
               "url": "data/list?<?php echo $param[1]; ?>",
               "dataType": "json",
               "type": "POST"
             },
      
        columns: [ <?php echo $columns ?>],

      buttons: ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>


<script src="../assets/jqs/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#checkDomain").click(function(){
    var getVal = $("#name").val();
    console.log(getVal)
    $('#domainBox').load('inc/checkDomain.php?id='+ getVal);
  });
});

$('form#id').submit(function(){
  $(this).find(':input[type=submit]').prop('disabled', true);
});
</script>

<!-- Add Contact Button  -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form action="listAction?add" method="POST" id="id">
        <input type="hidden" name="param1" value="<?php echo $param[1] ?>" />
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Data</h5>
        </div>
        <div class="modal-body px-4">

          <div class="row mb-2">

          <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName"><?php echo $title.' NAME'.$important_star ?></label>
                <input type="text" class="form-control" name="name" id="name" />
                <div id="domainBox"></div>
              </div>
            </div>

          <?php echo $add_new ?>
          
        </div>
        <div class="modal-footer px-4">
          <?php echo $btn_cancel.$btn_save //this is button ?>         
        </div>
      </form>
    </div>
  </div>
</div></div>



<!-- Add Contact Button  -->
<div class="modal fade" id="modal-add2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form id="export_excel" method="POST" action="listUpload" enctype="multipart/form-data">
        <input type="hidden" name="type" value="<?php echo Encryption::encode($title) ?>" />
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Data</h5>
        </div>
        <div class="modal-body px-4">

        <div class="row mb-2">
            <div id="results"></div>
            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Select .xlsx file<?php echo $important_star ?></label>
                <input type="file" class="btn info" name="excel_file" id="excel_file" />
              </div>
            </div>
            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Make sure the format for excel file is similar with sample file here:
                    <a target="_blank" href="../sample_file/<?php echo strtolower($title) ?>.xlsx">download sample file</a>.<br/>
                    Otherwise, data will not stored in database.
                </label>
              </div>
            </div>
        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-smoke btn-pill" data-dismiss="modal">Cancel</button>
          <input type="submit" class="btn btn-primary btn-pill" value="Save" />
        </div>
      </form>
    </div>
  </div>
</div></div>

<?php require_once 'inc/footer.php' ?>