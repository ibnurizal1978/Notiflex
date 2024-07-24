<?php
require_once 'inc/header.php';

$category_id = Encryption::decode($param[1]);
$s = "SELECT category_id, category_name FROM tbl_category WHERE category_id = '".$category_id."' LIMIT 1";
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
              <h3 class="card-title">Items for Category: <?php echo $r['category_name'] ?> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add"> Add</button></h3>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-hover table-product">
                <thead>
                <tr>
                  <th>Items Name</th>
                  <th>Current Active Date</th>
                  <th>Expired Date</th>
                  <th>Expired in (day)</th>
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
               "url": "data/items?<?php echo $param[1] ?>",
               "dataType": "json",
               "type": "POST"
             },
      columns: [
          { "data": "items_name" },
          { "data": "start_date" },
          { "data": "expired_date" },
          { "data": "expired_in"},
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
      <form action="itemsAction?add" method="POST">
        <input type="hidden" name="category_id" value="<?php echo Encryption::encode($r['category_id']) ?>" />
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Item for Category: <?php echo $r['category_name'] ?></h5>
        </div>
        <div class="modal-body px-4">

          <div class="col-lg-12">
            <div class="form-group">
              <label for="firstName">Item Name<?php echo $important_star ?></label>
              <input type="text" class="form-control" name="items_name" />
              <span class="mt-2 d-block">Special character are not allowed.</span>
            </div>
          </div>

          <br/>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="firstName">Current Active Date<?php echo $important_star ?></label>
                <input id="dt" type="date" class="form-control" name="start_date" />
                <span class="mt-2 d-block">Type in format: dd/mm/yyyy.</span>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="firstName">Expired Date<?php echo $important_star ?></label>
                <input type="date" class="form-control" name="expired_date" id="expired_date" />
                <span class="mt-2 d-block">Type in format: dd/mm/yyyy.</span>
              </div>
            </div>
          </div>

          <br/>
          <div class="col-lg-12">
            <div class="form-group">
              <label for="firstName">Item's Photo<?php echo $important_star ?></label>
              <input type="file" class="form-control" name="items_photo" />
              <span class="mt-2 d-block">You must upload photo for evidence</span>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="form-group">
              <label for="firstName">Who held this item?<?php echo $important_star ?></label>              
              <select class="form-control" name="employee_id">
                <option value="Nobody">Nobody</option>
                <?php
                $s1 = "SELECT full_name, employee_id FROM tbl_employee WHERE active_status = 1 AND client_id = '".$_SESSION['client_id']."' ORDER BY full_name";
                $h1 = mysqli_query($conn, $s1);
                while($r1 = mysqli_fetch_assoc($h1))
                {
                ?>
                <option value="<?php echo $r1['employee_id'] ?>"><?php echo $r1['full_name'] ?></option>
                <?php } ?>
              </select>
              <span class="mt-2 d-block">Who is currently held or operate this item?</span>
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

<script>
    function formatDate() {
      var input = document.getElementById('dt');
      var date = new Date(input.value);
      var day = date.getDate();
      var month = date.getMonth() + 1;
      var year = date.getFullYear();

      // Format day and month to have leading zeros if needed
      if (day < 10) {
        day = '0' + day;
      }
      if (month < 10) {
        month = '0' + month;
      }

      var formattedDate = day + '/' + month + '/' + year;
      input.value = formattedDate;
    }
</script>

<?php require_once 'inc/footer.php' ?>
