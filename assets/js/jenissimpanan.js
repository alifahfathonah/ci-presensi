var table;
var save_method;
$(document).ready(function() {
  form_validation();

  table = $('#table_jenissimpanan').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
          "url": base_url+"Jenissimpanan/ajax_list/",
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
  $('#form_jenis_simpanan').on('submit', function(event){
    event.preventDefault();
    event.stopPropagation();
    save();
  });
}

function save(){
  var url;
  var success_message;
  var error_message;

  url = base_url + "Jenissimpanan/update/";

  var form = $('#form_jenis_simpanan')[0];
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
        $('#modal_add_jenis_simpanan').modal('hide');
        reload_table();
      }
    }
  });
}

function edit(id){
  save_method = 'update';
  $('#form_jenis_simpanan')[0].reset();
  $.ajax({
      url : base_url+"jenissimpanan/ajax_detail/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
          $('[name="id"]').val(data.id);
          $('[name="input_nama_jenis_simpanan"]').val(data.jns_simpan);
          $('[name="input_jumlah"]').val(data.jumlah);
          $('[name="input_tampil"]').val(data.tampil);
          $('#modal_add_jenis_simpanan').modal('show');
          $('.modal-title').text('Ubah Jenis Simpanan');
      },
      error: function (jqXHR, textStatus, errorThrown){
          console.log(errorThrown);
          alert('Error get data from ajax'+jqXHR+textStatus+errorThrown);
      }
  });
}

function export_excel(){
  window.open(base_url + 'jenissimpanan/export_to_excel/', "_self");
}
