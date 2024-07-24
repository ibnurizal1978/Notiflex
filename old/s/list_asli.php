<?php require_once 'inc/header.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?php $title = base64_decode($param[1]); echo $title; ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add"> Add</button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add2"> Upload</button>
              </h3>  
            </div>
            <div class="card-body">
              <table id="example1" class="table table-hover table-product">
                <thead>
                <tr>
                  <?php if($title == 'EMPLOYEE') { ?>
                    <th>Employee Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Active Status</th>
                    <th>Action</th>
                  <?php } if($title == 'VEHICLE') { ?>
                    <th>Vehicle Name</th>
                    <th>Type</th>
                    <th>Year</th>
                    <th>Plate Number</th>
                    <th>Active Status</th>
                    <th>Action</th>
                  <?php } if($title == 'VENDORS' || $title == 'CLIENTS')  { ?>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Active Status</th>
                    <th>Action</th>
                  <?php } ?>
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
      
        <?php if($title == 'EMPLOYEE') { ?>
        columns: [
          { "data": "name" },
          { "data": "email" },
          { "data": "phone" },
          { "data": "position" },
          { "data": "active_status" },
          { "data": "aksi" }
        ],
        <?php } ?>

        <?php if($title == 'VEHICLE') { ?>
        columns: [
          { "data": "name" },
          { "data": "vehicle_type" },
          { "data": "vehicle_year" },
          { "data": "vehicle_plate" },
          { "data": "active_status" },
          { "data": "aksi" }
        ],
        <?php } ?>

        <?php if($title == 'VENDORS' || $title == 'CLIENTS') { ?>
        columns: [
          { "data": "name" },
          { "data": "email" },
          { "data": "phone" },
          { "data": "active_status" },
          { "data": "aksi" }
        ],
        <?php } ?>

      buttons: ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>




<!-- Add Contact Button  -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form action="listAction?add" method="POST">
        <input type="hidden" name="param1" value="<?php echo $param[1] ?>" />
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Data</h5>
        </div>
        <div class="modal-body px-4">

          <div class="row mb-2">

          <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Name<?php echo $important_star ?></label>
                <input type="text" class="form-control" name="name" />
                <span class="mt-2 d-block">Special character are not allowed.</span>
              </div>
            </div>

          <?php if(base64_decode($param[1]) == 'EMPLOYEE') { ?>
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
            </div>
            <?php } ?>

            <?php if(base64_decode($param[1]) == 'CLIENTS' || base64_decode($param[1]) == 'VENDORS') { ?>
            <div class="col-lg-12">
              <div class="form-group">
                <label for="com-name">Address</label>
                <input type="text" class="form-control" name="address" />
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
                <label for="com-name">City</label>
                <input type="text" class="form-control" name="city" />
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
            </div>
            <?php } ?>

            <?php if(base64_decode($param[1]) == 'VEHICLE') { ?>
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
            </div>
            <?php } ?>

        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-smoke btn-pill" data-dismiss="modal">Cancel</button>
          <input type="submit" class="btn btn-primary btn-pill" value="Save" />
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