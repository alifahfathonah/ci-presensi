var table;
var save_method;
$(document).ready(function() {
  form_validation();

  table = $('#table_databarang').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
          "url": base_url+"databarang/ajax_list/",
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
  $('#form_databarang')[0].reset();
  save_method = "add";
  $('#modal_add_databarang').modal('show');
}

function form_validation(){
  $('#form_databarang').on('submit', function(event){
    event.preventDefault();
    event.stopPropagation();
    $.ajax({
      url: base_url + "databarang/validation/"+save_method,
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

          if(data.input_nama_barang_error != ""){
            $('#input_nama_barang_error').html(data.input_nama_barang_error);
            $('#input_nama_barang').addClass('is-invalid');
          }else {
            $('#input_nama_barang_error').html('');
            $('#input_nama_barang').removeClass('is-invalid');
            $('#input_nama_barang').addClass('is-valid');
          }

          if(data.input_harga_error != ""){
            $('#input_harga_error').html(data.input_harga_error);
            $('#input_harga').addClass('is-invalid');
          }else {
            $('#input_harga_error').html('');
            $('#input_harga').removeClass('is-invalid');
            $('#input_harga').addClass('is-valid');
          }

          if(data.input_jml_barang_error != ""){
            $('#input_jml_barang_error').html(data.input_jml_barang_error);
            $('#input_jml_barang').addClass('is-invalid');
          }else {
            $('#input_jml_barang_error').html('');
            $('#input_jml_barang').removeClass('is-invalid');
            $('#input_jml_barang').addClass('is-valid');
          }

        }
        if(data.success){
          $('p').removeClass('text-danger');

          $('#input_nama_barang').removeClass('is-invalid');
          $('#input_nama_barang').removeClass('is-valid');
          $('#input_nama_barang_error').html('');

          $('#input_harga').removeClass('is-invalid');
          $('#input_harga').removeClass('is-valid');
          $('#input_harga_error').html('');

          $('#input_jml_barang').removeClass('is-invalid');
          $('#input_jml_barang').removeClass('is-valid');
          $('#input_jml_barang_error').html('');

          save();

          $('#form_databarang')[0].reset();
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
    url             = base_url + "databarang/save/";
    success_message = 'Successfully added ';
    error_message   = 'Insert data failed';
  } else {
    url             = base_url + "databarang/update/";
    success_message = 'Successfully updated ';
    error_message   = 'Update data failed';
  }

  var form = $('#form_databarang')[0];
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
        $('#modal_add_databarang').modal('hide');
        reload_table();
      }
    }
  });
}

function edit(id){
  save_method = 'update';
  $('#form_databarang')[0].reset();
  $.ajax({
      url : base_url+"databarang/ajax_detail/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
          $('[name="id"]').val(data.id);
          $('[name="input_nama_barang"]').val(data.nm_barang);
          $('[name="input_type"]').val(data.type);
          $('[name="input_merk"]').val(data.merk);
          $('[name="input_harga"]').val(data.harga);
          $('[name="input_jml_barang"]').val(data.jml_brg);
          $('[name="input_keterangan"]').val(data.ket);
          $('#modal_add_databarang').modal('show');
          $('.modal-title').text('Ubah Data Barang');
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
            url : base_url+"databarang/ajax_delete/" + id,
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
  window.open(base_url + 'databarang/export_to_excel/', "_self");
}

function isNumberKey(evt){
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode != 46 && charCode > 31
    && (charCode < 48 || charCode > 57))
    return false;
    return true;
}
