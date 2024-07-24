<?php
require_once 'inc/header.php';

//detail data
$category_id             = Encryption::decode($param[3]);
$s = "SELECT * FROM tbl_category WHERE category_id = '".$category_id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
?>

<div class="content-wrapper">
  <div class="content"><!-- Card Profile -->

    <div class="row">
    <div class="col-xl-12">
      <div class="card card-default">
        <div class="card-header">
          <h2 class="mb-5">Detail <a href="category?<?php echo $param[1]."?".$param[2] ?>">[back to category]</a></h2>
        </div>

        <div class="card-body">
          <form action="categoryAction?edit" method="POST">
            <input type="hidden" name="category_id" value="<?php echo Encryption::encode($r['category_id']) ?>" />
            <input type="hidden" name="param1" value="<?php echo $param[1] ?>" />
            <input type="hidden" name="param2" value="<?php echo $param[2] ?>" />
            <div class="form-group row mb-6">
              <label for="occupation" class="col-sm-4 col-lg-2 col-form-label">Name<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="category_name" value="<?php echo $r['category_name'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Description</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="category_description" value="<?php echo $r['category_description'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Expired Duration</label>
              <div class="col-sm-4 col-lg-4">
                <input type="text" class="form-control" name="category_duration" value="<?php echo $r['category_duration'] ?>" />
                <span class="mt-2 d-block">Type in number.</span>
              </div>
              <div class="col-sm-4 col-lg-4">
                <select class="form-control" name="category_duration_cycle">
                  <option value="DAY" <?php if($r['category_duration_cycle'] == 'DAY') { echo 'selected'; } ?>>Day</option>
                  <option value="MONTH" <?php if($r['category_duration_cycle'] == 'MONTH') { echo 'selected'; } ?>>Month</option>
                  <option value="YEAR" <?php if($r['category_duration_cycle'] == 'YEAR') { echo 'selected'; } ?>>Year</option>
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

  <div class="content"><!-- Card Profile -->

    <div class="row">
      <div class="col-xl-12">
        <div class="card card-default">
          <div class="card-header">
            <h2 class="mb-5">Reminder Duration & Receiver <a href="#"  data-toggle="modal" data-target="#modal-add">[Add new]</a></h2>
          </div>

          <div class="card-body">
            <?php
            $s2 = "SELECT category_reminder_id, reminder_day FROM tbl_category_reminder WHERE category_id = '".$r['category_id']."' AND client_id = '".$_SESSION['client_id']."'";
            $h2 = mysqli_query($conn, $s2);
            if(mysqli_num_rows($h2) == 0)
            {
              echo 'No reminder yet. create at least one reminder by clicking a link [add new]';
            }else{
              while($r2 = mysqli_fetch_assoc($h2))
              {
                $s3 = "SELECT DISTINCT full_name FROM tbl_user a INNER JOIN tbl_group_receiver_detail b USING (user_id) INNER JOIN tbl_group_receiver c USING (group_receiver_id) INNER JOIN tbl_category_reminder_receiver d USING (group_receiver_id) WHERE category_reminder_id = '".$r2['category_reminder_id']."' AND a.client_id = '".$_SESSION['client_id']."'";
                $h3 = mysqli_query($conn, $s3);
                $full_name    = "";
                while($r3 = mysqli_fetch_assoc($h3))
                {
                  $full_name .= $r3['full_name'].", ";
                  
                }
                $full_name    = substr($full_name,0,-2);
                echo '<p>Remind in <b>'.$r2['reminder_day'].'</b> day(s) to: <i>'.$full_name.'</i> <a href=categoryReminderAction?delete?'.Encryption::encode($r2['category_reminder_id']).'?'.Encryption::encode($r['category_id']).'?'.$param[1].'?'.$param[2].'?'.$param[3].'> <span class="text-danger mdi mdi-close-circle-outline"></span></a></p>';
              }
            }
            ?>

          </div>
        </div>
      </div>
    </div>
  </div>



</div>


<!-- Modal  -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form action="categoryReminderAction?add" method="POST">
        <input type="hidden" name="param1" value="<?php echo $param[1] ?>" />
        <input type="hidden" name="param2" value="<?php echo $param[2] ?>" />
        <input type="hidden" name="param3" value="<?php echo $param[3] ?>" />
        <input type="hidden" name="category_id" value="<?php echo Encryption::encode($r['category_id']) ?>" />
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Reminder</h5>
        </div>
        <div class="modal-body px-4">

          <div class="col-lg-12">
            <div class="form-group">
              <label for="firstName">Remind in (day)<?php echo $important_star ?></label>
              <input type="text" class="form-control" name="reminder_day" />
              <span class="mt-2 d-block">Type only number.</span>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="form-group">
            <label for="firstName">Remind to<?php echo $important_star ?></label>
               <br/>
               <?php
               //$sql  = "SELECT user_id, username FROM tbl_user WHERE active_status = 1 AND client_id = '".$_SESSION['client_id']."' ORDER BY username";
               $sql = "SELECT group_receiver_id, group_name FROM tbl_group_receiver WHERE client_id = '".$_SESSION['client_id']."' ORDER BY group_name";
               $h    = mysqli_query($conn,$sql);
               while($row1 = mysqli_fetch_assoc($h)) {
               ?>
               <input type="checkbox" name="group_receiver_id[]"  value="<?php echo $row1['group_receiver_id'] ?>"><i class="dark-white"></i> <b><?php echo $row1['group_name'] ?><br/>
               <!--<input type="checkbox" name="user_id[]"  value="<?php echo $row1['user_id'] ?>"><i class="dark-white"></i> <b><?php echo $row1['username'] ?><br/>-->
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
</div>

<?php require_once 'inc/footer.php' ?>