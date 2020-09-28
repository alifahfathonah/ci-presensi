var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function () {

  form_validation();
  preview_image_upload();
  autoKaryawan();
  fetch_data();

  $('#input_tanggal_awal').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_akhir').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_awal_filter').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_akhir_filter').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

});

function fetch_data( departemen = "", start_date="", end_date="" ) {
	table = $('#table_izin').DataTable({
		"lengthChange": true,
		"lengthMenu": [
			[10, 25, 50, -1],
			[10, 25, 50, "All"]
		],
		"autoWidth": true,
		"processing": true,
		"serverSide": true,
		"order": [],
		"dom": "<'row'>" +
		"<'row'<'col-sm-12 col-md-6'l><'col-md-6 text-right'f>>" +
		"<'row'<'col-sm-12 filter_container'>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"+
		"<'row'<'col-sm-12 row_show '>>",
		"buttons": ['colvis'],
		"ajax": {
			"url": base_url + "izin/ajax_list/",
			"type": "POST",
			"data" : function (data) {
			  data.start_date  = start_date;
        data.end_date    = end_date;
        data.departemen  = departemen;
			}
		},

		"columnDefs": [{
			"targets": [-1],
			"orderable": false,
		}, ],
	});

	$('.filter_container').html(
		'<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">' +
		'<button onclick="reload_table()" title="Reload" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-refresh"></b></button>' +
		'<button onclick="open_modal_filter()" title="Filter" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-filter"></b></button>' +
		'</div>'
	);
}

function reload_table() {
	$("#table_izin").dataTable().fnDestroy();
	fetch_data();
}

function open_modal_filter(){
	$('#modal-filter').modal('show');
}

function autoKaryawan(){
  $('.typeahead').on('focus', function() {
    $(this).parent().siblings().addClass('active');
  }).on('blur', function() {
    if (!$(this).val()) {
      $(this).parent().siblings().removeClass('active');
    }
  });

  var employee = new Bloodhound({
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.value)
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
      url: base_url+"izin/suggestKaryawan",

      replace: function(url, query) {
        return url + "#" + query;
      },
      ajax : {
        beforeSend: function(jqXhr, settings){
          settings.data = $.param({q: queryInput.val()})
          console.log(queryInput.val());
        },
        type: "POST"

      }
    }
  });

  $('#input_nama_karyawan').typeahead({
    highlight: true,
    hint: true,
  }, {
    name: 'employee',
    source: employee,
    limit: 3
  });
}

function form_validation() {
	$('#form-izin').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

		var input_list = [
			// 'input_nama_karyawan',
			'input_tanggal_awal',
			'input_tanggal_akhir',
			'input_keterangan',
			'input_status_approval',
			'input_jenis_izin',
			'input_departemen',
      'input_karyawan'
		];

		var input_list_error = [
      // 'input_nama_karyawan_error_detail',
			'input_tanggal_awal_error_detail',
			'input_tanggal_akhir_error_detail',
			'input_keterangan_error_detail',
			'input_status_approval_error_icon',
			'input_jenis_izin_error_icon',
      'input_departemen_error_icon',
      'input_karyawan_error_icon'
		];

		$.ajax({
			url: base_url + "izin/validation/",
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

							if( input_error == "input_tanggal_awal_error_detail"){
								$('#input_tanggal_awal_error_container .gj-icon').addClass('text-danger');
								$('#input_tanggal_awal_error_container .gj-icon').addClass('has-danger');
								$('[id=' + input_ + '_error_icon]').text('');
							}

              if( input_error == "input_tanggal_akhir_error_detail"){
								$('#input_tanggal_akhir_error_container .gj-icon').addClass('text-danger');
								$('#input_tanggal_akhir_error_container .gj-icon').addClass('has-danger');
								$('[id=' + input_ + '_error_icon]').text('');
							}

						} else {
							$('[id=' + input_error + ']').html('');
							$('[id=' + input_ + '_error_container]').removeClass('has-danger');
							$('[id=' + input_ + '_error_container]').addClass('has-success');
							$('[id=' + input_ + '_error_icon]').text('done');

							if( input_error == "input_tanggal_awal_error_detail"){
								$('#input_tanggal_awal_error_container .gj-icon').addClass('text-success');
								$('#input_tanggal_awal_error_container .gj-icon').addClass('has-success');
								$('[id=' + input_ + '_error_icon]').text('');
							}

              if( input_error == "input_tanggal_akhir_error_detail"){
								$('#input_tanggal_akhir_error_container .gj-icon').addClass('text-success');
								$('#input_tanggal_akhir_error_container .gj-icon').addClass('has-success');
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
						$('[id=' + input_ + '_error_icon]').text('done');
					}
					save();
				}
				$('#btn-save').attr('disabled', false);
			}
		});
	});
}

function save() {
	var url;
	if (save_method == "add") {
		url = base_url + "izin/insert/";
	} else {
		url = base_url + "izin/update/";
	}
	var form = $('#form-izin')[0];
	var formData = new FormData(form);
	$.ajax({
		url: url,
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
			$('#form-izin')[0].reset();
			window.open(base_url + "izin", "_self");
		}
	});
}

function preview_image_upload(){
	$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {

			var input = $(this).parents('.input-group').find(':text'),
			log = label;

			if( input.length ) {
				input.val(log);
			} else {
				if( log ) alert(log);
			}

		});
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					$('#img-upload').attr('src', e.target.result);
          $('#btn-hps-attachment').show();
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

		$("#imgInp").change(function(){
			readURL(this);
		});
	}

function load_modal_image(){
  var source = $('#myImg').attr('src');
  $('#imgBig').attr('src', source);
  $('#modal_image').modal('show');
}

function hapus_file_attachment(id="", file=""){
  $('#imgInp').val('');
  $('#img-upload').attr('src', null);
  $('#btn-hps-attachment').hide();
  $('#attachment-label').attr("placeholder", "");
}

function hapus_data(id) {
	if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "izin/delete/" + id,
			method: "GET",
			dataType: 'json',
			success: function (data) {
				if (data.status) {
					reload_table();
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			}
		});
	}
}

function validasi_cuti() {
  var id = $('#input_karyawan').val();
  if(id !== 'x'){
    $.ajax({
      url: base_url + "izin/validasi_cuti/" + id,
      method: "GET",
      dataType: 'json',
      success: function (data) {
        if(parseInt(data.jatah) == data.sudah_ambil){
          $('#input_karyawan_sisa_cuti').text("Sisa Cuti : 0 hari ");
          $("#input_karyawan_sisa_cuti").css({ 'display' : ''});
        } else {
          $('#input_karyawan_sisa_cuti').text('Sisa Cuti : '+ (parseInt(data.jatah)-data.sudah_ambil) + " hari ");
          $("#input_karyawan_sisa_cuti").css({ 'display' : ''});
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(errorThrown);
        alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
      }
    });
  } else {
    $("#input_karyawan_sisa_cuti").css({ 'display' : 'none'});
  }
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
