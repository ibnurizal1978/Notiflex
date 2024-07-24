<?php
require_once 'header.php';
$s = "SELECT user_id,username,full_name,DATE_FORMAT(DATE_ADD(a.created_at,INTERVAL '" . $_SESSION['selisih'] . "' HOUR), '%d/%m/%Y %H:%i') as created_at, DATE_FORMAT(DATE_ADD(last_login,INTERVAL '" . $_SESSION['selisih'] . "' HOUR), '%d/%m/%Y %H:%i') as last_login, a.active_status, b.client_id,b.business_name FROM tbl_user a INNER JOIN tbl_client b ON a.client_id = b.client_id WHERE a.client_id = '".$_SESSION['client_id']."' AND user_id = '".Encryption::decode($param[1])."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
$title_name = 'users';

/* create a label for order detail status */
switch ($r['active_status']) {
    case 1:
        $active_status = "<span class='badge badge-outline text-green'>Active</span>";
        break;
    default:
        $active_status = "<span class='badge badge-outline text-danger'>Inactive</span>";
}
?>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle"><?php echo $title_name ?></div>
                    <h2 class="page-title">Detail Data:&nbsp;<span class="text-blue"><?php echo $r['full_name'] ?></span></h2>
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
                            <!--<form class="card" method="POST" action="">-->
                            <form id="form_simpan" class="card">
                                <input type="hidden" name="id" value="<?php echo Encryption::encode($r['user_id']) ?>">
                                <div class="card-header">
                                    <h3 class="card-title">
                                    Edit Data
                                    </h3>
                                </div>                                    
                                <div class="card-body">
                                <div class="row row-cards">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Full Name</label>
                                            <input type="text" name="full_name" class="form-control" value="<?php echo $r['full_name'] ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Password (leave it blank if you wish not to change password)</label>
                                            <input type="text" name="password" class="form-control"  />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Active Status</label>
                                            <select name="active_status" class="form-control form-select">
                                                <option <?php if($r['active_status'] == 1) { echo "selected"; } ?> value="1">YES</option>
                                                <option <?php if($r['active_status'] == 0) { echo "selected"; } ?> value="0">NO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label required">Module Access</label>
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

<!-- this is for date picker -->
<script src="../../assets/libs/litepicker/dist/litepicker.js?1692870487" defer></script>
<script>
    // @formatter:off
    document.addEventListener("DOMContentLoaded", function () {
    	window.Litepicker && (new Litepicker({
    		element: document.getElementById('datepicker-icon'),
    		buttonText: {
    			previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>`,
    			nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
    		},
    	}));
    });
    // @formatter:on
</script>
<!-- end this is for date picker -->

<?php require_once 'footer.php' ?>