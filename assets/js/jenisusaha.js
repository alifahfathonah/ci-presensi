var table;
var save_method;
$(document).ready(function() {
  form_validation();

  table = $('#table_jenisbisnis').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
          "url": base_url+"jenisusaha/ajax_list/",
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
  $('#form_jenis_usaha')[0].reset();
  save_method = "add";
  $('#modal_add_jenis_usaha').modal('show');
}

function form_validation(){
  $('#form_jenis_usaha').on('submit', function(event){
    event.preventDefault();
    event.stopPropagation();
    $.ajax({
      url: base_url + "jenisusaha/validation/"+save_method,
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

          if(data.input_nama_jenis_usaha_error != ""){
            $('#input_nama_jenis_usaha_error').html(data.input_nama_jenis_usaha_error);
            $('#input_nama_jenis_usaha').addClass('is-invalid');
          }else {
            $('#input_nama_jenis_usaha_error').html('');
            $('#input_nama_jenis_usaha').removeClass('is-invalid');
            $('#input_nama_jenis_usaha').addClass('is-valid');
          }

        }
        if(data.success){
          $('p').removeClass('text-danger');

          $('#input_nama_jenis_usaha').removeClass('is-invalid');
          $('#input_nama_jenis_usaha').removeClass('is-valid');
          $('#input_nama_jenis_usaha_error').html('');

          save();

          $('#form_jenis_usaha')[0].reset();
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
    url             = base_url + "jenisusaha/save/";
    success_message = 'Successfully added ';
    error_message   = 'Insert data failed';
  } else {
    url             = base_url + "jenisusaha/update/";
    success_message = 'Successfully updated ';
    error_message   = 'Update data failed';
  }

  var form = $('#form_jenis_usaha')[0];
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
        $('#modal_add_jenis_usaha').modal('hide');
        reload_table();
      }
    }
  });
}

function edit(id){
  save_method = 'update';
  $('#form_jenis_usaha')[0].reset();
  $.ajax({
      url : base_url+"jenisusaha/ajax_detail/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
          $('#input_kode_jenis_usaha').attr('readonly', true);
          $('[name="input_kode_jenis_usaha"]').val(data.kd_jenisBisnis);
          $('[name="input_nama_jenis_usaha"]').val(data.jenisBisnis);
          $('[name="input_keterangan_jenis_usaha"]').val(data.keterangan);
          $('#modal_add_jenis_usaha').modal('show');
          $('.modal-title').text('Ubah Jenis Usaha');
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
            url : base_url+"jenisusaha/ajax_delete/" + id,
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
  window.open(base_url + 'jenisusaha/export_to_excel/', "_self");
}
