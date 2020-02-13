var table;
var save_method;
$(document).ready(function() {
  form_validation();

  table = $('#table_jenisangsuran').DataTable({
    "processing": true,
    "serverSide": true,
    "order": [],

    "ajax": {
      "url": base_url+"Jenisangsuran/ajax_list/",
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
  $('#form_jenis_angsuran')[0].reset();
  save_method = "add";
  $('#modal_add_jenis_angsuran').modal('show');
}

function form_validation(){
  $('#form_jenis_angsuran').on('submit', function(event){
    event.preventDefault();
    event.stopPropagation();
    $.ajax({
      url: base_url + "Jenisangsuran/validation/"+save_method,
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

          if(data.input_angsuran_error != ""){
            $('#input_angsuran_error').html(data.input_angsuran_error);
            $('#input_angsuran').addClass('is-invalid');
          }else {
            $('#input_angsuran_error').html('');
            $('#input_angsuran').removeClass('is-invalid');
            $('#input_angsuran').addClass('is-valid');
          }

        }
        if(data.success){
          $('p').removeClass('text-danger');

          $('#input_angsuran').removeClass('is-invalid');
          $('#input_angsuran').removeClass('is-valid');
          $('#input_angsuran_error').html('');

          save();

          $('#form_jenis_angsuran')[0].reset();
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

  if(save_method == "add") {
    url             = base_url + "Jenisangsuran/save/";
    success_message = 'Successfully added ';
    error_message   = 'Insert data failed';
  } else {
    url             = base_url + "Jenisangsuran/update/";
    success_message = 'Successfully updated ';
    error_message   = 'Update data failed';
  }

  var form = $('#form_jenis_angsuran')[0];
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
        $('#modal_add_jenis_angsuran').modal('hide');
        reload_table();
      }
    }
  });
}
//
function edit(id){
  save_method = 'update';
  $('#form_jenis_angsuran')[0].reset();
  $.ajax({
    url : base_url+"Jenisangsuran/ajax_detail/" + id,
    type: "GET",
    dataType: "JSON",
    success: function(data)
    {
      $('[name="id"]').val(data.id);
      $('[name="input_angsuran"]').val(data.ket);
      $('[name="input_aktif"]').val(data.aktif);

      $('#modal_add_jenis_angsuran').modal('show');
      $('.modal-title').text('Ubah Jenis Angsuran');
    },
    error: function (jqXHR, textStatus, errorThrown){
      console.log(errorThrown);
      alert('Error get data from ajax'+jqXHR+textStatus+errorThrown);
    }
  });
}

function export_excel(){
  window.open(base_url + 'Jenisangsuran/export_to_excel/', "_self");
}

function isNumberKey(evt){
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode != 46 && charCode > 31
    && (charCode < 48 || charCode > 57))
    return false;
    return true;
}

function delete_data(id){
    if(confirm('Apakah Anda yakin?')){
        $.ajax({
            url : base_url+"Jenisangsuran/ajax_delete/" + id,
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
