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
              <h3 class="card-title">Reminder Day <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add"> Add</button></h3>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-hover table-product">
                <thead>
                <tr>
                  <th>Category Name</th>
                  <th>Remind in (days)</th>
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
               "url": "data/categoryReminder",
               "dataType": "json",
               "type": "POST"
             },
      columns: [
          { "data": "category_detail_name" },
          { "data": "aksi" }
      ],
      buttons: ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>

<!-- Modal  -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form action="categoryReminderAction?add" method="POST">
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Data</h5>
        </div>
        <div class="modal-body px-4">

          <div class="row mb-2">

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Category Name<?php echo $important_star ?></label>
                <select class="form-control" name="category_detail_id">
                 <?php
                 $sc = "SELECT category_detail_id, category_detail_name FROM tbl_category_detail WHERE client_id = '".$_SESSION['client_id']."' ORDER BY category_detail_name";
                 $hc = mysqli_query($conn, $sc);
                 while($rc = mysqli_fetch_assoc($hc))
                 {
                 ?>
                   <option value="<?php echo $rc['category_detail_id'] ?>"><?php echo $rc['category_detail_name'] ?></option>
                 <?php } ?>
               </select>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Remind in (days)<?php echo $important_star ?></label>
                <input type="text" class="form-control" name="reminder_day" />
                <span class="mt-2 d-block">Only number allowed.</span>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Reminder Method<?php echo $important_star ?></label>
                <select class="form-control" name="reminder_method">
                   <option value="EMAIL">EMAIL</option>
                   <option value="SMS">SMS</option>
                   <option value="BOTH">EMAIL + SMS</option>
               </select>
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
