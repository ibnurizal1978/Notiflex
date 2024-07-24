<?php
require_once 'header.php';
$title_name = 'list';
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
                        Data List <span class="badge bg-purple text-purple-fg ms-2"><a href="#" data-bs-toggle="modal" data-bs-target="#modalInfo" title="What is this page for?">?</a></span>
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
                            <td><b>Data Name</b></td>
                            <td><b>Category</b></td>
                            <td><b>Expire Date</b></td>
                            <td><b>Days to Expire</b></td>
                            <td width="20%"><b>Action</b></td>
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

          { "data": "name" },
          { "data": "reminder_type_name" },
          { "data": "expired_date" },
          { "data": "difference" },
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
                            <label class="form-label required">Data Name</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="name" class="form-control ps-0" autocomplete="off">
                            </div>
                            <small class="text-secondary">Name your data, for instance: "invoice Adera Corp.", "work contract for Steve", "renewal Ferrari plate number" and else</small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Short Description</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="description" class="form-control ps-0" autocomplete="off">
                            </div>
                            <small class="text-secondary">This is optional</small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label required">Category</label>
                            <div class="input-group input-group-flat">
                                <select name="reminder_type_id" class="form-control form-select">
                                    <?php
                                    $sql_p = "SELECT reminder_type_id, reminder_type_name, category_master_name FROM tbl_reminder_type a INNER JOIN tbl_category_master b USING (category_master_id) where a.client_id = '".$_SESSION['client_id']."' ORDER BY category_master_name";
                                    $h_p  = mysqli_query($conn,$sql_p);
                                    while($row_p = mysqli_fetch_assoc($h_p)) {
                                    ?>
                                    <option value="<?php echo $row_p['reminder_type_id'] ?>"><?php echo $row_p['category_master_name'].' &raquo; '.$row_p['reminder_type_name']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label required">Additional Data</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="additional_data" class="form-control ps-0" autocomplete="off">
                            </div>
                            <small class="text-secondary">Additional data can be vehicle plate number, laptop serial number, employee's phone and else</small>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label required">Document</label>
                            <div class="input-group input-group-flat">
                                <input type="file" class="form-control ps-0" name="list_upload" id="list_upload" />
                            </div>
                            <small class="text-secondary">For instance: contract document, a photo of vehicle plate number and else. File size should not more than 3 MB.</small>
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
                    A data list is a collection of data items that are organized in a specific category you have created. Data list consists of document files, expiry dates, and categories. This data is inserted by you and can be used to track and manage document expiration date.

                    Everytime data list updated, you still able to view previous document. This can  improve the efficiency and organization of your documents, and to provide clear expiration history.
                </p>
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