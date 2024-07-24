<?php
require_once 'inc/header.php';

//detail data
$category_receiver_id             = Encryption::decode($param[1]);
$s = "SELECT * FROM tbl_category_receiver WHERE category_receiver_id = '".$category_receiver_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
?>

<div class="content-wrapper">
  <div class="content"><!-- Card Profile -->

    <div class="row">

    <div class="col-xl-12">
      <div class="card card-default">
        <div class="card-header">
          <h2 class="mb-5">Receiver Detail</h2>
        </div>

        <div class="card-body">
          <form action="categoryReceiverAction?edit" method="POST">
            <input type="hidden" name="category_receiver_id" value="<?php echo Encryption::encode($r['category_receiver_id']) ?>" />
            <div class="form-group row mb-6">
              <label for="occupation" class="col-sm-4 col-lg-2 col-form-label">Data<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="category_receiver_name" value="<?php echo $r['category_receiver_name'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Active Status</label>
              <div class="col-sm-8 col-lg-10">
                <select class="form-control" name="active_status">
                  <option value="1" <?php if($r['active_status'] == 1) { echo 'selected'; } ?>>Active</option>
                  <option value="0" <?php if($r['active_status'] == 0) { echo 'selected'; } ?>>Inactive</option>
                </select>
              </div>
            </div>

            <!--<div class="col-lg-6">
                <div class="form-group">
                    <label for="firstName">This Email Will Remind for Category<?php echo $important_star ?></label>
                    <br/>
                    <?php
                   $sql2  ="SELECT *, (SELECT count(*) FROM tbl_category_reminder_receiver x WHERE x.category_detail_id = y.category_detail_id AND x.category_receiver_id = '".$r['category_receiver_id']."') AS ada FROM tbl_category_detail y ORDER BY category_detail_name";
                   $h2    = mysqli_query($conn,$sql2);
                   while($row2 = mysqli_fetch_assoc($h2)) {
                     if($row2['ada']>0){
                   ?>
                   <input type="checkbox" name="category_detail_id[]" checked value="<?php echo $row2['category_detail_id'] ?>"><i class="dark-white"></i> <?php echo $row2['category_detail_name'] ?><br/>
                   <?php }else{ ?>
                   <input type="checkbox" name="category_detail_id[]" value="<?php echo $row2['category_detail_id'] ?>"><i class="dark-white"></i> <?php echo $row2['category_detail_name'] ?><br/>
                   <?php }} ?>
                </div>
            </div>-->

            <div class="d-flex justify-content-end">
              <input type="submit" class="btn btn-primary mb-2 btn-pill" value="Update" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once 'inc/footer.php' ?>
