<?php require_once 'header.php'; ?>
<link rel="stylesheet" href="../../assets/css/dataTables.css" />
<script src="../../assets/js/dataTables.js"></script>

<link href="../../assets/css/tabler.min.css?1692870487" rel="stylesheet"/>
<link href="../../assets/css/tabler-flags.min.css?1692870487" rel="stylesheet"/>
<link href="../../assets/css/tabler-payments.min.css?1692870487" rel="stylesheet"/>
<link href="../../assets/css/tabler-vendors.min.css?1692870487" rel="stylesheet"/>
<link href="../../assets/css/demo.min.css?1692870487" rel="stylesheet"/>

<!-- this is a style for multiple select dropdown on product link modal dialog -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<!-- end this is a style for multiple select dropdown on product link modal dialog -->

<!-- this is for column "product price", make separator -->
<script type='text/javascript'>
function Comma(Num)
 {
       Num += '';
       Num = Num.replace(/,/g, '');
       x = Num.split('.');
       x1 = x[0];
       x2 = x.length > 1 ? '.' + x[1] : '';

         var rgx = /(\d)((\d{3}?)+)$/;

       while (rgx.test(x1))
       x1 = x1.replace(rgx, '$1' + ',' + '$2');    
       return x1 + x2;              
 }
</script>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">Products</div>
                    <h2 class="page-title">
                        Products List
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
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal2">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cloud-upload" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1"></path><path d="M9 15l3 -3l3 3"></path><path d="M12 12l0 9"></path></svg>
                            Upload File
                        </a>
                    </div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-none d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal3">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 15l6 -6"></path><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464"></path><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463"></path></svg>
                            Link Product
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
                <form ui-jp="parsley" method="GET" action="products">
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
                                        <h3 class="h2 text-azure">Pencarian Data</h3>
                                        <div class="row row-cards">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label class="form-label">Ketik nama produk, kemasan atau deskripsi</label>
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
                            <td><b>Product Name</b></td>
                            <td><b>Material</b></td>
                            <td><b>Warehouse</b></td>
                            <td><b>Aktif?</b></td>
                            <td width="10%"><b>Tindakan</b></td>
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
               "url": "data/products?s=<?php echo @$_GET['s'] ?>&txt_search=<?php echo @$_GET['txt_search'] ?>",
               "dataType": "json",
               "type": "POST"
             },
      
        columns: [

          { "data": "product_name" },
          { "data": "product_material" },
          { "data": "warehouse_name" },
          { "data": "active_status" },
          { "data": "aksi" }
        ],

      buttons: ["copy", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>

<?php require_once 'footer.php' ?>

<!-- modal dialog for create new -->
<form id="form_simpan" class="card">
<div class="modal modal-blur fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label required">Product Name</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="product_name" class="form-control ps-0" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label required">SKU</label>
                            <div class="input-group input-group-flat">
                                <input type="text" name="product_sku" class="form-control ps-0" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Minimum Order Qty</label>
                            <input type="text" name="product_minimum_order" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Selling Unit</label>
                            <input type="text" name="product_unit" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="text" name="product_price" class="form-control" onkeyup = "javascript:this.value=Comma(this.value);">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="text" name="product_color" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Material</label>
                            <input type="text" name="product_material" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label required">Warehouse</label>
                            <select name="warehouse_id" class="form-control form-select">
                                <?php
                                $sql_p = "SELECT warehouse_id, warehouse_name FROM tbl_warehouse where client_id = '".$_SESSION['client_id']."' ORDER BY warehouse_name";
                                $h_p  = mysqli_query($conn,$sql_p);
                                while($row_p = mysqli_fetch_assoc($h_p)) {
                                ?>
                                <option value="<?php echo $row_p['warehouse_id'] ?>"><?php echo $row_p['warehouse_name']?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">Product Description</label>
                            <input type="text" name="product_description" class="form-control">
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

<!-- modal dialog for upload file -->
<div class="modal modal-blur fade" id="modal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload New Data Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">Before uploading a file, make sure the column format is similar with this sample file:</label>
                            <div class="input-group input-group-flat">
                                <a href="../../assets/templates/product-upload-template-xlsx.xlsx">Download template file</a>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <b>How to use our template file?
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <ul>
                                <li>Row A2 (Product Name): <span class="text-secondary small lh-base"><b>Mandatory</b>. Type your product name here. Free text format.</span></li>
                                <li>Row B2 (Product SKU): <span class="text-secondary small lh-base"><b>Mandatory</b>. SKU (Stock Keeping Unit). Free text format.</span></li>
                                <li>Row C2 (Minimum Order): <span class="text-secondary small lh-base">Type number only.</span></li>
                                <li>Row D2 (Product Unit): <span class="text-secondary small lh-base">Example: 'Pieces' or 'Ton' or 'KG' or 'Dozen' or else. Free text format.</span></li>
                                <li>Row E2 (Price): <span class="text-secondary small lh-base">Example: 'Black', or you may also type 'Black, White and green'. Free text format.</span></li>
                                <li>Row F2 (Color): <span class="text-secondary small lh-base">Type your product name here. Free text format.</span></li>
                                <li>Row G2 (Warehouse): <span class="text-secondary small lh-base"><b>Mandatory</b>. Type warehouse ID. See your warehouse the ID below.</span></li>
                                <li>Row H2 (Product Description): <span class="text-secondary small lh-base">Type description here. Free text format.</span></li>
                            </ul>
                        </div>
                    </div>
                    <hr/>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label class="form-label">Warehouse ID</label>
                                <?php
                                $sql_p = "SELECT warehouse_id, warehouse_name FROM tbl_warehouse where client_id = '".$_SESSION['client_id']."' ORDER BY warehouse_name";
                                $h_p  = mysqli_query($conn,$sql_p);
                                while($row_p = mysqli_fetch_assoc($h_p)) {
                                ?>
                                <?php echo "WarehouseID: <span class=text-danger>".$row_p['warehouse_id']."</span> (".$row_p['warehouse_name'].")"?><br/>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <br/>
                    <br/>
                    <div id="results2"></div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <form id="export_excel">
                                <label class="form-label required">Ready? Choose Your Product File</label>
                                <div class="input-group input-group-flat">
                                    <input type="file" class="form-control ps-0" name="excel_file" id="excel_file" accept=".xlsx" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal dialog for product link -->
<form id="form_simpan3">
    <div class="modal modal-blur fade" id="modal3" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Link Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">             
                    <div id="results3"></div>
                    <div class="row">
                        <p>Choose product and buyer to link. You may repeat the action for multiple product linking.</p>
                        <hr/>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label required">Product</label>
                                    <select name="product_id" class="form-control form-select">
                                        <?php
                                        $sql_p = "SELECT product_id, product_name FROM tbl_product where active_status = 1 AND client_id = '".$_SESSION['client_id']."' ORDER BY product_name";
                                        $h_p  = mysqli_query($conn,$sql_p);
                                        while($row_p = mysqli_fetch_assoc($h_p)) {
                                        ?>
                                        <option value="<?php echo $row_p['product_id'] ?>"><?php echo $row_p['product_name']?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label required">Buyer</label>
                                <select name="client_id" class="form-control form-select">
                                    <?php
                                    $sql_p = "SELECT client_id, client_business_name FROM tbl_client where active_status = 1 AND owner_id = '".$_SESSION['client_id']."' ORDER BY client_business_name";
                                    $h_p  = mysqli_query($conn,$sql_p);
                                    while($row_p = mysqli_fetch_assoc($h_p)) {
                                    ?>
                                    <option value="<?php echo $row_p['client_id'] ?>"><?php echo $row_p['client_business_name']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="results3"></div><div id="button3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function(){

    //this is for add new
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
        url:"productsAction?add",  
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
  
    //this is for upload using file
    $('#excel_file').change(function(){  
        $('#export_excel').submit(); 
        $("#results2").html('<span class="text-info">Uploading data...</span>'); 
    });  
    $('#export_excel').on('submit', function(event){  
        event.preventDefault();  
        $.ajax({  
        url:"productsAction?upload",  
        method:"POST",  
        data:new FormData(this),  
        contentType:false,  
        processData:false,  
        success:function(data){ 
            $('#results2').html(data);  
            $('#excel_file').val('');  
        }  
        });  
    });
    
    //this is for link product
    $("#button3").html('<?php echo $btn_save2 ?>');  
    $('#submit_data3').click(function(){  
        $('#form_simpan3').submit(); 
        $("#results3").html('');
    });  
    $('#form_simpan3').on('submit', function(event){
        $("#results3").html(''); 
        $("#button3").html('<?php echo $btn_save2_loading ?>');  
        event.preventDefault();  
        $.ajax({  
        url:"productsAction?link",  
        method:"POST",  
        data:new FormData(this),  
        contentType:false,  
        processData:false,  
        success:function(data){ 
            $('#results3').html(data);
            $("#button3").html('<?php echo $btn_save2 ?>');  
        }  
        });  
    });

});  
</script>