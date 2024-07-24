<?php
require_once 'header.php';

//total user this buyer has
$sa = "SELECT count(user_id) as total FROM tbl_user WHERE owner_id = '".$_SESSION['client_id']."' AND client_id = '".$r['client_id']."'";
$ha = mysqli_query($conn, $sa);
$ra = mysqli_fetch_assoc($ha);

//outstanding for this buyer
$sb = "SELECT sum(sub_total_after_tax) as total FROM tbl_order_detail a INNER JOIN tbl_order b USING (order_code) WHERE a.client_id = '".$r['client_id']."' AND b.order_status <> 'IN_CART' AND payment_status = 'PENDING'";
$hb = mysqli_query($conn, $sb);
$rb = mysqli_fetch_assoc($hb);
if($rb['total'] < 1) { $total = 0; }else{ $total = $rb['total']; }

//remaining credit limit
$sc   = "SELECT credit_limit FROM tbl_client WHERE client_id = '".$r['client_id']."' LIMIT 1";
$hc   = mysqli_query($conn, $sc);
$rc   = mysqli_fetch_assoc($hc);
$sisa = $rc['credit_limit']-$rb['total'];

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
          <td class="text-dark font-weight-bold">Total User For This Buyer<br/>
            <b class="font-weight-bold"><a href="#" class="badge badge-pill badge-outline-primary"><?php echo $ra['total'] ?> user(s)</a></b></td>
        </tr>
        <tr>
          <td class="text-dark font-weight-bold">Total Outstanding<br/>
          <b class="text-right font-weight-bold"><a href="#" class="badge badge-pill badge-outline-success"><?php echo $_SESSION['currency'].' '.number_format($rb['total'],0,",",".") ?></a></b></td>
        </tr>
        <tr>
          <td class="text-dark font-weight-bold">Remaining Credit Limit<br/>
          <b class="text-right font-weight-bold"><a href="#" class="badge badge-pill badge-outline-info"><?php echo $_SESSION['currency'].' '.number_format($sisa,0,",",".") ?></a></b></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
</div>
