$(document).ready(function (){

  form_validation();
  form_validation_2();

  $('#input_tanggal_awal_filter').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_akhir_filter').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_awal_filter_2').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_akhir_filter_2').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_form').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_start_delete').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_end_delete').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  open_filter();

});

function open_filter(){
  $('#modal-filter').modal('show');
}

function open_form(){
  $("#btn-hapus").css("display", "none");
  reset_validasi();
  $('.card-title').text('Tambah Data Shift Karyawan');
  $('#form-add')[0].reset();
  $('#modal-form').modal('show');
  $('#btn-save').text('Simpan');
  document.getElementById("input_departemen").disabled =false;
  document.getElementById("input_karyawan").disabled =false;
  document.getElementById("input_tanggal_form").readOnly =false;
  save_method = 'add';
}

function getKaryawanByDept(){
  $.ajax({
    url: base_url + "lembur/getKaryawanByDept/" + $('#input_departemen').val(),
    method: "GET",
    success: function (html) {
      $('#input_karyawan').html(html);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(errorThrown);
      alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
    }
  });
}

function getKaryawanByDeptDelete(){
  $.ajax({
    url: base_url + "lembur/getKaryawanByDept/" + $('#input_departemen_delete').val(),
    method: "GET",
    success: function (html) {
      $('#input_karyawan_delete').html(html);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(errorThrown);
      alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
    }
  });
}

function load_data(){
  $('#table-scroll').html("<i>Processing...</i>");
  var departemen = $('#input_departemen_filter').val();
  var start      = $('#input_tanggal_awal_filter').val();
  var end        = $('#input_tanggal_akhir_filter').val();
  var parameter = [departemen, start, end];
  console.log(parameter);
  $.ajax({
		url: base_url + 'shiftkaryawan/load_data',
		method: 'POST',
    data: {param: parameter},
		success: function (html) {
      data_departemen(departemen, start, end);
      $('#table-scroll').html(html);
      jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
      $('#modal-filter').modal('hide');
		}
	});
}

function data_departemen(departemen, start, end){
  $.ajax({
		url: base_url + 'departemen/json_detail/' + departemen,
		method: 'GET',
		success: function (data){
      console.log(data);
      if(departemen !== 'x'){
        var obj = JSON.parse(data);
        if(start != "" || end != ""){
          $('#judul_page_shiftkaryawan').html('Data Shift Dept. <b>'+obj.nama_departemen+'</b> Periode <b>'+start+'</b> s.d <b>'+end+'</b>');
        } else {
          $('#judul_page_shiftkaryawan').html('Data Shift Dept. <b>'+obj.nama_departemen+'</b> Periode Bulan Ini');
        }
      }
		}
	});
}

function download_template(){
  var departemen  = $('#input_departemen_filter_2').val();
  var start       = $('#input_tanggal_awal_filter_2').val();
  var end         = $('#input_tanggal_akhir_filter_2').val();
  window.open(base_url + 'shiftkaryawan/download_template/' + departemen +'/'+ start +'/'+ end, "_self");
}


function open_modal_import(){
	$('#alert-import-message').html();
	$('#modal-import').modal('show');
}

function do_import() {
	var form = $('#form-import')[0];
	var formData = new FormData(form);
	$('#alert-import-message').html('<div class="alert alert-danger">Import masih berjalan. Dilarang menutup / reload halaman / browser Anda.</di>');
	$.ajax({
		url: base_url + "shiftkaryawan/do_import/",
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
			console.log(data.status);
			if(data.status){
				alert('Import Data Berhasil');
				$('#modal-import').modal('hide');
				// reload_table();
			} else {
				$('#alert-import-message').html('<div class="alert alert-danger">' + data.message + '</di>');
			}
		}
	});
}

