<?php
require_once 'header.php';
$s = "SELECT group_receiver_id, group_name, group_description FROM tbl_group_receiver WHERE client_id = '".$_SESSION['client_id']."' AND group_receiver_id = '".Encryption::decode($param[1])."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
$title_name = 'groupReceiver';
?>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle"><?php echo $title_name ?></div>
                    <h2 class="page-title">Detail Data:&nbsp;<span class="text-blue"><?php echo $r['group_name'] ?></span></h2>
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
                                <input type="hidden" name="id" value="<?php echo Encryption::encode($r['group_receiver_id']) ?>">
                                <div class="card-header">
                                    <h3 class="card-title">
                                    Edit Data
                                    </h3>
                                </div>                                    
                                <div class="card-body">
                                    <div class="row row-cards">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label required">Group Receiver Name</label>
                                                <input type="text" name="group_name" class="form-control" value="<?php echo $r['group_name'] ?>" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Description (optional)</label>
                                                <input type="text" name="group_description" class="form-control" value="<?php echo $r['group_description'] ?>" />
                                            </div>
                                        </div>
                                        <p>&nbsp;</p>
                                        <hr/>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label required">Members</label>
                                                <?php
                                                $sql  = "SELECT user_id, full_name FROM tbl_user WHERE client_id = '".$_SESSION['client_id']."' AND active_status = 1 ORDER BY full_name";
                                                $h    = mysqli_query($conn,$sql);
                                                while($row1 = mysqli_fetch_assoc($h)) {
                                                    
                                                $s3 = "SELECT user_id FROM tbl_group_receiver_detail WHERE user_id = '".$row1['user_id']."' AND group_receiver_id = '".$r['group_receiver_id']."'";
                                                $h3 = mysqli_query($conn, $s3);
                                                $r3 = mysqli_fetch_assoc($h3);
                                                ?>
                                                <input type="checkbox" name="user_id[]"  <?php if($r3 && $r3['user_id'] == $row1['user_id']) {  echo 'checked'; }else{ echo ''; } ?>  value="<?php echo $row1['user_id'] ?>"><i class="dark-white"></i> <?php echo $row1['full_name'] ?><br/>
                                                <?php } ?>
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
});  
</script>
<?php require_once 'footer.php' ?>