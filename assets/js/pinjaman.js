var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
var biaya_admin;
$(document).ready(function() {

  document.title="Pinjaman - KSU Sakrawarih"
  biaya_admin = $('#input_biaya_admin').val();

  $('#input_jenis_pinjaman').on('change', function () {
    if(this.value == 3){
      $('#input_biaya_admin').val('0');
    } else if (this.value == 4) {
    	$('#input_biaya_admin').val(biaya_admin);
    }
  });

  $('#input_nama_anggota').on('change', function() {
    $('#input_nama_anggota_error').html('');
    $('#input_nama_anggota').removeClass('is-invalid')
  });

  $("#input_nama_anggota").bind("keyup change", function(e) {
    get_nominal_jenis_simpanan();
  })

  get_nominal_jenis_simpanan();

  $('#input_tanggal_pinjam').datetimepicker({
    setLocale: 'id',
    format: 'd M Y H:i'
  });

  $('.next span').removeClass('glyphicon fa-arrow-right');
  $('.next span').addClass('fa fa-arrow-right');

  $('.prev span').removeClass('glyphicon fa-arrow-left');
  $('.prev span').addClass('fa fa-arrow-left');

  form_validation();

  $('#sandbox-container .input-daterange').datepicker({
    format: 'yyyy-mm-dd',
    todayHighlight: true,
    language: "id"
  });

  filter_date = false;
  fetch_data();

  var sample_data = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    prefetch: base_url + "pinjaman/fetch_autocomplete",
    remote:{
      url: base_url + "pinjaman/fetch_autocomplete/%QUERY",
      wildcard:'%QUERY'
    }
  });

  var img_url = base_url + "uploads/anggota/";

  $('#prefetch .typeahead').typeahead(null, {
    name: 'sample_data',
    display: 'name',
    source:sample_data,
    limit:10,
    templates:{
      suggestion:Handlebars.compile(
        '<div class="row" onclick="prev({{identitas}})">'+
        '<div class="col-md-2" style="padding-right:5px; padding-left:5px;">'+
        '<img id="img_cilik_{{identitas}}" src="'+img_url+'{{image}}" class="img-thumbnail" width="48" />'+
        '</div>'+
        '<div class="col-md-10" style="padding-right:5px; padding-left:5px;">{{name}}<br>ID: {{id}}</div>'+
        '</div>')
      }
    });

  });

  function pilih_status_lunas(){
    param = $('#filter_status_pinjam').val();
    console.log('param : '+param);
    $('#table_pinjaman').DataTable().destroy();
    fetch_data('', '', param);
    $('#filter_status_pinjam').val(param);
  }

  function prev(identitas){
    if(typeof identitas !== "undefined" ){
      value = $('#img_cilik_'+identitas).attr('src');
      console.log(value);
      $('#img_prev').attr('src', value);
    } else {
      $('#img_prev').attr('src', base_url + "uploads/photo_default.jpg");
    }
  }

  function fetch_data(start_date='', end_date='', filter_status_pinjam=''){
    console.log('filter_status_pinjam : '+filter_status_pinjam);
    table = $('#table_pinjaman').DataTable({
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
        "url": base_url+"pinjaman/ajax_list/",
        "type": "POST",
        "data" : function (data) {
          data.start_date     = start_date;
          data.end_date       = end_date;
          data.filter_status_pinjam = filter_status_pinjam;
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
      '<select onchange="pilih_status_lunas()" id="filter_status_pinjam" name="filter_status_pinjam" style="border-radius: 0" class="border border-secondary custom-select custom-select-sm "><option value="x">-- Status Pinjaman --</option><option value="Belum">Belum Lunas</option><option value="Lunas">Sudah Lunas</option></select>' +
      // '<button onclick="get_excel()" type="button" class="btn btn-sm btn-outline-secondary" > Excel </button>' +
      // '<button onclick="get_pdf()" type="button" class="btn btn-sm btn-outline-secondary" > Print </button>' +
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
      $('#table_pinjaman').DataTable().destroy();
      fetch_data(start, end);
    });

  }

  function reload_table(){
    window.open(base_url + 'pinjaman', "_self");
  }

  function get_excel(){
    var url = "";
    if(filter_date){
      console.log(filter_date);
      console.log(start_filter_date);
      console.log(end_filter_date);
      url = base_url+"pinjaman/get_excel/" + "1/" +  start_filter_date + "/"+ end_filter_date;
    } else {
      search = $('[type="search"]').val();
      console.log(search);
      url = base_url+"pinjaman/get_excel/" + "2/" + search;
    }
    window.location = url;
  }
  //
  function get_pdf(){
    var url = "";
    url = base_url+"pinjaman/getpdf/";
    window.open(url, '_blank');
    // window.location = url;
  }
  //
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
      url : base_url+"pinjaman/export/",
      success: function(html){
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
      $('#form_trans_pinjaman')[0].reset();
      save_method = "add";
      $('#modal_add_trans_pinjaman').modal('show');
    }

    function form_validation(){
      $('#form_trans_pinjaman').on('submit', function(event){
        event.preventDefault();
        event.stopPropagation();
        $.ajax({
          url: base_url + "pinjaman/validation/"+save_method,
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

              if(data.input_tanggal_pinjam_error != ""){
                $('#input_tanggal_pinjam_error').html(data.input_tanggal_pinjam_error);
                $('#input_tanggal_pinjam').addClass('is-invalid');
              }else {
                $('#input_tanggal_pinjam_error').html('');
                $('#input_tanggal_pinjam').removeClass('is-invalid');
                $('#input_tanggal_pinjam').addClass('is-valid');
              }

              if(data.input_jenis_pinjaman_error != ""){
                $('#input_jenis_pinjaman_error').html(data.input_jenis_pinjaman_error);
                $('#input_jenis_pinjaman').addClass('is-invalid');
              }else {
                $('#input_jenis_pinjaman_error').html('');
                $('#input_jenis_pinjaman').removeClass('is-invalid');
                $('#input_jenis_pinjaman').addClass('is-valid');
              }

              if(data.input_nama_anggota_error != ""){
                $('#input_nama_anggota_error').html(data.input_nama_anggota_error);
                $('#input_nama_anggota').addClass('is-invalid');
              }else {
                $('#input_nama_anggota_error').html('');
                $('#input_nama_anggota').removeClass('is-invalid');
                $('#input_nama_anggota').addClass('is-valid');
              }

              if(data.input_jumlah_pinjaman_error != ""){
                $('#input_jumlah_pinjaman_error').html(data.input_jumlah_pinjaman_error);
                $('#input_jumlah_pinjaman').addClass('is-invalid');
              }else {
                $('#input_jumlah_pinjaman_error').html('');
                $('#input_jumlah_pinjaman').removeClass('is-invalid');
                $('#input_jumlah_pinjaman').addClass('is-valid');
              }

              if(data.input_lama_angsuran_error != ""){
                $('#input_lama_angsuran_error').html(data.input_lama_angsuran_error);
                $('#input_lama_angsuran').addClass('is-invalid');
              }else {
                $('#input_lama_angsuran_error').html('');
                $('#input_lama_angsuran').removeClass('is-invalid');
                $('#input_lama_angsuran').addClass('is-valid');
              }

              if(data.input_ambil_dari_kas_error != ""){
                $('#input_ambil_dari_kas_error').html(data.input_ambil_dari_kas_error);
                $('#input_ambil_dari_kas').addClass('is-invalid');
              }else {
                $('#input_ambil_dari_kas_error').html('');
                $('#input_ambil_dari_kas').removeClass('is-invalid');
                $('#input_ambil_dari_kas').addClass('is-valid');
              }

            }
            if(data.success){
              $('p').removeClass('text-danger');

              $('#input_tanggal_pinjam').removeClass('is-invalid');
              $('#input_tanggal_pinjam').removeClass('is-valid');
              $('#input_tanggal_pinjam_error').html('');

              $('#input_jumlah_pinjaman').removeClass('is-invalid');
              $('#input_jumlah_pinjaman').removeClass('is-valid');
              $('#input_jumlah_pinjaman_error').html('');

              $('#input_nama_anggota').removeClass('is-invalid');
              $('#input_nama_anggota').removeClass('is-valid');
              $('#input_nama_anggota_error').html('');

              $('#input_ambil_dari_kas').removeClass('is-invalid');
              $('#input_ambil_dari_kas').removeClass('is-valid');
              $('#input_ambil_dari_kas_error').html('');

              $('#input_jenis_pinjaman').removeClass('is-invalid');
              $('#input_jenis_pinjaman').removeClass('is-valid');
              $('#input_jenis_pinjaman_error').html('');

              $('#input_lama_angsuran').removeClass('is-invalid');
              $('#input_lama_angsuran').removeClass('is-valid');
              $('#input_lama_angsuran_error').html('');

              save();

              $('#form_trans_pinjaman')[0].reset();
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
        url             = base_url + "pinjaman/save/";
        success_message = 'Successfully added ';
        error_message   = 'Insert data failed';
      } else {
        url             = base_url + "pinjaman/update/";
        success_message = 'Successfully updated ';
        error_message   = 'Update data failed';
      }

      var form = $('#form_trans_pinjaman')[0];
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
            $('#modal_add_trans_pinjaman').modal('hide');
            reload_table();
          }
        }
      });
    }

    function edit(id){
      save_method = 'update';
      $('#form_trans_pinjaman')[0].reset();
      $.ajax({
        url : base_url+"pinjaman/ajax_detail/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

          if(data.file_pic !== ""){
            $('#img_prev').attr('src', base_url + "uploads/anggota/" + data.file_pic);
          } else {
            $('#img_prev').attr('src', base_url + "uploads/photo_default.jpg");
          }

          // $('[name="input_nama_anggota"]').attr('readonly', true);
          // $('[name="input_jenis_pinjaman"]').attr('readonly', true);

          $("#input_nama_anggota").css("pointer-events","none");
          $("#input_jenis_pinjaman").css("pointer-events","none");

          tanggal = data.tgl_pinjam_display;

          res_tanggal = tanggal.split(" - ");
          label_tanggal = res_tanggal[0];
          label_jam = res_tanggal[1];

          content_tanggal = label_tanggal.split(" ");
          tanggal_final = content_tanggal[0];
          bulan_final = content_tanggal[1].substring(0, 3);
          tahun_final = content_tanggal[2];

          tgl_pinjam = tanggal_final + " " + bulan_final + " " + tahun_final + " " + label_jam;

          $('[name="id"]').val(data.id);
          $('[name="input_tanggal_pinjam"]').val(tgl_pinjam);
          $('[name="input_nama_anggota"]').val(data.nama);
          $('[name="input_jenis_pinjaman"]').val(data.barang_id);
          $('[name="input_jumlah_pinjaman"]').val(data.jumlah);
          $('[name="input_lama_angsuran"]').val(data.lama_angsuran + " Bulan");
          $('[name="input_bunga"]').val(data.bunga + " %");
          $('[name="input_biaya_admin"]').val(data.biaya_adm);
          $('[name="input_keterangan"]').val(data.keterangan);
          $('[name="input_ambil_dari_kas"]').val(data.kas_id);
          $('#modal_add_trans_pinjaman').modal('show');
          $('.modal-title').text('Ubah Data');
        },
        error: function (jqXHR, textStatus, errorThrown){
          console.log(errorThrown);
          alert('Error get data from ajax'+jqXHR+textStatus+errorThrown);
        }
      });
    }

    function delete_data(id, kode, barang_id){
      var url;
      if(confirm('Apakah Anda akan menghapus data dengan kode: '+kode+' ?')){
        var reason = prompt("Silahkan input alasan hapus data", "-");
        if (reason == null || reason == "") {

        } else {
          var parameter = [id, reason, barang_id];
          url = base_url + "pinjaman/soft_delete/";
          console.log('parameter '+parameter);
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


    function get_nominal_jenis_simpanan(){
      $('#input_jenis_simpanan').on('change', function() {
        jenis_id     = $('#input_jenis_simpanan').val();
        nama_anggota = $('#input_nama_anggota').val();

        parameter = [jenis_id, nama_anggota];

        if(nama_anggota !== ""){
          $('#input_nama_anggota_error').html('');
          $('#input_nama_anggota').removeClass('is-invalid')
          $.ajax({
            type: "POST",
            data: {param: parameter},
            dataType: 'json',
            url : base_url+"pinjaman/get_jenis_simpanan/",
            success: function(data){
              console.log(data.result);
              $('#input_jumlah_pinjaman').val(data.result);
            }
          });
        } else {
          $('#input_jumlah_pinjaman').val('');
          $('#input_nama_anggota_error').html('<b class="fa fa-warning"></b> Nama Anggota Tidak Boleh Kosong');
          $('#input_nama_anggota').addClass('is-invalid');
        }
      });
    }