function reset_validasi(){
  var input_list = [
    'input_departemen',
    'input_karyawan',
    'input_tanggal_form',
    'input_kode_shift',
  ];

  var input_list_error = [

    'input_departemen_error_icon',
    'input_karyawan_error_icon',
    'input_tanggal_form_error_detail',
    'input_kode_shift_error_icon',

  ];

	for (let index = 0; index < input_list.length; index++) {
		const input_ = input_list[index];
		const input_error = input_list_error[index];

		$('[id=' + input_error + ']').html('');
		$('[id=' + input_ + '_error_container]').removeClass('has-danger');
		$('[id=' + input_ + '_error_container]').removeClass('has-success');
		$('[id=' + input_ + '_error_icon]').text('');

		if( input_error == "input_tanggal_form_error_detail"){
			$('.gj-icon').removeClass('text-success');
			$('.gj-icon').removeClass('has-success');
			$('.gj-icon').removeClass('text-danger');
			$('[id=' + input_ + '_error_icon]').text('');
		}
	}
}

function reset_validasi_2(){
  var input_list = [
    'input_departemen_delete',
    'input_karyawan_delete',
    'input_tanggal_start_delete',
    'input_tanggal_end_delete',
  ];

  var input_list_error = [

    'input_departemen_delete_error_icon',
    'input_karyawan_delete_error_icon',
    'input_tanggal_start_delete_error_detail',
    'input_tanggal_end_delete_error_detail',

  ];

	for (let index = 0; index < input_list.length; index++) {
		const input_ = input_list[index];
		const input_error = input_list_error[index];

		$('[id=' + input_error + ']').html('');
		$('[id=' + input_ + '_error_container]').removeClass('has-danger');
		$('[id=' + input_ + '_error_container]').removeClass('has-success');
		$('[id=' + input_ + '_error_icon]').text('');

		if( input_error == "input_tanggal_start_delete_error_detail"){
			$('.gj-icon').removeClass('text-success');
			$('.gj-icon').removeClass('has-success');
			$('.gj-icon').removeClass('text-danger');
			$('[id=' + input_ + '_error_icon]').text('');
		}

    if( input_error == "input_tanggal_end_delete_error_detail"){
			$('.gj-icon').removeClass('text-success');
			$('.gj-icon').removeClass('has-success');
			$('.gj-icon').removeClass('text-danger');
			$('[id=' + input_ + '_error_icon]').text('');
		}
	}
}

function form_validation() {
	$('#form-add').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

		var input_list = [
			'input_departemen',
			'input_karyawan',
			'input_tanggal_form',
			'input_kode_shift',
		];

		var input_list_error = [

      'input_departemen_error_icon',
			'input_karyawan_error_icon',
			'input_tanggal_form_error_detail',
			'input_kode_shift_error_icon',

		];

		$.ajax({
			url: base_url + "Shiftkaryawan/validation/",
			method: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			beforeSent: function () {
				$('#btn-save').attr('disabled', true);
			},
			success: function (data) {

				if (data.error) {
					for (let index = 0; index < input_list.length; index++) {
						const input_ = input_list[index];
						const input_error = input_list_error[index];
						if (data[input_error] !== "") {
							$('[id=' + input_error + ']').html(data[input_error]);
							$('[id=' + input_ + '_error_container]').addClass('has-danger');
							$('[id=' + input_ + '_error_icon]').text('clear');

							if( input_error == "input_tanggal_form_error_detail"){
								$('#input_tanggal_form_error_container .gj-icon').addClass('text-danger');
								$('#input_tanggal_form_error_container .gj-icon').addClass('has-danger');
								$('[id=' + input_ + '_error_icon]').text('');
							}


						} else {
							$('[id=' + input_error + ']').html('');
							$('[id=' + input_ + '_error_container]').removeClass('has-danger');
							$('[id=' + input_ + '_error_container]').addClass('has-success');
							$('[id=' + input_ + '_error_icon]').text('done');

							if( input_error == "input_tanggal_form_error_detail"){
                // $('#input_tanggal_form_error_container .gj-icon').removeClass('text-danger');
                // $('#input_tanggal_form_error_container ').removeClass('has-danger');
								$('#input_tanggal_form_error_container .gj-icon').addClass('text-success');
								$('#input_tanggal_form_error_container .gj-icon').addClass('has-success');
                // $('#input_tanggal_form_error_container .gj-icon').text('');
								$('[id=' + input_ + '_error_icon]').text('');
							}

						}
					}
				}

				if (data.success) {
					for (let index = 0; index < input_list.length; index++) {
						const input_ = input_list[index];
						const input_error = input_list_error[index]

						$('[id=' + input_error + ']').html('');
						$('[id=' + input_ + '_error_container]').removeClass('has-danger');
						$('[id=' + input_ + '_error_container]').addClass('has-success');
            // $('#input_tanggal_form_error_container .gj-icon').text('');
						$('[id=' + input_ + '_error_icon]').text('done');
					}
					save();
				}
				$('#btn-save').attr('disabled', false);
			}
		});
	});
}

