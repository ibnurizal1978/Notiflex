<?php
require_once 'header.php';
$title_name = 'category';
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
                        Category List <span class="badge bg-purple text-purple-fg ms-2"><a href="#" data-bs-toggle="modal" data-bs-target="#modalInfo" title="What is this page for?">?</a></span>
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
                            <td><b>Category Name</b></td>
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
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label required">Category Name</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="category_master_name" class="form-control ps-0" autocomplete="off">
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
                    Categories are a way to effectively segment and organize information, helping us classify and manage various items or documents based on their shared characteristics. In the context of a document reminder system, categories serve as a means to group and differentiate different types of documents.
                    <br/><br/>
                    Example categories you might want to add:
                    <ul>
                        <li>Electronic</li>
                        <li>Vehicle</li>
                        <li>Employee</li>
                        <li>My Clients</li>
                        <li>Vendors</li>
                        <li>...and else</li>
                    </ul>
                    You may type a free text of your category name.
                    <br/><br/>
                    By assigning documents to specific categories, you can streamline retrieval and ensure timely actions, making it easier to track, manage, and respond to various tasks and responsibilities, all while maintaining a well-structured and efficient system for document expiration reminders.</p>
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