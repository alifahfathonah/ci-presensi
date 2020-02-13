var table;
var save_method;
$(document).ready(function() {
 form_validation();

  table = $('#table_korporasi').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
          "url": base_url+"klienkorporasi/ajax_list/",
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

function modal_add(){
  save_method = 'add';
  $('#modal_add').modal('show');
}

function form_validation(){
  $('#form_korporasi').on('submit', function(event){
    event.preventDefault();
    event.stopPropagation();
    $.ajax({
      url: base_url + "klienkorporasi/validation/",
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      beforeSent:function(){
        $('#btn_save').attr('disabled', true);
      },
      success: function(data){
        if(data.error){
          console.log(data);
          alert('Gagal tambah data. Input data tidak valid.');

          if(data.input_nama_korporasi_error != ""){
            $('#input_nama_korporasi_error').html(data.input_nama_korporasi_error);
            $('#input_nama_korporasi').addClass('is-invalid');
          }else {
            $('#input_nama_korporasi_error').html('');
            $('#input_nama_korporasi').removeClass('is-invalid');
            $('#input_nama_korporasi').addClass('is-valid');
          }

          if(data.input_alamat_korporasi_error != ""){
            $('#input_alamat_korporasi_error').html(data.input_alamat_korporasi_error);
            $('#input_alamat_korporasi').addClass('is-invalid');
          }else {
            $('#input_alamat_korporasi_error').html('');
            $('#input_alamat_korporasi').removeClass('is-invalid');
            $('#input_alamat_korporasi').addClass('is-valid');
          }

          if(data.input_kota_korporasi_error != ""){
            $('#input_kota_korporasi_error').html(data.input_kota_korporasi_error);
            $('#input_kota_korporasi').addClass('is-invalid');
          }else {
            $('#input_kota_korporasi_error').html('');
            $('#input_kota_korporasi').removeClass('is-invalid');
            $('#input_kota_korporasi').addClass('is-valid');
          }

          if(data.input_telepon_korporasi_error != ""){
            $('#input_telepon_korporasi_error').html(data.input_telepon_korporasi_error);
            $('#input_telepon_korporasi').addClass('is-invalid');
          }else {
            $('#input_telepon_korporasi_error').html('');
            $('#input_telepon_korporasi').removeClass('is-invalid');
            $('#input_telepon_korporasi').addClass('is-valid');
          }


        }
        if(data.success){
          $('p').removeClass('text-danger');

          $('#input_nama_korporasi').removeClass('is-invalid');
          $('#input_nama_korporasi').removeClass('is-valid');
          $('#input_nama_korporasi_error').html('');

          $('#input_alamat_korporasi').removeClass('is-invalid');
          $('#input_alamat_korporasi').removeClass('is-valid');
          $('#input_alamat_korporasi_error').html('');

          $('#input_kota_korporasi').removeClass('is-invalid');
          $('#input_kota_korporasi').removeClass('is-valid');
          $('#input_kota_korporasi_error').html('');

          $('#input_telepon_korporasi').removeClass('is-invalid');
          $('#input_telepon_korporasi').removeClass('is-valid');
          $('#input_telepon_korporasi_error').html('');

          save();

          $('#form_korporasi')[0].reset();
        }
        $('#btn_save').attr('disabled', false);
      }
    });
  });
}


function save(){
  var url;
  var success_message;
  var error_message;

  if(save_method == "add") {
    url             = base_url + "klienkorporasi/save/";
    success_message = 'Successfully added ';
    error_message   = 'Insert data failed';
  } else {
    url             = base_url + "klienkorporasi/update/";
    success_message = 'Successfully updated ';
    error_message   = 'Update data failed';
  }

  var form = $('#form_korporasi')[0];
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
        console.log(data);
        $('#modal_add').modal('hide');
        reload_table();
      }
    }
  });
}

function edit_data(id){
  save_method = "update";
  $.ajax({
      url : base_url+"klienkorporasi/detail/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
          console.log(data);
          $('[name="id"]').val(data.id);
          $('[name="input_nama_korporasi"]').val(data.nama_klien);
          $('[name="input_alamat_korporasi"]').val(data.alamat);
          $('[name="input_kota_korporasi"]').val(data.kota);
          $('[name="input_telepon_korporasi"]').val(data.no_telpon);
          $('#modal_add').modal('show');
          $('.modal-title').text('Edit Data Klien Korporasi');
      },
      error: function (jqXHR, textStatus, errorThrown){
          console.log(errorThrown);
          alert('Error get data from ajax'+jqXHR+textStatus+errorThrown);
      }
  });
}

function delete_data(id){
    if(confirm('Apakah Anda yakin?')){
        $.ajax({
            url : base_url+"klienkorporasi/delete/" + id,
            type: "POST",
            dataType: "JSON",
            success: function(data){
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown){
                alert('Error deleting data');
            }
        });

    }
}
