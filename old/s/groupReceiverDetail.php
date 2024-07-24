<?php
require_once 'inc/header.php';

//detail data
$id             = Encryption::decode($param[1]);
$s = "SELECT * FROM tbl_group_receiver WHERE group_receiver_id = '".$id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
?>

<div class="content-wrapper">
  <div class="content"><!-- Card Profile -->

    <div class="row">
    <div class="col-xl-12">
      <div class="card card-default">
        <div class="card-header">
          <h2 class="mb-5">Detail <?php echo $r['group_name'] ?></a></h2>
        </div>

        <div class="card-body">
          <form action="groupReceiverAction?edit" method="POST">
            <input type="hidden" name="id" value="<?php echo Encryption::encode($r['group_receiver_id']) ?>" />
            <div class="form-group row mb-6">
              <label for="occupation" class="col-sm-4 col-lg-2 col-form-label">Name<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="group_name" value="<?php echo $r['group_name'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Description</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="group_description" value="<?php echo $r['group_description'] ?>" />
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

            <div class="form-group row mb-6">
               <label class="col-sm-4 col-lg-2 col-form-label">Members<?php echo $important_star ?></label>
               <div class="col-sm-8 col-lg-10">
               <?php
                    $sql  = "SELECT user_id, full_name FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND active_status = 1 ORDER BY full_name";
                    $h    = mysqli_query($conn,$sql);
                    while($row1 = mysqli_fetch_assoc($h)) {
                        
                    $s3 = "SELECT user_id FROM tbl_group_receiver_detail WHERE user_id = '".$row1['user_id']."' AND group_receiver_id = '".$r['group_receiver_id']."'";
                    $h3 = mysqli_query($conn, $s3);
                    $r3 = mysqli_fetch_assoc($h3);
                    ?>
                    <input type="checkbox" name="user_id[]"  <?php if($r3 && $r3['user_id'] == $row1['user_id']) {  echo 'checked'; }else{ echo ''; } ?>  value="<?php echo $row1['user_id'] ?>"><i class="dark-white"></i> <b><?php echo $row1['full_name'] ?><br/>
                    <?php } ?>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <?php echo $btn_update ?>
            </div>
          </form>
        </div>
      </div>
    </div>
    
  </div>
</div>

</div>

<?php require_once 'inc/footer.php' ?>