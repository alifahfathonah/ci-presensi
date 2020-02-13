var table;
var save_method;
$(document).ready(function() {
  form_validation();

  table = $('#table_jeniskas').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
          "url": base_url+"jeniskas/ajax_list/",
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
  $('#form_jenis_kas')[0].reset();
  save_method = "add";
  $('#modal_add_jenis_kas').modal('show');
}

function form_validation(){
  $('#form_jenis_kas').on('submit', function(event){
    event.preventDefault();
    event.stopPropagation();
    $.ajax({
      url: base_url + "jeniskas/validation/"+save_method,
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

          if(data.input_nama_kas_error != ""){
            $('#input_nama_kas_error').html(data.input_nama_kas_error);
            $('#input_nama_kas').addClass('is-invalid');
          }else {
            $('#input_nama_kas_error').html('');
            $('#input_nama_kas').removeClass('is-invalid');
            $('#input_nama_kas').addClass('is-valid');
          }

        }
        if(data.success){
          $('p').removeClass('text-danger');

          $('#input_nama_kas').removeClass('is-invalid');
          $('#input_nama_kas').removeClass('is-valid');
          $('#input_nama_kas_error').html('');

          save();

          $('#form_jenis_kas')[0].reset();
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
    url             = base_url + "jeniskas/save/";
    success_message = 'Successfully added ';
    error_message   = 'Insert data failed';
  } else {
    url             = base_url + "jeniskas/update/";
    success_message = 'Successfully updated ';
    error_message   = 'Update data failed';
  }

  var form = $('#form_jenis_kas')[0];
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
        $('#modal_add_jenis_kas').modal('hide');
        reload_table();
      }
    }
  });
}

function edit(id){
  save_method = 'update';
  $('#form_jenis_kas')[0].reset();
  $.ajax({
      url : base_url+"jeniskas/ajax_detail/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
          $('[name="id"]').val(data.id);
          $('[name="input_nama_kas"]').val(data.nama);
          $('[name="input_aktif"]').val(data.aktif);
          $('[name="input_simpanan"]').val(data.tmpl_simpan);
          $('[name="input_penarikan"]').val(data.tmpl_penarikan);
          $('[name="input_pinjaman"]').val(data.tmpl_pinjaman);
          $('[name="input_angsuran"]').val(data.tmpl_bayar);
          $('[name="input_pemasukan"]').val(data.tmpl_pemasukan);
          $('[name="input_pengeluaran"]').val(data.tmpl_pengeluaran);
          $('[name="input_transfer"]').val(data.tmpl_transfer);
          $('#modal_add_jenis_kas').modal('show');
          $('.modal-title').text('Ubah Jenis Kas');
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
            url : base_url+"jeniskas/ajax_delete/" + id,
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
  window.open(base_url + 'Jeniskas/export_to_excel/', "_self");
}
