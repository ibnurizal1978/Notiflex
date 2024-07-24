$(document).ready(function(){ // Ketika halaman sudah siap (sudah selesai di load)
    // Kita sembunyikan dulu untuk loadingnya
    $("#loading3").hide();
    
    $("#warehouse_master_id3").change(function(){ // Ketika user mengganti atau memilih data provinsi
      $("#pallet3").hide(); // Sembunyikan dulu combobox kota nya
      $("#loading3").show(); // Tampilkan loadingnya
    
      $.ajax({
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "data/warehousePalletDropdown", // Isi dengan url/path file php yang dituju
        data: {warehouse_master_id : $("#warehouse_master_id3").val()}, // data yang akan dikirim ke file yang dituju
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ // Ketika proses pengiriman berhasil
          $("#loading3").hide(); // Sembunyikan loadingnya
          // set isi dari combobox kota
          // lalu munculkan kembali combobox kotanya
          $("#pallet3").html(response.data_kota).show();
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(thrownError); // Munculkan alert error
        }
      });
      });
  });




  $(document).ready(function(){ // Ketika halaman sudah siap (sudah selesai di load)
    // Kita sembunyikan dulu untuk loadingnya
    $("#loading4").hide();
    
    $("#warehouse_master_id4").change(function(){ // Ketika user mengganti atau memilih data provinsi
      $("#pallet4").hide(); // Sembunyikan dulu combobox kota nya
      $("#loading4").show(); // Tampilkan loadingnya
    
      $.ajax({
        type: "POST", // Method pengiriman data bisa dengan GET atau POST
        url: "data/warehousePalletDropdown", // Isi dengan url/path file php yang dituju
        data: {warehouse_master_id : $("#warehouse_master_id4").val()}, // data yang akan dikirim ke file yang dituju
        dataType: "json",
        beforeSend: function(e) {
          if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
        success: function(response){ // Ketika proses pengiriman berhasil
          $("#loading4").hide(); // Sembunyikan loadingnya
          // set isi dari combobox kota
          // lalu munculkan kembali combobox kotanya
          $("#pallet4").html(response.data_kota).show();
        },
        error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
          alert(thrownError); // Munculkan alert error
        }
      });
      });
  });