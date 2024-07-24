<?php
require_once 'inc/header.php';
$title = base64_decode($param[1]);

if($title == 'EMPLOYEE' && $param[2] == 1)
{
  $title2 = 'Employee Certification';
}elseif($title == 'EMPLOYEE' && $param[2] == 2)
{
  $title2 = 'Employee Contract';
}else{
  $title2 = $title;
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
              <h3 class="card-title"><?php echo $title2; ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-add"> Add</button>
              </h3>  
            </div>
            <div class="card-body">
              <table id="example1" class="table table-hover table-product">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Duration</th>
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
               "url": "data/category?<?php if(@$param[2] <> '') { echo $param[1]."?".@$param[2]; }else{ echo $param[1]; } ?>",
               "dataType": "json",
               "type": "POST"
             },
      
        columns: [
          { "data": "category_name" },
          { "data": "category_description" },
          { "data": "category_duration" },
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
      <form action="categoryAction?add" method="POST">
        <input type="hidden" name="param1" value="<?php echo $param[1] ?>" />
        <input type="hidden" name="param2" value="<?php echo @$param[2] ?>" />
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Type</h5>
        </div>
        <div class="modal-body px-4">

          <div class="row mb-2">

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Type Name<?php echo $important_star ?></label>
                <input type="text" class="form-control" name="category_name" />
                <span class="mt-2 d-block">Warranty name, or name of activity that has an expired date for <?php echo $title ?>.</span>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
                <label for="firstName">Description (optional)</label>
                <input type="text" class="form-control" name="category_description" />
                <span class="mt-2 d-block">Type a short description.</span>
              </div>
            </div>

            <div class="col-lg-2">
              <div class="form-group">
                <label for="firstName">Duration<?php echo $important_star ?></label>
                <input type="text" class="form-control" name="category_duration" />
                <span class="mt-2 d-block">Type in number.</span>
              </div>
            </div> 
            
            <div class="col-lg-2">
              <div class="form-group">
                <label for="firstName">In<?php echo $important_star ?></label>
                <select class="form-control" name="category_duration_cycle">
                  <option value="DAY">Day</option>
                  <option value="MONTH">Month</option>
                  <option value="YEAR">Year</option>
                </select>
              </div>
            </div>

        </div>
        <div class="modal-footer px-4">
          <?php echo $btn_cancel.$btn_save //this is button ?>
        </div>
      </form>
    </div>
  </div>
</div></div>

<?php require_once 'inc/footer.php' ?>