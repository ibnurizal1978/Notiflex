<?php
require_once 'inc/header.php';

//detail data
$id             = Encryption::decode($param[2]);
$s = "SELECT * FROM tbl_list WHERE id = '".$id."' AND client_id = '".$_SESSION['client_id']."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
?>

<div class="content-wrapper">
  <div class="content"><!-- Card Profile -->

    <div class="row">
    <div class="col-xl-12">
      <div class="card card-default">
        <div class="card-header">
          <h2 class="mb-5"><?php echo base64_decode($param[1]) ?> Detail <a href="list?<?php echo $param[1] ?>">[back]</a></h2>
        </div>

        <div class="card-body">
          <form action="listAction?edit" method="POST">
            <input type="hidden" name="id" value="<?php echo Encryption::encode($r['id']) ?>" />
            <input type="hidden" name="param1" value="<?php echo $param[1] ?>" />
            <div class="form-group row mb-6">
              <label for="occupation" class="col-sm-4 col-lg-2 col-form-label">Name<?php echo $important_star ?></label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="name" value="<?php echo $r['name'] ?>" />
              </div>
            </div>

            <?php if($r['type'] == $module_employee) { ?>
            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Position</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="position" value="<?php echo $r['position'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Email</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="email" value="<?php echo $r['email'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Phone</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="phone" value="<?php echo $r['phone'] ?>" />
              </div>
            </div>
            <?php } ?>

            <?php if($r['type'] == $module_clients || $r['type'] == $module_vendors) { ?>
            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Address</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="address" value="<?php echo $r['address'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">City</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="city" value="<?php echo $r['city'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Email</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="email" value="<?php echo $r['email'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Phone</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="phone" value="<?php echo $r['phone'] ?>" />
              </div>
            </div>
            <?php } ?>

            <?php if($r['type'] == $module_vehicle) { ?>
            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Vehicle Plate</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="vehicle_plate" value="<?php echo $r['vehicle_plate'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Vehicle Type</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="vehicle_type" value="<?php echo $r['vehicle_type'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Year</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="vehicle_year" value="<?php echo $r['vehicle_year'] ?>" />
              </div>
            </div>
            <?php } ?>

            <?php if($r['type'] == $module_electronics) { ?>
            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Serial Number</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="serial_number" value="<?php echo $r['serial_number'] ?>" />
              </div>
            </div>

            <div class="form-group row mb-6">
              <label for="com-name" class="col-sm-4 col-lg-2 col-form-label">Electronic Type</label>
              <div class="col-sm-8 col-lg-10">
                <input type="text" class="form-control" name="electronic_type" value="<?php echo $r['electronic_type'] ?>" />
              </div>
            </div>
            <?php } ?>


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
              <?php echo $btn_update; ?>
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
            <h2 class="mb-5">Expiration Data <a href="#"  data-toggle="modal" data-target="#modal-add">[Add new]</a></h2>
          </div>

          <div class="card-body">
            <?php
            $s2 = "SELECT a.list_id, a.category_id, a.list_expiration_id, category_name, date_format(start_date, '%M %d, %Y') as start_date, date_format(expired_date, '%M %d, %Y') as expired_date, datediff(expired_date, CURDATE()) difference, name, photo_name
            FROM tbl_list_expiration a INNER JOIN tbl_category b USING (category_id)  INNER JOIN tbl_list c ON a.held_by = c.id INNER JOIN tbl_list_upload d ON a.list_expiration_id = d.list_expiration_id WHERE archived_status = 0 AND list_id = '".$r['id']."' AND a.client_id = '".$_SESSION['client_id']."'";
            $h2 = mysqli_query($conn, $s2);
            if(mysqli_num_rows($h2) == 0)
            {
              echo 'No expiration data.';
            }else{
              ?>
              <table class="table table-hover table-product" id="list">
                <tr>
                  <th>Expiration Category</th>
                  <th>Active Date</th>
                  <th>Expired Date</th>
                  <th>Held by</th>
                  <th class="text-center">Action</th>
                </tr>
                <?php while($r2 = mysqli_fetch_assoc($h2)) { ?>
                <tr>
                  <td><?php echo $r2['category_name'] ?></td>
                  <td><?php echo $r2['start_date'] ?></td>
                  <td>
                    <?php
                    echo $r2['expired_date'].'<br/>';
                    if($r2['difference']<0) { 
                      echo '<b class="text-danger">ALREADY EXPIRED!</b>';
                    } elseif($r2['difference']<10) { 
                      echo '<small>expired in <b class="text-danger">'.$r2['difference'].' days</b></small>';
                    }else{
                      echo '<small>expired in <b class="text-success">'.$r2['difference'].' days</b></small>';
                    }
                    ?></td>
                  <td><?php echo $r2['name'] ?></td>
                  <td class="text-center">
                    <a class="btn-sm btn-success" href="../list_upload/<?php echo $r2['photo_name'] ?>" target="_blank">view file</a>
                    <a class="btn-sm btn-warning" href="#"  data-toggle="modal" data-target="#modal-view<?php echo $r2['list_expiration_id'] ?>">view archive</a>
                    <a href="listRenew?<?php echo $param[1]."?".$param[2]."?".Encryption::encode($r2['list_expiration_id'])."?".Encryption::encode($r['id']) ?>" class='btn-info btn-sm'>renew</a>
                  
                  
               <!-- modal history -->
               <div class="modal fade" id="modal-view<?php echo $r2['list_expiration_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">History</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </div>
                      <div class="modal-body px-4">
                        <div class="col-lg-12">
                        <?php
                          $s_archived = "SELECT a.list_expiration_id, category_name, date_format(start_date, '%M %d, %Y') as start_date, date_format(expired_date, '%M %d, %Y') as expired_date, datediff(expired_date, CURDATE()) difference, name, photo_name
                          FROM tbl_list_expiration a INNER JOIN tbl_category b USING (category_id)  INNER JOIN tbl_list c ON a.held_by = c.id INNER JOIN tbl_list_upload d ON a.list_expiration_id = d.list_expiration_id WHERE a.list_id = '".$r2['list_id']."' AND a.category_id = '".$r2['category_id']."' AND a.client_id = '".$_SESSION['client_id']."' ORDER BY list_expiration_id DESC";
                          $h_archived = mysqli_query($conn, $s_archived);
                          ?>
                      
                          <table class="table table-hover table-product" id="list">
                            <tr>
                              <th>Active Date</th>
                              <th>Expired Date</th>
                              <th>Document</th>
                              <th>Held by</th>
                            </tr>
                            <?php while($r_archived = mysqli_fetch_assoc($h_archived)) { ?>
                            <tr>
                              <td style="color:#000"><?php echo $r_archived['start_date'] ?></td>
                              <td style="color:#000"><?php echo $r_archived['expired_date'] ?></td>
                              <td style="color:#000"><a class="btn-sm btn-success" href="../list_upload/<?php echo $r_archived['photo_name'] ?>" target="_blank">view file</a></td>
                              <td style="color:#000"><?php echo $r_archived['name'] ?></td>
                            </tr>
                          <?php } ?>
                          </table>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <?php echo $btn_close ?>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end modal history -->                
                  
                  
                  </td>
                </tr>           
                <?php } ?>
                </table>
            <?php } ?>

          </div>
        </div>
      </div>
    </div>
  </div>



</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
function change(_this) {
  console.log(_this.value)
  $('#box').load('inc/listDetailDuration.php?id='+ _this.value);
}
</script>

<!-- Modal  -->
<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form action="listAction?addExpiration" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="param1" value="<?php echo $param[1] ?>" />
        <input type="hidden" name="param2" value="<?php echo $param[2] ?>" />
        <input type="hidden" name="param3" value="<?php echo $param[3] ?>" />
        <input type="hidden" name="id" value="<?php echo Encryption::encode($r['id']) ?>" />
        <div class="modal-header px-4">
          <h5 class="modal-title" id="exampleModalCenterTitle">New Reminder</h5>
        </div>
        <div class="modal-body px-4">

          <div class="col-lg-12">
            <div class="form-group">
              <label for="firstName">Type of Expiration<?php echo $important_star ?></label>
              <select name="category_id" onchange="change(this)" class="form-control">
                <option value=""> -- SELECT CATEGORY -- </option>
                <?php
                $sql  = "SELECT category_id, category_name FROM tbl_category WHERE category_type LIKE '%".$r['type']."%' AND active_status = 1 AND client_id = '".$_SESSION['client_id']."' ORDER BY category_name";
                $h    = mysqli_query($conn,$sql);
                while($row1 = mysqli_fetch_assoc($h)) {
                ?>
                <option name="category_id"  value="<?php echo $row1['category_id'] ?>"><?php echo $row1['category_name'] ?></option>
                <?php } ?>
               </select>
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
            <label for="firstName">Current Active Date<?php echo $important_star ?></label>
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
                <label for="firstName"> or follow the expired duration for this category: <div id="box"></div></label>
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
                $s1 = "SELECT name, id FROM tbl_list WHERE active_status = 1 AND type = 'EMPLOYEE' AND client_id = '".$_SESSION['client_id']."' ORDER BY name";
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


        </div>          
        <div class="modal-footer px-4">
          <?php echo $btn_cancel.$btn_save //this is button ?>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once 'inc/footer.php' ?>