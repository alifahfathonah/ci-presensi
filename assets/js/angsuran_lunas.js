var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function() {
  form_validation();

  open_modal_pelunasan();

  $('#input_tanggal_trans').datetimepicker({
    setLocale: 'id',
    format: 'd M Y H:i'
  });

  filter_date = false;
  fetch_data();

});



function fetch_data(start_date='', end_date=''){
  table = $('#table_pelunasan').DataTable({
    "lengthChange" : true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "autoWidth": true,
    "processing": true,
    "serverSide": true,
    "order": [],
    "dom" :
    "<'row'<'col-sm-12'<'col-md-6 filter_container'><'col-md-6 text-right pol_kiri'l>>>" +
    "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6'>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'>>",
    "buttons": ['colvis'],
    "ajax": {
      "url": base_url+"angsuran_lunas/ajax_list/",
      "type": "POST",
      "data" : function (data) {
        data.start_date     = start_date;
        data.end_date       = end_date;
        data.id             = $('#hidden_id').val();
      }
    },

    "columnDefs": [
      {
        "targets": [ -1 ],
        "orderable": false,
      },
    ],
  });

  $('.export_button_group_container .btn').removeClass('btn-secondary');
  $('.export_button_group_container .btn').addClass('btn-sm btn-outline-secondary');
  $('.filter_container').html(
    '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">' +
    '<button onclick="open_modal_pelunasan()" type="button" class="btn btn-sm btn-success" ><b class="fa fa-plus"></b></button>' +
    '<button onclick="reload_table()" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-refresh"></b></button>' +
    '<div id="reportrange" class="btn btn-sm btn-outline-secondary ">' +
    '<b class="fa fa-filter"></b>'+
    '</div>'+
    '</div>'
  );
  //daterange datatables
  $(function() {
    var start = moment("2017-01-01 12:34:16");
    var end = moment("2018-03-03 10:08:07");

    function cb(start, end) {
      $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'This Year': [moment().startOf('year').startOf('month'), moment().endOf('year').endOf('month')],
        'Last Year': [moment().subtract('year', 1).startOf('year').startOf('month'), moment().subtract('year', 1).endOf('year').endOf('month')]
      }
    }, cb);

    cb(start, end);

  });


  $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
    var start = picker.startDate.format('YYYY-MM-DD');
    var end = picker.endDate.format('YYYY-MM-DD');
    end_filter_date = end;
    start_filter_date = start;
    filter_date = true;
    $('#table_pelunasan').DataTable().destroy();
    fetch_data(start, end);
  });
}

function reload_table(){
  window.open(base_url + 'angsuran_lunas/index/'+$('#hidden_id').val(), "_self");
}

function open_modal_pelunasan(){
  save_method = "add";
  var tgl_bayar_val = $('#tgl_bayar').val();
  $.ajax({
    type	: "POST",
    url		: base_url + "angsuran/get_ags_ke/" + $('#hidden_id').val(),
    data 		: { tgl_bayar: tgl_bayar_val},
    success	: function(result){
      var myObj = JSON.parse(result);

      if(myObj.total_tagihan <= 0) {
        alert('Maaf, Sisa tagihan atau Sisa Angsuran Anggota NOL');
      } else {
        $('#input_sisa_tagihan').val(myObj.total_tagihan);
        $('#input_jumlah_bayar').val(myObj.total_tagihan);
        $('#input_tanggal_trans').val(current_time());
        $('#modal_pelunasan').modal('show');
      }
    },
    error : function() {
      alert('Terjadi Kesalahan Kneksi');
    }
  });
}

function current_time(){
  arrbulan = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Okt","Nov","Des"];
  date = new Date();
  millisecond = date.getMilliseconds();
  detik = date.getSeconds();
  menit = date.getMinutes();
  jam = date.getHours();
  hari = date.getDay();
  tanggal = ('0' + date.getDate()).slice(-2);
  bulan = date.getMonth();
  tahun = date.getFullYear();
  return tanggal+" "+arrbulan[bulan]+" "+tahun+" "+jam+":"+menit;
  // document.write(tanggal+" "+arrbulan[bulan]+" "+tahun+" "+jam+":"+menit);
}