function form_validation_2() {
	$('#form-delete').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

    var input_list = [
      'input_departemen_delete',
      // 'input_karyawan_delete',
      'input_tanggal_start_delete',
      'input_tanggal_end_delete',
    ];

    var input_list_error = [

      'input_departemen_delete_error_icon',
      // 'input_karyawan_delete_error_icon',
      'input_tanggal_start_delete_error_detail',
      'input_tanggal_end_delete_error_detail',

    ];

		$.ajax({
			url: base_url + "Shiftkaryawan/validation2/",
			method: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			beforeSent: function () {
				$('#btn-hapus-form').attr('disabled', true);
			},
			success: function (data) {

				if (data.error) {
					for (let index = 0; index < input_list.length; index++) {
						const input_ = input_list[index];
						const input_error = input_list_error[index];
						if (data[input_error] !== "") {
							$('[id=' + input_error + ']').html(data[input_error]);
							$('[id=' + input_ + '_error_container]').addClass('has-danger');
							$('[id=' + input_ + '_error_icon]').text('clear');

							if( input_error == "input_tanggal_start_delete_error_detail"){
                $('#input_tanggal_start_delete_error_container .gj-icon').removeClass('text-success');
								$('#input_tanggal_start_delete_error_container .gj-icon').removeClass('has-success');
								$('#input_tanggal_start_delete_error_container .gj-icon').addClass('text-danger');
								$('#input_tanggal_start_delete_error_container .gj-icon').addClass('has-danger');
								$('[id=' + input_ + '_error_icon]').text('');
							}

              if( input_error == "input_tanggal_end_delete_error_detail"){
                $('#input_tanggal_end_delete_error_container .gj-icon').removeClass('text-success');
								$('#input_tanggal_end_delete_error_container .gj-icon').removeClass('has-success');
								$('#input_tanggal_end_delete_error_container .gj-icon').addClass('text-danger');
								$('#input_tanggal_end_delete_error_container .gj-icon').addClass('has-danger');
								$('[id=' + input_ + '_error_icon]').text('');
							}


						} else {
							$('[id=' + input_error + ']').html('');
							$('[id=' + input_ + '_error_container]').removeClass('has-danger');
							$('[id=' + input_ + '_error_container]').addClass('has-success');
							$('[id=' + input_ + '_error_icon]').text('done');

							if( input_error == "input_tanggal_start_delete_error_detail"){
                $('#input_tanggal_start_delete_error_container .gj-icon').removeClass('text-danger');
								$('#input_tanggal_start_delete_error_container .gj-icon').removeClass('has-danger');
								$('#input_tanggal_start_delete_error_container .gj-icon').addClass('text-success');
								$('#input_tanggal_start_delete_error_container .gj-icon').addClass('has-success');
                $('#input_tanggal_start_delete_error_container .gj-icon').text('');
								$('[id=' + input_ + '_error_icon]').text('');
							}

              if( input_error == "input_tanggal_end_delete_error_detail"){
                $('#input_tanggal_end_delete_error_container .gj-icon').removeClass('text-danger');
								$('#input_tanggal_end_delete_error_container .gj-icon').removeClass('has-danger');
								$('#input_tanggal_end_delete_error_container .gj-icon').addClass('text-success');
								$('#input_tanggal_end_delete_error_container .gj-icon').addClass('has-success');
                $('#input_tanggal_end_delete_error_container .gj-icon').text('');
								$('[id=' + input_ + '_error_icon]').text('');
							}

						}
					}
				}

				if (data.success) {
					for (let index = 0; index < input_list.length; index++) {
						const input_ = input_list[index];
						const input_error = input_list_error[index]

						$('[id=' + input_error + ']').html('');
						$('[id=' + input_ + '_error_container]').removeClass('has-danger');
						$('[id=' + input_ + '_error_container]').addClass('has-success');
            $('#input_tanggal_end_delete_error_container .gj-icon').text('');
            $('#input_tanggal_start_delete_error_container .gj-icon').text('');
						$('[id=' + input_ + '_error_icon]').text('done');
					}
					delete_filter();
				}
				$('#btn-hapus-form').attr('disabled', false);
			}
		});
	});
}


