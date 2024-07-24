<?php
require_once 'inc/header.php';
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
              <h3 class="card-title">Group Receiver
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add"> Add</button>
              </h3>  
            </div>
            <div class="card-body">
              <table id="example1" class="table table-hover table-product">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
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
               "url": "data/groupReceiver",
               "dataType": "json",
               "type": "POST"
             },
      
        columns: [
          { "data": "group_name" },
          { "data": "group_description" },
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
      <form action="groupReceiverAction?add" method="POST">
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Data</h5>
        </div>
        <div class="modal-body px-4">

          <div class="row mb-2">

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Group Name<?php echo $important_star ?></label>
                <input type="text" class="form-control" name="group_name" />
                <span class="mt-2 d-block">You may set a name for this group.</span>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Description (optional)</label>
                <input type="text" class="form-control" name="group_description" />
                <span class="mt-2 d-block">Type a short description.</span>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Members<?php echo $important_star ?></label>
                    <br/>
                    <?php
                    $sql  = "SELECT user_id, full_name FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND active_status = 1 ORDER BY full_name";
                    $h    = mysqli_query($conn,$sql);
                    while($row1 = mysqli_fetch_assoc($h)) {
                    ?>
                    <input type="checkbox" name="user_id[]"  value="<?php echo $row1['user_id'] ?>"><i class="dark-white"></i> <b><?php echo $row1['full_name'] ?><br/>
                    <?php } ?>
                <span class="mt-2 d-block">This will be a member of this group</span>
              </div>
            </div>            

        </div>
        <div class="modal-footer px-4">
          <?php echo $btn_cancel.$btn_save ?>
        </div>
      </form>
    </div>
  </div>
</div></div>

<?php require_once 'inc/footer.php' ?>