function form_validation(){
  $('#form_pelunasan').on('submit', function(event){
    event.preventDefault();
    event.stopPropagation();
    $.ajax({
      url: base_url + "angsuran_lunas/validation/"+save_method,
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      beforeSent:function(){
        $('#btn-save').attr('disabled', true);
      },
      success: function(data){
        if(data.error){
          console.log(data);
          alert('Gagal tambah data. Input data tidak valid.');

          if(data.simpan_ke_kas_error != ""){
            $('#simpan_ke_kas_error').html(data.simpan_ke_kas_error);
            $('#simpan_ke_kas').addClass('is-invalid');
          }else {
            $('#simpan_ke_kas_error').html('');
            $('#simpan_ke_kas').removeClass('is-invalid');
            $('#simpan_ke_kas').addClass('is-valid');
          }

          if(data.input_jumlah_bayar_error != ""){
            $('#input_jumlah_bayar_error').html(data.input_jumlah_bayar_error);
            $('#input_jumlah_bayar').addClass('is-invalid');
          }else {
            $('#input_jumlah_bayar_error').html('');
            $('#input_jumlah_bayar').removeClass('is-invalid');
            $('#input_jumlah_bayar').addClass('is-valid');
          }

        }
        if(data.success){
          $('p').removeClass('text-danger');

          $('#simpan_ke_kas').removeClass('is-invalid');
          $('#simpan_ke_kas').removeClass('is-valid');
          $('#simpan_ke_kas_error').html('');

          $('#input_jumlah_bayar').removeClass('is-invalid');
          $('#input_jumlah_bayar').removeClass('is-valid');
          $('#input_jumlah_bayar_error').html('');

          save();

          $('#form_pelunasan')[0].reset();
        }
        $('#btn-save').attr('disabled', false);
      }
    });
  });
}

function isNumberKey(evt){
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode != 46 && charCode > 31
    && (charCode < 48 || charCode > 57))
    return false;
    return true;
}

function save(){
  var url;
  var success_message;
  var error_message;

  console.log('tanggal '+$('#input_tanggal_trans').val());

  if(save_method == "add") {
    url             = base_url + "angsuran_lunas/save/";
    success_message = 'Successfully added ';
    error_message   = 'Insert data failed';
  } else {
    url             = base_url + "angsuran_lunas/update/";
    success_message = 'Successfully updated ';
    error_message   = 'Update data failed';
  }

  var form = $('#form_pelunasan')[0];
  var formData = new FormData(form);
  $.ajax({
    url: url,
    method: 'POST',
    data: formData,
    dataType: 'json',
    contentType: false,
    processData: false,
    success: function(data){
      if(data.status){
        $('#modal_pelunasan').modal('hide');
        // reload_table();
      } else {
        $('#modal_pelunasan').modal('hide');
        alert('Gagal input data. Silahkan coba refresh kembali halaman ini');
      }
    }
  });
}


function delete_data(id, kode){
  var url;
  if(confirm('Apakah Anda akan menghapus data dengan kode: '+kode+' ?')){
    var id        = id;
    var master_id = $('#hidden_id').val();
    var reason = prompt("Silahkan input alasan hapus data", "-");

    if (reason == null || reason == "") {

    } else {
      var parameter = [id, master_id, reason];
      url = base_url + "angsuran_lunas/delete/";
      $.ajax({
        url: url,
        method: 'POST',
        data: {param: parameter},
        dataType: 'json',
        success: function(data){
          if(data.status){
            var txt;
            if (confirm("Data "+kode+" berhasil Dihapus")) {
              reload_table();
            }
          } else {
            alert('Oups. Hapus data gagal');
          }
        }
      });
    }
  }
}
