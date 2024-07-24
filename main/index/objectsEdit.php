<?php
require_once 'header.php';
$s = "SELECT object_id, category_master_id, name, description, additional_data FROM tbl_object WHERE client_id = '".$_SESSION['client_id']."' AND object_id = '".Encryption::decode($param[1])."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
$title_name = 'objects';
?>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle"><?php echo $title_name ?></div>
                    <h2 class="page-title">Detail Data:&nbsp;<span class="text-blue"><?php echo $r['name'] ?></span></h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row g-2 align-items-center">  

                <!-- card for detail -->
                <div class="col-lg-12">
                    <div class="row row-cards">
                        <div class="col-12">
                            <form id="form_simpan" class="card">
                                <input type="hidden" name="id" value="<?php echo Encryption::encode($r['object_id']) ?>">
                                <div class="card-header">
                                    <h3 class="card-title">
                                    Edit Data
                                    </h3>
                                </div>                                    
                                <div class="card-body">
                                    <div class="row row-cards">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label required">Object Name</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" name="name" class="form-control ps-0" autocomplete="off" value="<?php echo $r['name'] ?>" />
                                                </div>
                                                <small class="text-secondary">Name your object, for instance: "Adrian Welsh", "BMW M3 Cabriolet", " Acoma Corporation" and else</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Short Description</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" name="description" class="form-control ps-0" autocomplete="off" value="<?php echo $r['description'] ?>" />
                                                </div>
                                                <small class="text-secondary">This is optional</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label required">Category</label>
                                                <div class="input-group input-group-flat">
                                                    <select name="category_master_id" class="form-control form-select">
                                                        <?php
                                                        $sql_p = "SELECT category_master_id, category_master_name FROM tbl_category_master where client_id = '".$_SESSION['client_id']."' ORDER BY category_master_name";
                                                        $h_p  = mysqli_query($conn,$sql_p);
                                                        while($row_p = mysqli_fetch_assoc($h_p)) {
                                                        ?>
                                                        <option <?php if($r['category_master_id'] ==$row_p['category_master_id']) { echo "selected"; } ?> value="<?php echo $row_p['category_master_id'] ?>"><?php echo $row_p['category_master_name'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label required">Additional Data</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" name="additional_data" class="form-control ps-0" autocomplete="off" value="<?php echo $r['additional_data'] ?>" />
                                                </div>
                                                <small class="text-secondary">Additional data can addded to this object. For instance, you may input vehicle plate number, laptop serial number, employee's phone and else</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <div id="results"></div><div id="button"></div>
                                </div>
                            </form>
                        </div>             
                    </div>
                </div>
                <!-- end card for detail -->

                <br/>

                <!-- card for access data -->
                <div class="col-lg-12">
                    <div class="row row-cards">
                        <div class="col-12">
                            <form id="form_simpan2" class="card">
                                <input type="hidden" name="id" value="<?php echo Encryption::encode($r['object_id']) ?>">
                                <div class="card-header">
                                    <h3 class="card-title">
                                    Who Can Access This Object?
                                    </h3>
                                </div>                                    
                                <div class="card-body">
                                    <div class="row row-cards">
                                        <div class="col-lg-12">

                                            <div class="row" id="receiver">
                                                <div class="col-lg-4">
                                                    <div class="mb-6">
                                                        <label class="form-label required">Choose Name</label>
                                                        <select name="user_id" class="form-control form-select">
                                                            <?php
                                                            $sp = "SELECT user_id, full_name FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND active_status = 1 ORDER BY full_name";
                                                            $hp  = mysqli_query($conn,$sp);
                                                            while($rp = mysqli_fetch_assoc($hp)) {
                                                                $user_id = $rp['user_id'];
                                                            ?>
                                                            <option value="<?php echo $rp['user_id'] ?>"><?php echo $rp['full_name']?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2">
                                                    <div class="mb-6">
                                                        <label class="form-label required">Choose Access</label>
                                                        <select name="access" class="form-control form-select">
                                                            <?php
                                                            $sp = "SELECT access_type FROM tbl_object_access WHERE user_id = '".$user_id."' LIMIT 1";
                                                            $hp  = mysqli_query($conn,$sp);

                                                            if ($hp !== false && mysqli_num_rows($hp) > 0) {
                                                            while($rp = mysqli_fetch_assoc($hp)) {

                                                                if($rp['access'] == 1)
                                                                {
                                                                    $text = 'Can Read';
                                                                    $value = 1;
                                                                }elseif($rp['access'] == 2)
                                                                {
                                                                    $text = 'Can Read, Edit and Upload';
                                                                    $value = 2;
                                                                }

                                                            ?>
                                                            <option value="<?php echo $value ?>"><?php echo $text ?></option>
                                                            <?php }}else{ ?>
                                                            <option value="1">Can Read</option>
                                                            <option value="2">Can Read, Edit and Upload</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>  
                                                <div class="col-lg-2">
                                                    <div class="mb-6">
                                                        <label class="form-label">&nbsp;</label>
                                                        <div id="results2"></div><div id="button2"></div>
                                                    </div>
                                                </div>                                                                                           
                                            </div>
                                            <!-- end add new reminder -->

                                            <!-- list of access -->
                                            <div class="row" id="receiver">
                                                <div class="col-lg-6">
                                                    <h4 class="text-info">Existing Access</h4>
                                                    <table class="table-responsive table table-sm table-border">
                                                        <tr>
                                                            <td width="50%"><b>Name</b></td>
                                                            <td width="10%"><b>Access</b></td>
                                                            <td width="5%" class="text-center"><b>Action</b></td>
                                                        </tr>
                                                        <?php
                                                        $s = "SELECT full_name, user_id, access, object_id FROM tbl_object_access a INNER JOIN tbl_user b USING (user_id) WHERE b.client_id = '".$_SESSION['client_id']."' AND object_id = '".Encryption::decode($param[1])."'";
                                                        $h = mysqli_query($conn, $s);
                                                        while($r = mysqli_fetch_assoc($h))
                                                        {
                                                            $object_id = $r['object_id'];
                                                            $user_id = $r['user_id'];
                                                        
                                                            if($r['access'] == 1)
                                                            {
                                                                $text = '<span class="badge bg-blue text-blue-fg">Can Read</span>';
                                                            }elseif($r['access'] == 2){
                                                                $text = '<span class="badge bg-green text-green-fg">Can Read, Edit and Upload</span>';
                                                            }else{
                                                                $text = '<span class="badge bg-orange text-orange-fg">No Access</span>';
                                                            }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $r['full_name'] ?></td>
                                                            <td><?php echo $text ?></td>
                                                            <td class="text-center"><a href="<?php echo $title_name ?>Action?removeAccess?<?php echo Encryption::encode($object_id).'?'.Encryption::encode($user_id) ?>" class="btn btn-danger text-danger-fg btn-sm">X</a></td>
                                                        </tr>
                                                        <?php } ?>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- end list of access -->

                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <div id="results2"></div><div id="button2"></div>
                                </div>
                            </form>
                        </div>             
                    </div>
                </div>
                <!-- end card for access data -->

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
  $("#button").html('<?php echo $btn_save2 ?>');  
  $('#submit_data').click(function(){  
    $('#form_simpan').submit(); 
    $("#results").html('');
  });  
  $('#form_simpan').on('submit', function(event){
    $("#results").html(''); 
    $("#button").html('<?php echo $btn_save2_loading ?>');  
    event.preventDefault();  
    $.ajax({  
      url:"<?php echo $title_name ?>Action?edit",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results').html(data);
        $("#button").html('<?php echo $btn_save2 ?>');  
      }  
    });  
  });

  //for access method
    $("#button2").html('<?php echo $btn_save2 ?>');  
  $('#submit_data').click(function(){  
    $('#form_simpan2').submit(); 
    $("#results2").html('');
  });  
  $('#form_simpan2').on('submit', function(event){
    $("#results2").html(''); 
    $("#button2").html('<?php echo $btn_save2_loading ?>');  
    event.preventDefault();  
    $.ajax({  
      url:"<?php echo $title_name ?>Action?access",  
      method:"POST",  
      data:new FormData(this),  
      contentType:false,  
      processData:false,  
      success:function(data){ 
        $('#results2').html(data);
        $("#button2").html('<?php echo $btn_save2 ?>');  
      }  
    });  
  });  
});  
</script>
<?php require_once 'footer.php' ?>