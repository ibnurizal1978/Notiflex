<?php
require_once 'header.php';
$title_name = 'reminderType';
?>
<link rel="stylesheet" href="../../assets/css/dataTables.css" />
<script src="../../assets/js/dataTables.js"></script>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle"><?php echo $title_name ?></div>
                    <h2 class="page-title">
                        Reminder Type List <span class="badge bg-purple text-purple-fg ms-2"><a href="#" data-bs-toggle="modal" data-bs-target="#modalInfo" title="What is this page for?">?</a></span>
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            New
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row g-2 align-items-center">  

                <!-- this is for search bar -->
                <form ui-jp="parsley" method="GET" action="<?php echo $title_name ?>">
                    <input type="hidden" name="s" value="Y">
                    <div class="col-12">
                        <div class="card card-md">
                            <div class="card-stamp card-stamp-lg">
                                <div class="card-stamp-icon bg-primary">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/ghost -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                        <path d="M21 21l-6 -6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <h3 class="h2 text-azure">Search</h3>
                                        <div class="row row-cards">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label class="form-label">type a keyword</label>
                                                    <input type="text" class="form-control" name="txt_search" value="<?php echo @$_GET['txt_search'] ?>" />
                                                </div>
                                            </div>
                                            <div class="col-sm-2 col-md-2">
                                                <div class="mb-2">
                                                <label class="form-label">&nbsp;</label>
                                                <input type="submit" class="btn btn-primary" value="Cari" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- end this is for search bar -->


                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                    </div>

                    <table id="example1" class="table table-hover table-responsive table-product card-table table-vcenter datatable">
                        <thead>
                        <tr class="bg-blue-lt">
                            <td><b>Reminder Type Name</b></td>
                            <td><b>Category Master</b></td>
                            <td width="10%"><b>Action</b></td>
                        </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
  $(function () {
    $("#example1").DataTable({
      dom: 'Bfrtip',
      searching: false,
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      "pageLength": 20,
      lengthChange: false,
      ajax:{
               "url": "data/<?php echo $title_name ?>?s=<?php echo @$_GET['s'] ?>&txt_search=<?php echo @$_GET['txt_search'] ?>",
               "dataType": "json",
               "type": "POST"
             },
      
        columns: [

          { "data": "reminder_type_name" },
          { "data": "category_master_name" },
          { "data": "aksi" }
        ],

      buttons: ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>

<?php require_once 'footer.php' ?>

<!-- modal dialog for create new data -->
<form id="form_simpan" class="card">
<div class="modal modal-blur fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label required">Reminder Type Name</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="reminder_type_name" class="form-control ps-0" autocomplete="off">
                            </div>
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
                                <option value="<?php echo $row_p['category_master_id'] ?>"><?php echo $row_p['category_master_name']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <hr/>
                <h5>Reminder day and receiver</h5>
                <p>
                    You must set how many days before expired date the system will send an email notification to a selected group receiver.
                    <br/><br/>
                    For instance, if you want this reminder type to notify 20 days before its expiration date to a group receiver, type "20" on the "Day" column, choose which Group Receiver to send. You may add multiple different day or different Group Receiver as you need.
                    <br/><br/>
                </p>
                <div class="row">
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
                        <a href="#" class="add" onclick="add()">Add Reminder Day</a> | 
                        <a href="#" class="remove" onclick="remove()">Remove</a>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
            <div id="results"></div><div id="button"></div>
            </div>
        </div>
    </div>
</div>
</form>


<!-- modal dialog for module info -->
<div class="modal modal-blur fade" id="modalInfo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Module Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    "Reminder Type" are specific subcategories or classifications within a broader Category Master system. In this context, reminder types serve as detailed labels or classifications that help specify the nature of a reminder associated with a particular category.
                    <br/><br/>
                    Example:
                    <ul>
                        <li>Category Electronic; Reminder type might be "Laptop warranty", "Refrigerator"</li>
                        <li>Category Vehicle; Remidner type might be "License plate", "oil change maintenance"</li>
                        <li>Category Employee; Reminder type might be "HSE Certification", "Employee Contract", "Computer Certificate"</li>
                        <li>Category Clients; Reminder type might be "1 year contract", "14 days invoice"</li>
                        <li>Category Vendors; Reminder type might be "1 year lease contract", "30 days cleaning service maintenance"</li>
                        <li>...and else</li>
                    </ul>
                    You may type a free text of your reminder type.
                    <br/><br/>
                    By using reminder types, you can effectively categorize and manage reminders in a structured manner, ensuring that each reminder is appropriately defined and tracked within its respective category.</p>
            </div>
        </div>
    </div>
</div>

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
      url:"<?php echo $title_name ?>Action?add",  
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