<?php
require_once 'header.php';
$s = "SELECT product_id, product_name, product_sku, product_minimum_order, product_unit, product_price, product_color, product_material, warehouse_name, warehouse_id, product_description, active_status FROM tbl_product a INNER JOIN tbl_warehouse USING (warehouse_id) WHERE a.client_id = '".$_SESSION['client_id']."' AND a.product_id = '".Encryption::decode($param[1])."' LIMIT 1";
$h = mysqli_query($conn, $s);
$r = mysqli_fetch_assoc($h);

/* create a label for order detail status */
switch ($r['active_status']) {
    case 1:
        $active_status = "<span class='badge badge-outline text-green'>AKTIF</span>";
        break;
    default:
        $active_status = "<span class='badge badge-outline text-danger'>TIDAK AKTIF</span>";
}
?>

<!-- this is for column "credit limit", make separator -->
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
                    <h2 class="page-title">Products Detail:&nbsp;<span class="text-blue"><?php echo $r['product_name'] ?></span></h2>
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
                                <input type="hidden" name="product_id" value="<?php echo Encryption::encode($r['product_id']) ?>">
                                <div class="card-header">
                                    <h3 class="card-title">
                                    Edit Data
                                    </h3>
                                </div>                                    
                                <div class="card-body">
                                    <div class="row row-cards">
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label required">Product Name</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" name="product_name" value="<?php echo $r['product_name'] ?>" class="form-control ps-0" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label required">SKU</label>
                                                <div class="input-group input-group-flat">
                                                    <input type="text" name="product_sku" value="<?php echo $r['product_sku'] ?>" class="form-control ps-0" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Minimum Order Qty</label>
                                                <input type="text" name="product_minimum_order" value="<?php echo $r['product_minimum_order'] ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Selling Unit</label>
                                                <input type="text" name="product_unit"  value="<?php echo $r['product_unit'] ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Price</label>
                                                <input type="text" name="product_price" value="<?php echo number_format($r['product_price'],0,",",".") ?>" class="form-control" onkeyup = "javascript:this.value=Comma(this.value);">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Color</label>
                                                <input type="text" name="product_color"  value="<?php echo $r['product_color'] ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Material</label>
                                                <input type="text" name="product_material"  value="<?php echo $r['product_material'] ?>" class="form-control">
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
                                                    <option <?php if($r['warehouse_id'] ==$row_p['warehouse_id']) { echo "selected"; } ?> value="<?php echo $row_p['warehouse_id'] ?>"><?php echo $row_p['warehouse_name']?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label class="form-label">Product Description</label>
                                                <input type="text" name="product_description"  value="<?php echo $r['product_description'] ?>" class="form-control">
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
      url:"productsAction?edit",  
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