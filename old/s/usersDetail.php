<?php
require_once 'inc/header.php';

//detail data
$user_id  = Encryption::decode($param[1]);
$s = "SELECT * FROM tbl_user WHERE user_id = '".$user_id."' AND client_id = '".$_SESSION['client_id']."'";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
?>

<div class="content-wrapper">
  <div class="content"><!-- Card Profile -->

    <div class="row">
      <?php //include 'inc/side_info_buyer.php'; ?>

    <div class="col-xl-12">
      <div class="card card-default">
        <div class="card-header">
          <h2 class="mb-5">User Detail</h2>
        </div>

        <div class="card-body">
          <form action="usersAction?edit" method="POST">
            <input type="hidden" name="user_id" value="<?php echo Encryption::encode($r['user_id']) ?>" />
            <div class="form-group row mb-6">
              <label for="occupation" class="col-sm-4 col-lg-2 col-form-label">Username</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" disabled value="<?php echo $r['username'] ?>" />
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
               <label class="col-sm-4 col-lg-2 col-form-label">Module Access<?php echo $important_star ?></label>
               <div class="col-sm-8 col-lg-10">
                 <?php
                   $sql2  ="SELECT *, (SELECT count(*) FROM tbl_nav_user x WHERE x.nav_menu_id = y.nav_menu_id AND user_id = '".$r['user_id']."') AS ada FROM tbl_nav_menu y  INNER JOIN tbl_nav_header b USING (nav_header_id) ORDER BY nav_header_name ";
                   $h2    = mysqli_query($conn,$sql2);
                   while($row2 = mysqli_fetch_assoc($h2)) {
                     if($row2['ada']>0){
                   ?>
                   <input type="checkbox" name="nav_menu_id[]" checked value="<?php echo $row2['nav_menu_id'] ?>"><i class="dark-white"></i> <?php echo $row2['nav_header_name'].' - '.$row2['nav_menu_name'] ?><br/>
                   <?php }else{ ?>
                   <input type="checkbox" name="nav_menu_id[]" value="<?php echo $row2['nav_menu_id'] ?>"><i class="dark-white"></i> <?php echo $row2['nav_header_name'].' - '.$row2['nav_menu_name'] ?><br/>
                   <?php }} ?>
              </div>
            </div>
            <!--<div class="form-group row mb-6">
               <label class="col-sm-4 col-lg-2 col-form-label">Category Access<?php echo $important_star ?></label>
               <div class="col-sm-8 col-lg-10">
                 <?php
                   $sql2  ="SELECT category_id, category_name FROM tbl_category WHERE active_status = 1 AND client_id = '".$_SESSION['client_id']."' ORDER BY category_name";
                   $h2    = mysqli_query($conn,$sql2);
                   while($row2 = mysqli_fetch_assoc($h2)) {

                    $s3 = "SELECT category_id FROM tbl_nav_category_user WHERE category_id = '".$row2['category_id']."' AND user_id = '".$r['user_id']."'";
                    $h3 = mysqli_query($conn, $s3);
                    $r3 = mysqli_fetch_assoc($h3);
                   ?>
                   <input type="checkbox" name="category_id[]" <?php if($r3 && $r3['category_id'] == $row2['category_id']) {  echo 'checked'; }else{ echo ''; } ?> value="<?php echo $row2['category_id'] ?>"><i class="dark-white"></i> <?php echo $row2['category_name'] ?><br/>
                  <?php } ?>
                </div>
            </div>-->
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
