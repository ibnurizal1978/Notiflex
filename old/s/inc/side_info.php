<?php
require_once 'header.php';

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

?>
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
