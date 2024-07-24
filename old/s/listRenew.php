<?php
require_once 'inc/header.php';

//detail data
$id = Encryption::decode($param[3]);
$s = "SELECT * FROM tbl_list a INNER JOIN tbl_list_expiration b ON a.id = b.list_id INNER JOIN tbl_category c ON b.category_id = c.category_id WHERE b.list_expiration_id = '".$id."' AND b.client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
?>



<div class="content-wrapper">
  <div class="content"><!-- Card Profile -->

    <div class="row">
    <div class="col-xl-12">
      <div class="card card-default">
        <div class="card-header">
          <h2 class="mb-5">Renew Expiration <a href="listDetail?<?php echo $param[1]."?".$param[2] ?>">[back]</a></h2>
          <p><b>Detail for</b>
            <br/>Name: <?php echo "<span class=text-info>".$r['name']."</span><br/>Category: <span class=text-info>".$r['category_name']."</span>"; ?></p>
        </div>

        <div class="card-body">
          <br/>
          <b class="text-danger text-center">Remember: When you hit "Renew" button, this will be a new record for the expiration data. Previous record will remain archive.</b>
          <br/><br/>
          <form action="listAction?renewExpiration" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="old_id" value="<?php echo Encryption::encode($r['list_expiration_id']) ?>" />
            <input type="hidden" name="id" value="<?php echo Encryption::encode($r['id']) ?>" />
            <input type="hidden" name="category_id" value="<?php echo Encryption::encode($r['category_id']) ?>" />
            <input type="hidden" name="param1" value="<?php echo $param[1] ?>" />
            <input type="hidden" name="param2" value="<?php echo $param[2] ?>" />
            <input type="hidden" name="param4" value="<?php echo @$param[4] ?>" />
            <input type="hidden" name="param5" value="<?php echo @$param[5] ?>" />
            
            <div class="col-lg-12">
            <div class="form-group">
              <label for="firstName">Type of Expiration</label>
              <input type="text" class="form-control" value="<?php echo $r['category_name'] ?>" disabled />
            </div>
          </div>

          <div class="col-lg-12">
            <div class="form-group">
            <label for="firstName">Photo/Document<?php echo $important_star ?></label>
            <input type="file" class="form-control" name="list_upload" />
            </div>
          </div>

          <div class="col-lg-6">
            <div class="form-group">
            <label for="firstName">New Active Date<?php echo $important_star ?></label>
            <input id="dt" type="date" class="form-control" name="start_date" />
            <span class="mt-2 d-block">Date written on the document.<br/>Type in format: dd/mm/yyyy.</span>
            </div>
          </div>
          <br/>
          <hr/>
          <br/>
          <h5>Expiration Period<?php echo $important_star ?></h5>
          <br/>
            <div class="col-lg-6">
              <div class="form-group">
                <label for="firstName">Type the Expired Date</label>
                <input id="dt" type="date" class="form-control" name="expired_date" />
                <span class="mt-2 d-block">End date written on the document.<br/>Type in format: dd/mm/yyyy.</span>
              </div>
            </div>

            <div class="col-lg-12">
              <div class="form-group">
                <input type="checkbox" name="expired_date2" id="expired_date" />
                <label for="firstName"> or follow the expired duration for this category:
                <?php
                $s = "SELECT category_duration, category_duration_cycle FROM tbl_category WHERE category_id = '".$r['category_id']."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
                $h = mysqli_query($conn, $s);
                $r = mysqli_fetch_assoc($h);
                echo "<b class='text-danger'>".$r['category_duration']." ".$r['category_duration_cycle']."(s)</b>";
                ?>
                </label>
              </div>
            </div>

          <br/>
          <span class="mt-2 d-block">You may have two options for setting an expired date:<br/>Type it in format dd/mm/yyyy or checklist the option so system will auto calculate the expired date based on duration for this category.</span>
          <hr/>
          <br/>

          <div class="col-lg-12">
            <div class="form-group">
              <label for="firstName">Who held this item?<?php echo $important_star ?></label>              
              <select class="form-control" name="held_by">
                <option value="000">Nobody</option>
                <?php
                $s1 = "SELECT name, id FROM tbl_list WHERE active_status = 1 AND client_id = '".$_SESSION['client_id']."' ORDER BY name";
                $h1 = mysqli_query($conn, $s1);
                while($r1 = mysqli_fetch_assoc($h1))
                {
                ?>
                <option value="<?php echo $r1['id'] ?>"><?php echo $r1['name'] ?></option>
                <?php } ?>
              </select>
              <span class="mt-2 d-block">Who is currently held or operate this item?</span>
            </div>
          </div>

            <div class="d-flex justify-content-end">
              <?php echo $btn_renew ?>
            </div>
          </form>
        </div>
      </div>
    </div>
    
  </div>
</div>


<?php require_once 'inc/footer.php' ?>