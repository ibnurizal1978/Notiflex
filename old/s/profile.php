<?php
require_once 'inc/header.php';

//total region this client has
$sa = "SELECT count(region_id) as total FROM tbl_region WHERE client_id = '".$_SESSION['client_id']."'";
$ha = mysqli_query($conn, $sa);
$ra = mysqli_fetch_assoc($ha);

//total buyer this client has
$sb = "SELECT count(client_id) as total FROM tbl_client WHERE owner_id = '".$_SESSION['client_id']."'";
$hb = mysqli_query($conn, $sb);
$rb = mysqli_fetch_assoc($hb);

//total product this client has
$sc = "SELECT count(product_id) as total FROM tbl_product WHERE client_id = '".$_SESSION['client_id']."'";
$hc = mysqli_query($conn, $sc);
$rc = mysqli_fetch_assoc($hc);

//detail data
$s = "SELECT * FROM tbl_client WHERE client_id = '".$_SESSION['client_id']."'";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
?>

<div class="content-wrapper">
  <div class="content"><!-- Card Profile -->

    <div class="row">
      <div class="col-xl-3">
        <div class="card card-default">
          <div class="card-header">
          <h2>Info</h2>
        </div>

        <div class="card-body pt-0">
          <table class="table table-borderless table-thead-border">
            <tbody>
              <tr>
                <td class="text-dark font-weight-bold">Total Region</td>
                  <td class="text-right font-weight-bold"><a href="#" class="badge badge-pill badge-outline-primary"><?php echo $ra['total'] ?></a></td>
              </tr>
              <tr>
                <td class="text-dark font-weight-bold">Total Buyer</td>
                <td class="text-right font-weight-bold"><a href="#" class="badge badge-pill badge-outline-success"><?php echo $rb['total'] ?></a></td>
              </tr>
              <tr>
                <td class="text-dark font-weight-bold">Total Product</td>
                <td class="text-right font-weight-bold"><a href="#" class="badge badge-pill badge-outline-info"><?php echo $rc['total'] ?></a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-xl-9">
      <div class="card card-default">
        <div class="card-header">
          <h2 class="mb-5">Profile Detail</h2>
        </div>

        <div class="card-body">
          <form action="profileAction" method="POST">
            <div class="form-group row mb-6">
              <label class="col-sm-4 col-lg-2 col-form-label">Business Name<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="business_name" value="<?php echo $r['business_name'] ?>" />
              </div>
            </div>
            <div class="form-group row mb-6">
              <label class="col-sm-4 col-lg-2 col-form-label">Currency<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="currency" value="<?php echo $r['currency'] ?>" />
                <span class="mt-2 d-block">e.g. $, IDR, € etc.</span>
              </div>
            </div>
            <div class="form-group row mb-6">
              <label class="col-sm-4 col-lg-2 col-form-label">Business Address<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="business_address" value="<?php echo $r['business_address'] ?>" />
              </div>
            </div>
            <div class="form-group row mb-6">
              <label class="col-sm-4 col-lg-2 col-form-label">Business Phone<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="business_phone" value="<?php echo $r['business_phone'] ?>" />
              </div>
            </div>
            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Business Email<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="business_email" value="<?php echo $r['business_email'] ?>" />
              </div>
            </div>
            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">City<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="city" value="<?php echo $r['city'] ?>" />
              </div>
            </div>
            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">State<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="state" value="<?php echo $r['state'] ?>" />
              </div>
            </div>
            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Zip Code<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="zip_code" value="<?php echo $r['zip_code'] ?>" />
              </div>
            </div>
            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Country<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <select class="form-control" name="country_id">
                  <?php
                  $sc = "SELECT country_id, country_name FROM tbl_country ORDER BY country_name";
                  $hc = mysqli_query($conn, $sc);
                  while($rc = mysqli_fetch_assoc($hc))
                  {
                  ?>
                    <option value="<?php echo $rc['country_id'] ?>" <?php if($rc['country_id'] == $r['country_id']) { echo 'selected'; } ?>><?php echo $rc['country_name'] ?></option>
                  <?php } ?>
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
