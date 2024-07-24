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
              <h3 class="card-title">Users <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add"> Add</button></h3>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-hover table-product">
                <thead>
                <tr>
                  <th>Username</th>
                  <th>Full Name</th>
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
               "url": "data/users",
               "dataType": "json",
               "type": "POST"
             },
      columns: [
          { "data": "username" },
          { "data": "full_name" },
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
      <form action="usersAction?add" method="POST">
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Data</h5>
        </div>
        <div class="modal-body px-4">

          <div class="row mb-2">

            <div class="col-lg-6">
              <div class="form-group">
               <label for="firstName">Email (this will be your username)<?php echo $important_star ?></label>
               <input type="email" class="form-control" name="username" />
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
          </div>
          <hr/>
          <dic class="row">

            <div class="col-lg-6">
              <div class="form-group">
               <label for="firstName">Module Access<?php echo $important_star ?></label>
               <br/>
               <?php
               $sql  = "SELECT * FROM tbl_nav_menu y  INNER JOIN tbl_nav_header b USING (nav_header_id) ORDER BY nav_header_name";
               $h    = mysqli_query($conn,$sql);
               while($row1 = mysqli_fetch_assoc($h)) {
               ?>
               <input type="checkbox" name="nav_menu_id[]"  value="<?php echo $row1['nav_menu_id'] ?>"><i class="dark-white"></i> <b><?php echo $row1['nav_header_name'].'</b> &raquo; '.$row1['nav_menu_name'] ?><br/>
               <?php } ?>
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
