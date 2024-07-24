<?php
require_once 'inc/header.php';

//detail data
$client_id  = Encryption::decode($param[1]);
$s = "SELECT * FROM tbl_client WHERE client_id = '".$client_id."' AND owner_id = '".$_SESSION['client_id']."'";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
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
              <h3 class="card-title">User for <?php echo $r['business_name'] ?> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add"> Add</button></h3>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-hover table-product">
                <thead>
                <tr>
                  <th>Username</th>
                  <th>Full Name</th>
                  <th>Role</th>
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
               "url": "data/user?<?php echo Encryption::encode($r['client_id']) ?>",
               "dataType": "json",
               "type": "POST"
             },
      columns: [
          { "data": "username" },
          { "data": "full_name" },
          { "data": "role_name" },
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
      <form action="userAction?add" method="POST">
        <input type="hidden" name="client_id" value="<?php echo $r['client_id'] ?>" />
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Data</h5>
        </div>
        <div class="modal-body px-4">

          <div class="row mb-2">

            <div class="col-lg-6">
              <div class="form-group">
               <label for="firstName">Username<?php echo $important_star ?></label>
               <input type="text" class="form-control" name="username" />
               <span class="mt-2 d-block">Blank space and special character are not allowed.</span>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
               <label for="firstName">Password<?php echo $important_star ?></label>
               <input type="text" class="form-control" name="password" />
               <span class="mt-2 d-block">Minimum of 6 characters and must have 1 uppercase letter, 1 lowercase letter and a number.</span>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
               <label for="firstName">Full Name<?php echo $important_star ?></label>
               <input type="text" class="form-control" name="full_name" />
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
               <label for="firstName">Role<?php echo $important_star ?></label>
               <select class="form-control" name="role_id">
                 <?php
                 $sc = "SELECT * FROM tbl_user_role ORDER BY role_name";
                 $hc = mysqli_query($conn, $sc);
                 while($rc = mysqli_fetch_assoc($hc))
                 {
                 ?>
                   <option value="<?php echo $rc['role_id'] ?>"><?php echo $rc['role_name'] ?></option>
                 <?php } ?>
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
