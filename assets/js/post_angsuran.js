var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
var get_data;
var data_search;
$(document).ready(function(){
  $('#alert_container').hide();
  $('body').addClass('open');
  $('.blockquote-footer').hide();
  $("#input_report_id").focus();
  $("#input_report_id").keyup(function(){
    var id = $('#input_report_id').val();
    if(id === null || id === ""){
      console.log("Silahkan input report ID");
    } else {
      $('.blockquote-footer').show();
      $('.report_id_label').text(id);
      data_search = 1;
      loadDataAgsTemp(id);
      loadDataSimpTemp(id);
    }
  });

});

function toExcel(){
  if(get_data != 1){
    alert('Silahkan klik "Get Data" dahulu!');
  } else {
    get_data = 0;
    window.open( base_url+"post_angsuran/toExcel" , "_self");
    clear_table();
  }
}

function getDataTemp(){
  $.ajax({
    url : base_url+"post_angsuran/saveDataAgsTemp/",
    type: "GET",
    dataType: "JSON",
    success: function(data){
      if(data){
        get_data = 1;
        $('.blockquote-footer').hide();
        loadDataAgsTemp();
        loadDataSimpTemp();
        $('#input_report_id').val();
      } else {
        alert('Get Data Angsuran Temp Error');
      }
    },
    error: function (jqXHR, textStatus, errorThrown){
      // alert('Get Data Angsuran Temp Error');
    }
  });
}

function loadDataAgsTemp(post_id = ""){
  var url = "";
  if(post_id === ""){
    url = base_url+"post_angsuran/data_post_angsuran/";
  } else {
    url = base_url+"post_angsuran/data_post_angsuran/" + post_id;
  }

  $('#table_post_angsuran_container').html('Loading...');
  $.ajax({
    url : url,
    type: "GET",
    success: function(html){
      $('#table_post_angsuran_container').html(html);
    },
    error: function (jqXHR, textStatus, errorThrown){
      alert('Data Tidak Ditemukan');
      $('.blockquote-footer').hide();
    }
  });
}

function deleteDataAgsTemp(nama, id, row, post_id=""){
  console.clear();
  _nama = nama;
  var p_id = "";
  console.log('data_search '+data_search);
  if(post_id==""){
    p_id = "";
  } else {
    p_id = post_id;
  }
  if(confirm('Apakah Anda yakin akan menghapus data angsuran "'+_nama+'" ?')){
    $.ajax({
      url : base_url+"post_angsuran/del_post_angsuran/",
      type: "POST",
      data: {param: [id, row, p_id]},
      dataType: "JSON",
      success: function(data){
        alert('Data Berhasil Dihapus');
        if (post_id == "") {
          loadDataAgsTemp();
        } else {
          cari();
        }
      },
      error: function (jqXHR, textStatus, errorThrown){
        alert('Error deleting data');
      }
    });
  }
}

function loadDataSimpTemp(post_id = ""){
  var url = "";
  if(post_id === ""){
    url = base_url+"post_angsuran/data_post_simpanan/";
  } else {
    url = base_url+"post_angsuran/data_post_simpanan/" + post_id;
  }

  $('#table_post_simpanan_container').html('Loading...');
  $.ajax({
    url : url,
    type: "GET",
    success: function(html){
      $('#table_post_simpanan_container').html(html);
    },
    error: function (jqXHR, textStatus, errorThrown){
      alert('Data Tidak Ditemukan');
      $('.blockquote-footer').hide();
    }
  });
}

function deleteDataSimpTemp(nama, id, row, post_id=""){
  console.clear();
  console.log('this');
  
  _nama = nama;
  if(confirm('Apakah Anda yakin akan menghapus data simpanan "'+_nama+'" ?')){
    $.ajax({
      url : base_url+"post_angsuran/del_post_simpanan/",
      type: "POST",
      data: {param: [id, post_id]},
      dataType: "JSON",
      success: function(data){
        alert('Data Berhasil Dihapus');
        
        if (post_id == "") {
          loadDataSimpTemp();
        } else {
          cari();
        }
      },
      error: function (jqXHR, textStatus, errorThrown){
        alert('Error deleting data');
      }
    });
  }
}

function clear_table(){
  $('.blockquote-footer').hide();
  data_search = 0;
  $.ajax({
    url : base_url+"post_angsuran/clear_angsuran/",
    type: "GET",
    success: function(html){
      $('#table_post_angsuran_container').html(html);
    }
  });
  $.ajax({
    url : base_url+"post_angsuran/clear_simpanan/",
    type: "GET",
    success: function(html){
      $('#table_post_simpanan_container').html(html);
    }
  });
  console.log('data_search '+data_search);
  $('#input_report_id').val();
}

function cari(){
  var id = $('#input_report_id').val();
  if(id === null || id === ""){
    alert("Silahkan input report ID");
  } else {
    $('.blockquote-footer').show();
    $('.report_id_label').text(id);
    data_search = 1;
    loadDataAgsTemp(id);
    loadDataSimpTemp(id);
  }
}

function doPosting(){
  var report_id = $('#input_report_id').val();
  if(report_id === ""){
    alert('Silahkan masukkan report id');
  } else {
    if (confirm('Apakah Anda yakin utk posting semua data ini ?')) {
      $('#posting_btn').attr('readonly');
      $('#alert_container').show();
      $.ajax({
        url: base_url + "post_angsuran/bulk_posting/",
        type: "POST",
        data: { param: [report_id] },
        dataType: "JSON",
        success: function (data) {
          if (data.status) {
            alert('Posting Berhasil!!!');
            clear_table()
            console.log('data_search ' + data_search);
            $('#alert_container').hide();
            $('#input_report_id').val();
          } else {
            alert('Error Upload Posting');
            $('#alert_container').hide();
          }
          $('#posting_btn').removaAttr('readonly');
        },
        error: function (jqXHR, textStatus, errorThrown) {
          alert('Error Upload Posting');
        }
      });
    }
  }
}

function check_report_existing(){
  $.ajax({
  	url: base_url + "post_angsuran/check_log_existing/" + $('#input_report_id').val(),
    type: "GET",
    dataType: "JSON",
  	success: function (data) {
      if (document.getElementById("table_post_angsuran").tBodies[0].rows.length > 0 && document.getElementById("table_post_simpanan").tBodies[0].rows.length > 0){
        if (data.status) {
          // alert('oke');
          doPosting()
        } else {
        	alert('gagal, data sudah ada');
        }
      } else {
        alert('no data');
      }
  	}
  });
}
