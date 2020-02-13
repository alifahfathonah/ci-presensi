var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function() {

  $('#input_simpanan_wajib_hidden').hide();
  $('#input_simpanan_sukarela_hidden').hide();

  form_validation();

  open_modal_bayar_angsuran();

  $('#input_tanggal_trans').datetimepicker({
    setLocale: 'id',
    format: 'd M Y H:i'
  });

  filter_date = false;
  fetch_data();
  console.clear();
});

function opsiBayarSimpanan(){
  var val    = $("input[name='inline-radios']:checked"). val();
  var simwa  = $('#input_simpanan_wajib_hidden').val();
  var simsuk = $('#input_simpanan_sukarela_hidden').val();

  console.log(val);
  if(val === 'y'){
    $('#input_simpanan_wajib').val(simwa);
    $('#input_simpanan_sukarela').val(simsuk);
  } else {
    $('#input_simpanan_wajib').val(0);
    $('#input_simpanan_sukarela').val(0);
  }
}

function fetch_data(start_date='', end_date=''){
  table = $('#table_angsuran').DataTable({
    "lengthChange" : true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "autoWidth": true,
    "processing": true,
    "serverSide": true,
    "order": [],
    "dom" :
    "<'row'<'col-sm-12'<'col-md-6 pol_kiri'l><'col-md-6 text-right export_button_group_container'B>>>" +
    "<'row'<'col-sm-12 col-md-6 filter_container'><'col-sm-12 col-md-6'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    "buttons": ['colvis'],
    "ajax": {
      "url": base_url+"angsuran/ajax_list/",
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
    '<button onclick="open_modal_bayar_angsuran()" type="button" class="btn btn-sm btn-success" ><b class="fa fa-plus"></b></button>' +
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
    $('#table_angsuran').DataTable().destroy();
    fetch_data(start, end);
  });
}

function reload_table(){
  window.open(base_url + 'angsuran/index/'+$('#hidden_id').val(), "_self");
}

function hitung_denda() {
  $('#input_denda').val('0');
  val_tgl_bayar 	= $('#input_tanggal_trans').val();
  val_id_bayar 	  = $('#id_bayar').val();
  $.ajax({
    type	: "POST",
    url		: base_url + "angsuran/get_ags_ke/" + $('#hidden_id').val(),
    data 	: { tgl_bayar : val_tgl_bayar, id_bayar : val_id_bayar},
    success	: function(result){
      console.log(result);
      var result = eval('('+result+')');
      $('#denda').text(result.denda);
      $('#denda_val').val(result.denda);
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

function open_modal_bayar_angsuran(){
  save_method = "add";
  $.ajax({
    type	: "POST",
    url		: base_url + "angsuran/get_ags_ke/" + $('#hidden_id').val(),
    success	: function(result){
      var result = eval('('+result+')');
      if((result.sisa_ags == 0) || (result.total_tagihan <= 0)) {
        alert('Klik "Validasi Lunas" untuk Pelunasan dan membayar Tagihan Denda');
      } else {
        $('#input_angsuran_ke').val(result.ags_ke);
        $('#input_sisa_angsuran').val(result.sisa_ags);
        $('#input_sisa_tagihan').val(result.sisa_tagihan);
        $('#jml_bayar').val(result.sisa_pembayaran);
        $('#jml_kas').val(result.total_tagihan);
        $('#input_tanggal_trans').val(current_time());
        hitung_denda();
        $('#modal_bayar_angsuran').modal('show')
      }
    },
    error : function() {
      alert('Terjadi Kesalahan Kneksi');
    }
  });
}

function form_validation(){
  $('#form_pembayaran_angsuran').on('submit', function(event){
    event.preventDefault();
    event.stopPropagation();
    $.ajax({
      url: base_url + "angsuran/validation/"+save_method,
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

        }
        if(data.success){
          $('p').removeClass('text-danger');

          $('#simpan_ke_kas').removeClass('is-invalid');
          $('#simpan_ke_kas').removeClass('is-valid');
          $('#simpan_ke_kas_error').html('');

          save();

          $('#form_pembayaran_angsuran')[0].reset();
        }
        $('#btn-save').attr('disabled', false);
      }
    });
  });
}

function save(){
  var url;
  var success_message;
  var error_message;

  console.log('tanggal '+$('#input_tanggal_trans').val());

  if(save_method == "add") {
    url             = base_url + "angsuran/save/";
    success_message = 'Successfully added ';
    error_message   = 'Insert data failed';
  } else {
    url             = base_url + "angsuran/update/";
    success_message = 'Successfully updated ';
    error_message   = 'Update data failed';
  }

  var form = $('#form_pembayaran_angsuran')[0];
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
        $('#modal_bayar_angsuran').modal('hide');
        reload_table();
      } else {
        $('#modal_bayar_angsuran').modal('hide');
        alert('Gagal input data. Silahkan coba refresh kembali halaman ini');
      }
    }
  });
}

function detail(id){
  save_method = 'update';
  $.ajax({
    type	: "GET",
    url		: base_url + "angsuran/detail/" + id,
    success	: function(result){
      var myObj = JSON.parse(result);
      $('[name="input_tanggal_trans"]').val(myObj.tgl_bayar_txt);
      $('[name="id_bayar"]').val(myObj.id);
      $('[name="input_keterangan"]').val(myObj.keterangan);
      $('[name="simpan_ke_kas"]').val(myObj.kas_id);
      $('[name="input_angsuran_ke"]').val(myObj.angsuran_ke);

      $.ajax({
        url: base_url + "angsuran/cek_sebelum_update/",
        type: 'POST',
        dataType: 'json',
        data: {id_bayar: myObj.id, master_id: $('#hidden_id').val()}
      }).done(function(result) {
        if(result.success == '1') {
          $('.modal-title').text('Form Edit Pembayaran Angsuran');
          console.log(result);

          $('#input_sisa_angsuran').val(result.sisa_ags);
          $('#input_sisa_tagihan').val(result.sisa_tagihan);
          var denda_txt = myObj.denda_rp;
          var denda_num = denda_txt.replace(',', '');
          $('#input_denda').val(denda_num);

          $('#modal_bayar_angsuran').modal('show');
        } else {
          alert('Maaf, Hanya data transaksi terakhir saja yang boleh diubah (silahkan cek juga list Pelunasan jika ada).');
        }
      }).fail(function() {
        alert("Kesalahan koneksi, silahkan ulangi (refresh).");
      });
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
      url = base_url + "angsuran/delete/";
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

function modal_petunjuk_pembayaran(){
  $('#modal_petunjuk_pembayaran').modal('show');
}
