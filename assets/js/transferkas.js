var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function() {
  form_validation();

  $('#sandbox-container .input-daterange').datepicker({
    format: 'yyyy-mm-dd',
    todayHighlight: true,
    language: "id"
  });

  filter_date = false;
  fetch_data();

});

function fetch_data(start_date='', end_date=''){
  table = $('#table_transferkas').DataTable({
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
      "url": base_url+"transferkas/ajax_list/",
      "type": "POST",
      "data" : function (data) {
        data.start_date     = start_date;
        data.end_date       = end_date;
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
    '<button onclick="reload_table()" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-refresh"></b></button>' +
    '<div id="reportrange" class="btn btn-sm btn-outline-secondary ">' +
    '<b class="fa fa-filter"></b>'+
    '</div>'+
    '<button onclick="get_excel()" type="button" class="btn btn-sm btn-outline-secondary" > Excel </button>' +
    '<button onclick="get_pdf()" type="button" class="btn btn-sm btn-outline-secondary" > Print </button>' +
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
    $('#table_transferkas').DataTable().destroy();
    fetch_data(start, end);
  });
}

function reload_table(){
  window.open(base_url + 'transferkas', "_self");
}

function get_excel(){
  var url = "";
  if(filter_date){
    console.log(filter_date);
    console.log(start_filter_date);
    console.log(end_filter_date);
    url = base_url+"transferkas/get_excel/" + "1/" +  start_filter_date + "/"+ end_filter_date;
  } else {
    search = $('[type="search"]').val();
    console.log(search);
    url = base_url+"transferkas/get_excel/" + "2/" + search;
  }
  window.location = url;
}

function get_pdf(){
  var url = "";
  url = base_url+"transferkas/getpdf/";
  window.open(url, '_blank');
  // window.location = url;
}

function export_excel(){
  console.clear()
  var post_parameter = [];
  parameter = $('[type="search"]').val();
  console.log('parameter ' + parameter);
  console.log('filter date ' + filter_date);
  console.log('start_filter_date ' + start_filter_date);
  console.log('end_filter_date ' + end_filter_date);


  if(parameter != ""){
    post_parameter = [1, parameter];
  } else {
    if(filter_date){
      post_parameter = [2, start_filter_date, end_filter_date];
    } else {
      post_parameter = [null];
    }
  }

  $.ajax({
    type: "GET",
    data: {param: post_parameter},
    url : base_url+"transferkas/export/",
    success: function(html){
      // window.location = base_url+"transferkas/export/";
      // console.log(html);
      // $('#export_container').html(html);
    }
  });
}

function isNumberKey(evt){
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode != 46 && charCode > 31
    && (charCode < 48 || charCode > 57))
    return false;
    return true;
  }

  function add(){
    $('#form_trans_transfer_kas')[0].reset();
    save_method = "add";
    $('#modal_add_trans_transfer_kas').modal('show');
  }

  function form_validation(){
    $('#form_trans_transfer_kas').on('submit', function(event){
      event.preventDefault();
      event.stopPropagation();
      $.ajax({
        url: base_url + "transferkas/validation/"+save_method,
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

            if(data.input_jumlah_error != ""){
              $('#input_jumlah_error').html(data.input_jumlah_error);
              $('#input_jumlah').addClass('is-invalid');
            }else {
              $('#input_jumlah_error').html('');
              $('#input_jumlah').removeClass('is-invalid');
              $('#input_jumlah').addClass('is-valid');
            }

            if(data.input_uraian_error != ""){
              $('#input_uraian_error').html(data.input_uraian_error);
              $('#input_uraian').addClass('is-invalid');
            }else {
              $('#input_uraian_error').html('');
              $('#input_uraian').removeClass('is-invalid');
              $('#input_uraian').addClass('is-valid');
            }

            if(data.input_untuk_kas_error != ""){
              $('#input_untuk_kas_error').html(data.input_untuk_kas_error);
              $('#input_untuk_kas').addClass('is-invalid');
            }else {
              $('#input_untuk_kas_error').html('');
              $('#input_untuk_kas').removeClass('is-invalid');
              $('#input_untuk_kas').addClass('is-valid');
            }

            if(data.input_dari_kas_error != ""){
              $('#input_dari_kas_error').html(data.input_dari_kas_error);
              $('#input_dari_kas').addClass('is-invalid');
            }else {
              $('#input_dari_kas_error').html('');
              $('#input_dari_kas').removeClass('is-invalid');
              $('#input_dari_kas').addClass('is-valid');
            }

          }
          if(data.success){
            $('p').removeClass('text-danger');

            $('#input_jumlah').removeClass('is-invalid');
            $('#input_jumlah').removeClass('is-valid');
            $('#input_jumlah_error').html('');

            $('#input_uraian').removeClass('is-invalid');
            $('#input_uraian').removeClass('is-valid');
            $('#input_uraian_error').html('');

            $('#input_untuk_kas').removeClass('is-invalid');
            $('#input_untuk_kas').removeClass('is-valid');
            $('#input_untuk_kas_error').html('');

            $('#input_dari_kas').removeClass('is-invalid');
            $('#input_dari_kas').removeClass('is-valid');
            $('#input_dari_kas_error').html('');

            save();

            $('#form_trans_transfer_kas')[0].reset();
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
      url             = base_url + "transferkas/save/";
      success_message = 'Successfully added ';
      error_message   = 'Insert data failed';
    } else {
      url             = base_url + "transferkas/update/";
      success_message = 'Successfully updated ';
      error_message   = 'Update data failed';
    }

    var form = $('#form_trans_transfer_kas')[0];
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
          $('#modal_add_trans_transfer_kas').modal('hide');
          reload_table();
        }
      }
    });
  }

  function edit(id){
    save_method = 'update';
    $('#form_trans_transfer_kas')[0].reset();
    $.ajax({
        url : base_url+"transferkas/ajax_detail/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="input_tanggal_trans"]').val(data.tgl_catat_asli.replace('00:00:00', ''));
            $('[name="input_jumlah"]').val(data.jumlah);
            $('[name="input_uraian"]').val(data.keterangan);
            $('[name="input_untuk_kas"]').val(data.untuk_kas_id);
            $('[name="input_dari_kas"]').val(data.dari_kas_id);
            $('#modal_add_trans_transfer_kas').modal('show');
            $('.modal-title').text('Ubah Data');
        },
        error: function (jqXHR, textStatus, errorThrown){
            console.log(errorThrown);
            alert('Error get data from ajax'+jqXHR+textStatus+errorThrown);
        }
    });
  }

  function delete_data(id, kode){
    var url;
    if(confirm('Apakah Anda akan menghapus data dengan kode: '+kode+' ?')){
      var reason = prompt("Silahkan input alasan hapus data", "-");
      if (reason == null || reason == "") {

      } else {
        var parameter = [id, reason];
        url = base_url + "transferkas/soft_delete/";
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
            }
          }
        });
      }
    }
  }
