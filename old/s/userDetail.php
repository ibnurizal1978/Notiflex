<?php
require_once 'inc/header.php';

//detail data
$user_id  = Encryption::decode($param[1]);
$s = "SELECT * FROM tbl_user WHERE user_id = '".$user_id."' AND owner_id = '".$_SESSION['client_id']."'";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
?>

<div class="content-wrapper">
  <div class="content"><!-- Card Profile -->

    <div class="row">
      <?php //include 'inc/side_info_buyer.php'; ?>

    <div class="col-xl-9">
      <div class="card card-default">
        <div class="card-header">
          <h2 class="mb-5">Usesr Detail</h2>
        </div>

        <div class="card-body">
          <form action="userAction?edit" method="POST">
            <input type="hidden" name="user_id" value="<?php echo Encryption::encode($r['user_id']) ?>" />
            <input type="hidden" name="client_id" value="<?php echo Encryption::encode($r['client_id']) ?>" />
            <div class="form-group row mb-6">
              <label for="occupation" class="col-sm-4 col-lg-2 col-form-label">Username<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="username" value="<?php echo $r['username'] ?>" />
              </div>
            </div>
            <div class="form-group row mb-6">
              <label class="col-sm-4 col-lg-2 col-form-label">Password<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="password" />
                <span class="mt-2 d-block">Leave blank if you don't want to change password.</span>
              </div>
            </div>
            <div class="form-group row mb-6">
              <label for="occupation" class="col-sm-4 col-lg-2 col-form-label">Full Name<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="full_name" value="<?php echo $r['full_name'] ?>" />
              </div>
            </div>
            <div class="form-group row mb-6">
               <label class="col-sm-4 col-lg-2 col-form-label">Role<?php echo $important_star ?></label>
               <div class="col-sm-8 col-lg-10">
               <select class="form-control" name="role_id">
                 <?php
                 $sc = "SELECT * FROM tbl_user_role ORDER BY role_name";
                 $hc = mysqli_query($conn, $sc);
                 while($rc = mysqli_fetch_assoc($hc))
                 {
                 ?>
                   <option value="<?php echo $rc['role_id'] ?>" <?php if($rc['role_id'] == $r['role_id']) { echo 'selected'; } ?>><?php echo $rc['role_name'] ?></option>
                 <?php } ?>
               </select>
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
            <div class="d-flex justify-content-end">
              <?php echo $btn_update ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once 'inc/footer.php' ?>
