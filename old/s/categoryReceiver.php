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
              <h3 class="card-title">Receiver <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add"> Add New Email</button></h3>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-hover table-product">
                <thead>
                <tr>
                  <th>Receiver</th>
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
               "url": "data/categoryReceiver",
               "dataType": "json",
               "type": "POST"
             },
      columns: [
          { "data": "category_receiver_name" },
          { "data": "active_status" },
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
      <form action="categoryReceiverAction?add1" method="POST">
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Data</h5>
        </div>
        <div class="modal-body px-4">

          <div class="row mb-2">

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Email<?php echo $important_star ?></label>
                <input type="text" class="form-control" name="category_receiver_name" />
                <span class="mt-2 d-block">Type only 1 email.</span>
              </div>
            </div>

            <!--<div class="col-lg-6">
                <div class="form-group">
                    <label for="firstName">This Email Will Remind for Category<?php echo $important_star ?></label>
                    <br/>
                    <?php
                    $sql  = "SELECT category_detail_id, category_detail_name FROM tbl_category_detail WHERE client_id = '".$_SESSION['client_id']."' ORDER BY category_detail_name";
                    $h    = mysqli_query($conn,$sql);
                    while($row1 = mysqli_fetch_assoc($h)) {
                    ?>
                    <input type="checkbox" name="category_detail_id[]"  value="<?php echo $row1['category_detail_id'] ?>"><i class="dark-white"></i> <b><?php echo $row1['category_detail_name'] ?><br/>
                    <?php } ?>
                </div>
            </div>-->


        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-smoke btn-pill" data-dismiss="modal">Cancel</button>
          <input type="submit" class="btn btn-primary btn-pill" value="Save" />
        </div>
      </form>
    </div>
  </div>
</div></div>


<!-- Modal2  -->
<div class="modal fade" id="modal-add2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form action="categoryReceiverAction?add2" method="POST">
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New SMS Data</h5>
        </div>
        <div class="modal-body px-4">

          <div class="row mb-2">

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">SMS Number<?php echo $important_star ?></label>
                <input type="number" class="form-control" name="category_receiver_name" />
                <span class="mt-2 d-block">Type number only, including your country code.</span>
              </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label for="firstName">This Email Will Remind for Category<?php echo $important_star ?></label>
                    <br/>
                    <?php
                    $sql  = "SELECT category_detail_id, category_detail_name FROM tbl_category_detail WHERE client_id = '".$_SESSION['client_id']."' ORDER BY category_detail_name";
                    $h    = mysqli_query($conn,$sql);
                    while($row1 = mysqli_fetch_assoc($h)) {
                    ?>
                    <input type="checkbox" name="category_detail_id[]"  value="<?php echo $row1['category_detail_id'] ?>"><i class="dark-white"></i> <b><?php echo $row1['category_detail_name'] ?><br/>
                    <?php } ?>
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
