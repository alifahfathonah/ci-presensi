var table;
var save_method;
$(document).ready(function() {
  form_validation();
  $('#sandbox-container .input-daterange').datepicker({
     format: 'yyyy-mm-dd',
     todayHighlight: true,
     language: "id"
  });

  table = $('#table_anggota').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
          "url": base_url+"anggota/ajax_list/",
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

function form_validation(){
  $('#form_anggota').on('submit', function(event){
    event.preventDefault();
    event.stopPropagation();
    $.ajax({
      url: base_url + "anggota/validation/"+save_method,
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

          if(data.input_nama_error != ""){
            $('#input_nama_error').html(data.input_nama_error);
            $('#input_nama').addClass('is-invalid');
          }else {
            $('#input_nama_error').html('');
            $('#input_nama').removeClass('is-invalid');
            $('#input_nama').addClass('is-valid');
          }

          if(data.input_username_error != ""){
            $('#input_username_error').html(data.input_username_error);
            $('#input_username').addClass('is-invalid');
          }else {
            $('#input_username_error').html('');
            $('#input_username').removeClass('is-invalid');
            $('#input_username').addClass('is-valid');
          }

          if(data.input_jenis_kelamin_error != ""){
            $('#input_jenis_kelamin_error').html(data.input_jenis_kelamin_error);
            $('#input_jenis_kelamin').addClass('is-invalid');
          }else {
            $('#input_jenis_kelamin_error').html('');
            $('#input_jenis_kelamin').removeClass('is-invalid');
            $('#input_jenis_kelamin').addClass('is-valid');
          }

          if(data.input_tempat_lahir_error != ""){
            $('#input_tempat_lahir_error').html(data.input_tempat_lahir_error);
            $('#input_tempat_lahir').addClass('is-invalid');
          }else {
            $('#input_tempat_lahir_error').html('');
            $('#input_tempat_lahir').removeClass('is-invalid');
            $('#input_tempat_lahir').addClass('is-valid');
          }

          if(data.input_tanggal_lahir_error != ""){
            $('#input_tanggal_lahir_error').html(data.input_tanggal_lahir_error);
            $('#input_tanggal_lahir').addClass('is-invalid');
          }else {
            $('#input_tanggal_lahir_error').html('');
            $('#input_tanggal_lahir').removeClass('is-invalid');
            $('#input_tanggal_lahir').addClass('is-valid');
          }

          if(data.input_alamat_error != ""){
            $('#input_alamat_error').html(data.input_alamat_error);
            $('#input_alamat').addClass('is-invalid');
          }else {
            $('#input_alamat_error').html('');
            $('#input_alamat').removeClass('is-invalid');
            $('#input_alamat').addClass('is-valid');
          }

          if(data.input_kota_error != ""){
            $('#input_kota_error').html(data.input_kota_error);
            $('#input_kota').addClass('is-invalid');
          }else {
            $('#input_kota_error').html('');
            $('#input_kota').removeClass('is-invalid');
            $('#input_kota').addClass('is-valid');
          }

          if(data.input_tanggal_registrasi_error != ""){
            $('#input_tanggal_registrasi_error').html(data.input_tanggal_registrasi_error);
            $('#input_tanggal_registrasi').addClass('is-invalid');
          }else {
            $('#input_tanggal_registrasi_error').html('');
            $('#input_tanggal_registrasi').removeClass('is-invalid');
            $('#input_tanggal_registrasi').addClass('is-valid');
          }

          if(data.input_jabatan_error != ""){
            $('#input_jabatan_error').html(data.input_jabatan_error);
            $('#input_jabatan').addClass('is-invalid');
          }else {
            $('#input_jabatan_error').html('');
            $('#input_jabatan').removeClass('is-invalid');
            $('#input_jabatan').addClass('is-valid');
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

          $('#input_nama').removeClass('is-invalid');
          $('#input_nama').removeClass('is-valid');
          $('#input_nama_error').html('');

          $('#input_username').removeClass('is-invalid');
          $('#input_username').removeClass('is-valid');
          $('#input_username_error').html('');

          $('#input_jenis_kelamin').removeClass('is-invalid');
          $('#input_jenis_kelamin').removeClass('is-valid');
          $('#input_jenis_kelamin_error').html('');

          $('#input_tempat_lahir').removeClass('is-invalid');
          $('#input_tempat_lahir').removeClass('is-valid');
          $('#input_tempat_lahir_error').html('');

          $('#input_tanggal_lahir').removeClass('is-invalid');
          $('#input_tanggal_lahir').removeClass('is-valid');
          $('#input_tanggal_lahir_error').html('');

          $('#input_alamat').removeClass('is-invalid');
          $('#input_alamat').removeClass('is-valid');
          $('#input_alamat_error').html('');

          $('#input_kota').removeClass('is-invalid');
          $('#input_kota').removeClass('is-valid');
          $('#input_kota_error').html('');

          $('#input_tanggal_registrasi').removeClass('is-invalid');
          $('#input_tanggal_registrasi').removeClass('is-valid');
          $('#input_tanggal_registrasi_error').html('');

          $('#input_jabatan').removeClass('is-invalid');
          $('#input_jabatan').removeClass('is-valid');
          $('#input_jabatan_error').html('');

          $('#input_aktif').removeClass('is-invalid');
          $('#input_aktif').removeClass('is-valid');
          $('#input_aktif_error').html('');

          save();

          $('#form_anggota')[0].reset();
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
    url             = base_url + "anggota/save/";
    success_message = 'Successfully added ';
    error_message   = 'Insert data failed';
  } else {
    url             = base_url + "anggota/update/";
    success_message = 'Successfully updated ';
    error_message   = 'Update data failed';
  }

  var form = $('#form_anggota')[0];
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
        alert(success_message + " " + data.name);
        window.open(base_url + 'anggota', "_self");
        // if(save_method == "add") {
        //   window.open(base_url + 'anggota/add', "_self");
        // } else if(save_method == "update"){
        //   window.open(base_url + 'anggota/edit/'+data.id, "_self");
        // }
      }
    }
  });
}

function delete_data(id){
    if(confirm('Apakah Anda yakin?')){
        $.ajax({
            url : base_url+"anggota/ajax_delete/" + id,
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

function export_excel(){
  window.open(base_url + 'anggota/export_to_excel/', "_self");
}
  
