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
              <h3 class="card-title">Employee <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add"> Add</button> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add2"> Upload</button></h3>  
            </div>
            <div class="card-body">
              <table id="example1" class="table table-hover table-product">
                <thead>
                <tr>
                  <th>Employee Name</th>
                  <th>Email</th>
                  <th>Designation</th>
                  <th>Active Status</th>
                  <th>Action</th>
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
               "url": "data/employee",
               "dataType": "json",
               "type": "POST"
             },
      columns: [
          { "data": "full_name" },
          { "data": "email" },
          { "data": "designation" },
          { "data": "active_status" },
          { "data": "aksi" }
      ],
      buttons: ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>




<!-- Add Contact Button  -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form action="employeeCertificateAction?add" method="POST">
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Data</h5>
        </div>
        <div class="modal-body px-4">

          <div class="row mb-2">

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Certificate Name<?php echo $important_star ?></label>
                <input type="text" class="form-control" name="certificate_name" />
                <span class="mt-2 d-block">Example: CPR, MCSE, Google Certified, CPA and else.</span>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">What is this certificate about?</label>
                <input type="text" class="form-control" name="certificate_description" />
                <span class="mt-2 d-block">Write a short description about this certificate (optional).</span>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">How long is the expiration date?<?php echo $important_star ?></label>
                <input type="text" class="form-control" name="certificate_duration" />
                <span class="mt-2 d-block">Write it in day(s).</span>
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