function save() {
	var url;
	if (save_method == "add") {
		url = base_url + "shiftkaryawan/insert/";
	} else {
		url = base_url + "shiftkaryawan/update/";
	}
	var form = $('#form-add')[0];
	var formData = new FormData(form);
	$.ajax({
		url: url,
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
      if(data.status){
        load_data();
        reset_validasi();
        $('#form-add')[0].reset();
        $('#modal-form').modal('hide');
      } else {
        alert(data.message)
      }
		}
	});
}

function update(id){
  $.ajax({
      type: "GET",
      url : base_url+"shiftkaryawan/detail/"+id,
      dataType: "JSON",
      success: function(data){
        document.getElementById("input_departemen").disabled =true;
        document.getElementById("input_karyawan").disabled =true;
        document.getElementById("input_tanggal_form").readOnly =true;

        $("#btn-hapus").css("display", "block");
        reset_validasi();
        $('[name="id"]').val(data.id );
        $('[name="input_departemen"]').val(data.id_departemen );
        $('[name="input_kode_shift"]').val(data.id_shift );
        $('[name="input_karyawan"]').html('<option value="'+data.id_karyawan+'">'+data.nama+'</option>');
        $('[name="input_tanggal_form"]').val(data.tanggal );
        $('#modal-form').modal('show');
        $('.card-title').text('Edit Data Shift Karyawan');
        $('#btn-save').text('Update');
        save_method = "edit";
      }
  });
}

function hapus_shift(){
  if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "shiftkaryawan/delete/" + $('[name="id"]').val(),
			method: "GET",
			dataType: 'json',
			success: function (data) {
				if (data.status) {
          document.getElementById("input_departemen").disabled =false;
          document.getElementById("input_karyawan").disabled =false;
          document.getElementById("input_tanggal_form").readOnly =false;
          // load_data();
          $('#modal-form').modal('hide');
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			}
		});
	}
}

function open_form_delete(){
  // reset_validasi();
  $('.card-title').text('Hapus Data Shift Karyawan');
  $('#form-delete')[0].reset();
  $('#modal-delete').modal('show');
  document.getElementById("input_departemen").disabled =false;
  document.getElementById("input_karyawan").disabled =false;
  document.getElementById("input_tanggal_form").readOnly =false;
  // save_method = 'add';
}

function delete_filter() {
  if (confirm('Anda yakin akan menghapus data ini?')) {
    var url = base_url + "shiftkaryawan/delete_filter/";
    var form = $('#form-delete')[0];
    var formData = new FormData(form);
    $.ajax({
      url: url,
      method: 'POST',
      data: formData,
      dataType: 'json',
      contentType: false,
      processData: false,
      success: function (data) {
        if(data.status){
          alert(data.message);
          load_data();
          reset_validasi_2();
          $('#form-delete')[0].reset();
          $('#modal-delete').modal('hide');
        } else {
          alert(data.message)
        }
      }
    });
  }
}
