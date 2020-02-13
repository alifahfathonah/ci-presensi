var table;
var save_method;
$(document).ready(function() {
  form_validation();

  table = $('#table_jenisakun').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
          "url": base_url+"jenisakun/ajax_list/",
          "type": "POST"
      },

      "columnDefs": [
      {
          "targets": [ -1 ],
          "orderable": false,
      },
      ],
  });

});

function reload_table(){
    table.ajax.reload(null,false);
}

function add_jenis_usaha(){
  $('#form_jenis_akun')[0].reset();
  save_method = "add";
  $('#modal_add_jenis_akun').modal('show');
}

function form_validation(){
  $('#form_jenis_akun').on('submit', function(event){
    event.preventDefault();
    event.stopPropagation();
    $.ajax({
      url: base_url + "jenisakun/validation/"+save_method,
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

          if(data.input_kd_aktiva_error != ""){
            $('#input_kd_aktiva_error').html(data.input_kd_aktiva_error);
            $('#input_kd_aktiva').addClass('is-invalid');
          }else {
            $('#input_kd_aktiva_error').html('');
            $('#input_kd_aktiva').removeClass('is-invalid');
            $('#input_kd_aktiva').addClass('is-valid');
          }

          if(data.input_jenis_trans_error != ""){
            $('#input_jenis_trans_error').html(data.input_jenis_trans_error);
            $('#input_jenis_trans').addClass('is-invalid');
          }else {
            $('#input_jenis_trans_error').html('');
            $('#input_jenis_trans').removeClass('is-invalid');
            $('#input_jenis_trans').addClass('is-valid');
          }

          if(data.input_akun_error != ""){
            $('#input_akun_error').html(data.input_akun_error);
            $('#input_akun').addClass('is-invalid');
          }else {
            $('#input_akun_error').html('');
            $('#input_akun').removeClass('is-invalid');
            $('#input_akun').addClass('is-valid');
          }

          if(data.input_pemasukan_error != ""){
            $('#input_pemasukan_error').html(data.input_pemasukan_error);
            $('#input_pemasukan').addClass('is-invalid');
          }else {
            $('#input_pemasukan_error').html('');
            $('#input_pemasukan').removeClass('is-invalid');
            $('#input_pemasukan').addClass('is-valid');
          }

          if(data.input_pengeluaran_error != ""){
            $('#input_pengeluaran_error').html(data.input_pengeluaran_error);
            $('#input_pengeluaran').addClass('is-invalid');
          }else {
            $('#input_pengeluaran_error').html('');
            $('#input_pengeluaran').removeClass('is-invalid');
            $('#input_pengeluaran').addClass('is-valid');
          }

          if(data.input_aktif_error != ""){
            $('#input_aktif_error').html(data.input_aktif_error);
            $('#input_aktif').addClass('is-invalid');
          }else {
            $('#input_aktif_error').html('');
            $('#input_aktif').removeClass('is-invalid');
            $('#input_aktif').addClass('is-valid');
          }

        }
        if(data.success){
          $('p').removeClass('text-danger');

          $('#input_kd_aktiva').removeClass('is-invalid');
          $('#input_kd_aktiva').removeClass('is-valid');
          $('#input_kd_aktiva_error').html('');

          $('#input_jenis_trans').removeClass('is-invalid');
          $('#input_jenis_trans').removeClass('is-valid');
          $('#input_jenis_trans_error').html('');

          $('#input_akun').removeClass('is-invalid');
          $('#input_akun').removeClass('is-valid');
          $('#input_akun_error').html('');

          $('#input_pemasukan').removeClass('is-invalid');
          $('#input_pemasukan').removeClass('is-valid');
          $('#input_pemasukan_error').html('');

          $('#input_pengeluaran').removeClass('is-invalid');
          $('#input_pengeluaran').removeClass('is-valid');
          $('#input_pengeluaran_error').html('');

          $('#input_aktif').removeClass('is-invalid');
          $('#input_aktif').removeClass('is-valid');
          $('#input_aktif_error').html('');

          save();

          $('#form_jenis_akun')[0].reset();
        }
        $('#btn-save').attr('disabled', false);
      }
    });
  });
}
//
function save(){
  var url;
  var success_message;
  var error_message;

  if(save_method == "add") {
    url             = base_url + "jenisakun/save/";
    success_message = 'Successfully added ';
    error_message   = 'Insert data failed';
  } else {
    url             = base_url + "jenisakun/update/";
    success_message = 'Successfully updated ';
    error_message   = 'Update data failed';
  }

  var form = $('#form_jenis_akun')[0];
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
        $('#modal_add_jenis_akun').modal('hide');
        reload_table();
      }
    }
  });
}
//
function edit(id){
  save_method = 'update';
  $('#form_jenis_akun')[0].reset();
  $.ajax({
      url : base_url+"jenisakun/ajax_detail/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
          $('[name="id"]').val(data.id);
          $('[name="input_kd_aktiva"]').val(data.kd_aktiva);
          $('[name="input_jenis_trans"]').val(data.jns_trans);
          $('[name="input_akun"]').val(data.akun);
          $('[name="input_pemasukan"]').val(data.pemasukan);
          $('[name="input_pengeluaran"]').val(data.pengeluaran);
          $('[name="input_aktif"]').val(data.aktif);

          if(data.laba_rugi == ""){
            $('[name="input_labarugi"]').val('x');
          } else if(data.laba_rugi !== " "){
            $('[name="input_labarugi"]').val(data.laba_rugi);
          }

          $('#modal_add_jenis_akun').modal('show');
          $('.modal-title').text('Ubah Jenis Akun Transaksi');
      },
      error: function (jqXHR, textStatus, errorThrown){
          console.log(errorThrown);
          alert('Error get data from ajax'+jqXHR+textStatus+errorThrown);
      }
  });
}

function export_excel(){
  window.open(base_url + 'jenisakun/export_to_excel/', "_self");
}
