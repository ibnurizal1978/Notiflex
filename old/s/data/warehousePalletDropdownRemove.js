$(document).ready(function(){ // Ketika halaman sudah siap (sudah selesai di load)
    // Kita sembunyikan dulu untuk loadingnya
    $("#loading2").hide();
    
    $("#warehouse_master_id2").change(function(){ // Ketika user mengganti atau memilih data provinsi
      $("#pallet2").hide(); // Sembunyikan dulu combobox kota nya
      $("#loading2").show(); // Tampilkan loadingnya
    
      $.ajax({
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "data/warehousePalletDropdown", // Isi dengan url/path file php yang dituju
        data: {warehouse_master_id : $("#warehouse_master_id2").val()}, // data yang akan dikirim ke file yang dituju
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ // Ketika proses pengiriman berhasil
          $("#loading2").hide(); // Sembunyikan loadingnya
          // set isi dari combobox kota
          // lalu munculkan kembali combobox kotanya
          $("#pallet2").html(response.data_kota).show();
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(thrownError); // Munculkan alert error
        }
      });
      });
  });