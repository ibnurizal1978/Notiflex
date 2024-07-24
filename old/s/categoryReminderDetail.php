<?php
require_once 'inc/header.php';

//detail data
$category_reminder_id             = Encryption::decode($param[1]);
$s = "SELECT * FROM tbl_category_reminder a INNER JOIN tbl_category_detail b USING (category_detail_id) WHERE category_reminder_id = '".$category_reminder_id."' AND a.client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
?>

<div class="content-wrapper">
  <div class="content"><!-- Card Profile -->

    <div class="row">

    <div class="col-xl-12">
      <div class="card card-default">
        <div class="card-header">
          <h2 class="mb-5">Category Reminder Detail</h2>
        </div>

        <div class="card-body">
          <form action="categoryReminderAction?edit" method="POST">
            <input type="hidden" name="category_reminder_id" value="<?php echo Encryption::encode($r['category_reminder_id']) ?>" />
            <input type="hidden" name="category_detail_id" value="<?php echo Encryption::encode($r['category_detail_id']) ?>" />
            <div class="form-group row mb-6">
              <label for="occupation" class="col-sm-4 col-lg-2 col-form-label">Category Name<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" disabled value="<?php echo $r['category_detail_name'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="occupation" class="col-sm-4 col-lg-2 col-form-label">Remind in<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="reminder_day" value="<?php echo $r['reminder_day'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="occupation" class="col-sm-4 col-lg-2 col-form-label">Reminder Method<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <select class="form-control" name="reminder_method">
                    <option value="EMAIL" <?php if(@$r['reminder_method']=='EMAIL') { echo 'selected'; } ?>>EMAIL</option>
                    <option value="SMS" <?php if(@$r['reminder_method']=='SMS') { echo 'selected'; } ?>>SMS</option>
                    <option value="BOTH" <?php if(@$r['reminder_method']=='BOTH') { echo 'selected'; } ?>>EMAIL + SMS</option>
                </select>
              </div>
            </div>

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
