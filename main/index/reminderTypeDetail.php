<?php
require_once 'header.php';
$s = "SELECT reminder_type_id, reminder_type_name, category_master_id, category_master_name FROM tbl_reminder_type a INNER JOIN tbl_category_master b USING (category_master_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND reminder_type_id = '".Encryption::decode($param[1])."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);
$title_name = 'reminderType';
?>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle"><?php echo $title_name ?></div>
                    <h2 class="page-title">Detail Data:&nbsp;<span class="text-blue"><?php echo $r['reminder_type_name'] ?></span></h2>
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
                                <input type="hidden" name="id" value="<?php echo Encryption::encode($r['reminder_type_id']) ?>">
                                <div class="card-header">
                                    <h3 class="card-title">
                                    Edit Data
                                    </h3>
                                </div>                                    
                                <div class="card-body">
                                    <div class="row row-cards">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label required">Reminder Type Name</label>
                                                <input type="text" name="reminder_type_name" class="form-control" value="<?php echo $r['reminder_type_name'] ?>" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label required">From Category</label>
                                                <select name="category_master_id" class="form-control form-select">
                                                    <?php
                                                    $sql_p = "SELECT category_master_id, category_master_name FROM tbl_category_master where client_id = '".$_SESSION['client_id']."' ORDER BY category_master_name";
                                                    $h_p  = mysqli_query($conn,$sql_p);
                                                    while($row_p = mysqli_fetch_assoc($h_p)) {
                                                    ?>
                                                    <option <?php if($r['category_master_id'] ==$row_p['category_master_id']) { echo "selected"; } ?> value="<?php echo $row_p['category_master_id'] ?>"><?php echo $row_p['category_master_name']?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <p>&nbsp;</p>
                                        <hr/>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label text-info">Reminder Day for This Type</label>
                                                <?php
                                                $s2 = "SELECT reminder_duration_id, reminder_type_id, reminder_day, reminder_via FROM tbl_reminder_duration WHERE reminder_type_id = '".$r['reminder_type_id']."' AND client_id = '".$_SESSION['client_id']."' ORDER BY reminder_day DESC";
                                                $h2 = mysqli_query($conn, $s2);
                                                if(mysqli_num_rows($h2) == 0)
                                                {
                                                echo '<span class="text-danger">No reminder yet. create at least one reminder by adding below</span>';
                                                }else{
                                                while($r2 = mysqli_fetch_assoc($h2))
                                                {
                                                    $s3 = "SELECT DISTINCT full_name FROM tbl_user a INNER JOIN tbl_group_receiver_detail b USING (user_id) INNER JOIN tbl_group_receiver c USING (group_receiver_id) INNER JOIN tbl_reminder_duration d USING (group_receiver_id) WHERE reminder_duration_id = '".$r2['reminder_duration_id']."' AND a.client_id = '".$_SESSION['client_id']."'";
                                                    $h3 = mysqli_query($conn, $s3);
                                                    $full_name    = "";
                                                    while($r3 = mysqli_fetch_assoc($h3))
                                                    {
                                                    $full_name .= $r3['full_name'].", ";
                                                    
                                                    }
                                                    $full_name    = substr($full_name,0,-2);
                                                    echo '<span style="line-height:2em">Remind in <b>'.$r2['reminder_day'].'</b> day(s) before expire to: <i class="text-blue">'.$full_name.'</i> <a class="badge bg-blue text-blue-fg text-uppercase" href=reminderTypeAction?delete?'.Encryption::encode($r2['reminder_duration_id']).'?'.Encryption::encode($r2['reminder_type_id']).'>Delete</a></span><br/>';
                                                }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <p>&nbsp;</p>
                                    <hr/>
                                    <!-- add new reminder -->
                                    <div class="row" id="receiver">
                                        <h4 class="text-info">You may add a new reminder day here</h4>
                                        <div class="col-lg-2">
                                            <div class="mb-2">
                                                <label class="form-label required">Day</label>
                                                <input type="text" size=2 name="reminder_day[]" class="form-control ps-0" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-6">
                                                <label class="form-label required">It will send to  group receiver:</label>
                                                <select name="group_receiver_id[]" class="form-control form-select">
                                                    <?php
                                                    $sql_p = "SELECT group_receiver_id, group_name FROM tbl_group_receiver where client_id = '".$_SESSION['client_id']."' ORDER BY group_name";
                                                    $h_p  = mysqli_query($conn,$sql_p);
                                                    while($row_p = mysqli_fetch_assoc($h_p)) {
                                                    ?>
                                                    <option value="<?php echo $row_p['group_receiver_id'] ?>"><?php echo $row_p['group_name']?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label class="form-label required">Via</label>
                                                <input type="text" size=4 class="form-control ps-0" placeholder="EMAIL" disabled>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="mb-2">
                                                <div id="formfield1"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-6">
                                                <div id="formfield2"></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <div id="formfield3"></div>
                                            </div>
                                        </div>

                                        <div class="controls">
                                            <a href="#receiver" class="add" onclick="add()">Add Reminder Day</a> | 
                                            <a href="#receiver" class="remove" onclick="remove()">Remove</a>
                                        </div>
                                    </div>
                                    <!-- end add new reminder -->

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

<!-- this is for select dropdown on javascript adding reminder day -->
<?php
$s = "SELECT group_receiver_id, group_name FROM tbl_group_receiver where client_id = '".$_SESSION['client_id']."' ORDER BY group_name";
$h = mysqli_query($conn, $s);
$data    = "";
while($r     = mysqli_fetch_assoc($h)) {
  $data .= "{ group_receiver_id:".$r['group_receiver_id'].", group_name: '".$r['group_name']."'},";
}
$data    = substr($data,0,-1);
?>
<script>
    var formfield1 = document.getElementById('formfield1');
    var formfield2 = document.getElementById('formfield2');
    var formfield3 = document.getElementById('formfield3');

function add(){
    var newField1 = document.createElement('input');
    newField1.setAttribute('type','text');
    newField1.setAttribute('name','reminder_day[]');
    newField1.setAttribute('class','form-control ps-0');
    newField1.setAttribute('size',2);
    formfield1.appendChild(newField1);

    var newField2 = document.createElement('select');
    var customers = [ <?php echo $data ?> ];

    for (var i = 0; i < customers.length; i++) {
            var option = document.createElement("OPTION");
            option.innerHTML = customers[i].group_name;
            option.value = customers[i].group_receiver_id;
            newField2.options.add(option);
        }
    newField2.setAttribute('name','group_receiver_id[]');
    newField2.setAttribute('class','form-control ps-0');
    formfield2.appendChild(newField2);

    var newField3 = document.createElement('input');
    newField3.setAttribute('type','text');
    newField3.setAttribute('disabled','disabled');
    newField3.setAttribute('class','form-control ps-0');
    newField3.setAttribute('size',4);
    newField3.setAttribute('placeholder','EMAIL');
    formfield3.appendChild(newField3);
    return false;
}

function remove(){
    var input_tags1 = formfield1.getElementsByTagName('input');
    var input_tags2 = formfield2.getElementsByTagName('select');
    var input_tags3 = formfield3.getElementsByTagName('input');
    if(input_tags1.length > 0) {
        formfield1.removeChild(input_tags1[(input_tags1.length) - 1]);
        formfield2.removeChild(input_tags2[(input_tags2.length) - 1]);
        formfield3.removeChild(input_tags3[(input_tags3.length) - 1]);
    }
}
</script>

<?php require_once 'footer.php' ?>