<?php
require_once 'header.php';
$s          = "SELECT object_id, name FROM tbl_object WHERE client_id = '".$_SESSION['client_id']."' AND object_id = '".Encryption::decode($param[1])."' LIMIT 1";
$h          = mysqli_query($conn, $s);
$r          = mysqli_fetch_assoc($h);
$name       = $r['name'];
$object_id  = $r['object_id'];
$title_name = 'objects';
?>

<div class="page-wrapper">

    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title"><?php echo $name ?>'s Expiration Data</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Add or Renew Document
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="row g-0">
                    <div class="col-12 col-md-3 border-end">
                        <div class="card-body">
                            <h4 class="subheader">Expiration Data</h4>
                            <div class="list-group list-group-transparent">
                                <?php
                                //this is for showing what reminder type he has
                                $s = "SELECT reminder_type_id, reminder_type_name FROM tbl_reminder_type a INNER JOIN tbl_object_expiration b USING (reminder_type_id) INNER JOIN tbl_object c USING (object_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND object_id = '".Encryption::decode($param[1])."' GROUP BY b.reminder_type_id";
                                $h = mysqli_query($conn, $s);
                                if(mysqli_num_rows($h) == 0)
                                {
                                    echo '<span class="text-danger list-group-item list-group-item-action d-flex">This object does not have any reminder type yet.</span>';
                                }
                                while($r = mysqli_fetch_assoc($h))
                                {
                                ?>
                                <a href="objectsDetail?<?php echo $param[1] ?>?<?php echo Encryption::encode($r['reminder_type_id']) ?>?<?php echo Encryption::encode($r['reminder_type_name']) ?>" class="list-group-item list-group-item-action d-flex align-items-center active"><?php echo $r['reminder_type_name'] ?> &raquo;</a>
                                <?php } ?>
                            </div>
  
                            </div>
                        </div>

                        <!-- right side -->
                        <div class="col-12 col-md-9 d-flex flex-column">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <?php
                                        //if this page just open first time, they will no choosen reminder type. So we must query the reminder type
                                        if(@$param[3] == '')
                                        {
                                            $s = "SELECT reminder_type_id, reminder_type_name FROM tbl_reminder_type a INNER JOIN tbl_object_expiration b USING (reminder_type_id) where a.client_id = '".$_SESSION['client_id']."' AND object_id = '".$object_id."' ORDER BY reminder_type_id";
                                            $h = mysqli_query($conn, $s);
                                            if(mysqli_num_rows($h) == 0)
                                            {
                                                echo 'No Historical Data';
                                            }else{
                                                echo 'Top 20 Expiration Data';
                                            }
                                        }else{
                                            echo 'History for '.Encryption::decode(@$param[3]);
                                        }
                                        ?>
                                    </h3>
                                </div>

                                <div class="list-group list-group-flush list-group-hoverable">
                                    <?php
                                    //get 20 last expiration history which are active
                                    if(@$param[2] == '')
                                    {
                                        $query = ' AND archived_status = 0';
                                    }else{
                                        $query = 'AND a.reminder_type_id ='.Encryption::decode(@$param[2]);
                                    }
                                    $s = "SELECT archived_status, date_format(start_date, '%M %d, %Y') as start_date, date_format(expired_date, '%M %d, %Y') as expired_date, datediff(expired_date, CURDATE()) difference, file_name, reminder_type_name, a.description, additional_data FROM tbl_object_expiration a INNER JOIN tbl_object_file b USING (object_expiration_id) INNER JOIN tbl_reminder_type c ON a.reminder_type_id = c.reminder_type_id INNER JOIN tbl_object d ON a.object_id = d.object_id WHERE a.client_id = '".$_SESSION['client_id']."' AND a.object_id = '".$object_id."' $query ORDER BY expired_date DESC LIMIT 20";
                                    $h = mysqli_query($conn, $s);
                                    if(mysqli_num_rows($h) == 0)
                                    {
                                        echo '<span class="text-danger list-group-item">No data for this reminder type. You may create a new one <a href="#" data-bs-toggle="modal" data-bs-target="#modal">here</a></span>';
                                    }
                                    while($r = mysqli_fetch_assoc($h))
                                    {
                                        //give color for difference, red if less than 30 days
                                        if($r['difference'] < 31 && $r['archived_status'] == 0)
                                        {
                                            $difference = '<span class="text-danger"><b>'.$r['difference'].' day(s) left!</b></span>';
                                        }elseif($r['difference'] > 31 && $r['archived_status'] == 0){
                                            $difference = '<span class="text-success">'.$r['difference'].' day(s) left</span>';
                                        }else{
                                            $difference = '<span class="text-grey">(archived)</span>';
                                        }
                                    ?>
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto"><span class="badge <?php if($r['archived_status'] == 0) { ?>bg-success<?php }else{ ?>bg-secondary<?php } ?>"></span></div>
                                            <div class="col text-truncate">Note: <?php echo $r['description'] ?><br/>
                                                <small><?php echo $r['additional_data'] ?></small>
                                            </div>

                                            <!-- start display icon -->
                                            <div class="row">
                                                <div class="mt-3 list-inline list-inline-dots mb-0 text-secondary d-sm-block d-none">
                                                    <!-- start date -->
                                                    <div class="list-inline-item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path><path d="M16 3v4"></path><path d="M8 3v4"></path><path d="M4 11h16"></path><path d="M11 15h1"></path><path d="M12 15v3"></path></svg>
                                                        <?php echo $r['start_date'] ?>
                                                    </div>
                                                    <!-- expired date -->
                                                    <div class="list-inline-item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 5h9a2 2 0 0 1 2 2v9m-.184 3.839a2 2 0 0 1 -1.816 1.161h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 1.158 -1.815"></path><path d="M16 3v4"></path><path d="M8 3v1"></path><path d="M4 11h7m4 0h5"></path><path d="M3 3l18 18"></path></svg>
                                                        <?php echo $r['expired_date'] ?>
                                                    </div>
                                                    <!-- view file -->
                                                    <div class="list-inline-item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path></svg>
                                                        <a href="../../uploads/<?php echo $r['file_name'] ?>" target="_blank">view file</a>
                                                    </div>
                                                    <!-- difference -->
                                                    <div class="list-inline-item">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clock-hour-5" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M12 12l2 3"></path><path d="M12 7v5"></path></svg>
                                                        <?php echo $difference ?>
                                                    </div>
                                                </div>                            
                                            </div>
                                            <!-- end start display icon -->

                                        </div>
                                    </div>
                                    <?php } ?>
                                   
                                </div>
                            </div>
                        </div>
                        <!-- end right side -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

        // @formatter:off
        document.addEventListener("DOMContentLoaded", function () {
    	window.Litepicker && (new Litepicker({
    		element: document.getElementById('datepicker-icon2'),
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

<!-- modal dialog for create new data -->
<form id="form_simpan" class="card" enctype="multipart/form-data">
    <input type="hidden" name="object_id" value="<?php echo Encryption::encode($object_id) ?>" />
    <div class="modal modal-blur fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Reminder for <?php echo $name ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label required">Reminder Type</label>
                                <div class="input-group input-group-flat">
                                    <select name="reminder_type_id" class="form-control form-select">
                                        <?php
                                        $s2 = "SELECT reminder_type_id, reminder_type_name, category_master_name FROM tbl_reminder_type a INNER JOIN tbl_category_master b using (category_master_id) where a.client_id = '".$_SESSION['client_id']."' ORDER BY category_master_name";
                                        $h2  = mysqli_query($conn,$s2);
                                        if(mysqli_num_rows($h2) == 0)
                                        {
                                            $continue = 0;
                                            echo '<option class="text-danger">WARNING! You must add Reminder Type for this object before saving this data</option>';
                                        }else{
                                            $continue = 1;
                                            while($r2 = mysqli_fetch_assoc($h2)) {
                                        ?>
                                        <option value="<?php echo $r2['reminder_type_id'] ?>"><?php echo $r2['category_master_name'].' &raquo; '.$r2['reminder_type_name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <div class="input-group input-group-flat">
                                    <input type="text" class="form-control ps-0" name="description" />
                                </div>
                                <small class="text-secondary">This is optional.</small>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label required">Document</label>
                                <div class="input-group input-group-flat">
                                    <input type="file" class="form-control ps-0" name="list_upload" id="list_upload" />
                                </div>
                                <small class="text-secondary">For instance: contract document, a photo of vehicle plate number and else. <span class="text-danger"><b>File size should not more than 3 MB and not upload exe, bat or apk file.</b></span></small>
                            </div>
                        </div>
                        <p>&nbsp;</p>
                        <hr/>
                        <b class="text-info">Here You Can Set Expiry Date. Notiflex will send a reminder notification based on expiry date.</b><br/><br/> 
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label required">Start Date</label>
                                <div class="input-icon mb-2">
                                    <input class="form-control" name="start_date" id="datepicker-icon" />
                                    <span class="input-icon-addon"><!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">&nbsp;</div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label required">Expiry Date</label>
                                <div class="input-icon mb-2">
                                    <input class="form-control" name="expired_date" id="datepicker-icon2" />
                                    <span class="input-icon-addon"><!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Or set expiry date by day:</label>
                                <div class="input-group input-group-flat">
                                    <input type="text" size="2" name="expired_day" class="form-control ps-0" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label class="form-label">Duration</label>
                                <div class="input-group input-group-flat">
                                    <select class="form-control form-select" name="expired_duration">
                                        <option value="DAY">Day</option>
                                        <option value="MONTH">Month</option>
                                        <option value="YEAR">YEAR</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <?php if($continue == 0) { echo '<button type="button" class="btn" disabled>Save</button>'; }else{ ?><div id="results"></div><div id="button"></div><?php } ?>
                </div>
            </div>
        </div>
    </div>
</form>

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
      url:"<?php echo $title_name ?>Action?addExpirationData",  
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