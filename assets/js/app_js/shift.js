var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function () {
  form_validation();

	filter_date = false;

	$('#input_jam_masuk').timepicker();
	$('#input_jam_pulang').timepicker();

  table = $('#table_shift').DataTable({
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
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"+
		"<'row'<'col-sm-12  row_show '>>",
		"buttons": ['colvis'],

		"ajax": {
			"url": base_url + "shift/ajax_list/",
			"type": "POST"
		},

		"columnDefs": [{
			"targets": [-1],
			"orderable": false,
		}, ],
	});

});

function reload_table() {
	table.ajax.reload(null, false);
}


function open_modal(){
	reset_validasi();
	save_method ="add";
	$('#modal-add').modal('show');
}

function form_validation() {
	$('#form-shift').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

		var input_list = [
			'input_kode_shift',
			'input_nama_shift',
		];

		var input_list_error = [
			'input_kode_shift_error_detail',
			'input_nama_shift_error_detail',
		];

		$.ajax({
			url: base_url + "shift/validation/",
			method: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			beforeSent: function () {
				$('#btn-save').attr('disabled', true);
			},
			success: function (data) {

				if (data.error) {
					for (let index = 0; index < input_list.length; index++) {
            console.log("------");
						const input_ = input_list[index];
						const input_error = input_list_error[index];
						if (data[input_error] !== "") {
							$('[id=' + input_error + ']').html(data[input_error]);
							$('[id=' + input_ + '_error_container]').addClass('has-danger');
              $('[id=' + input_ + '_error_icon]').text('clear');

							if( input_error == "input_jam_masuk_error_detail"){
								$('.gj-icon').addClass('text-danger');
								$('.gj-icon').addClass('has-danger');
								$('[id=' + input_ + '_error_icon]').text('');
							}

							if( input_error == "input_jam_pulang_error_detail"){
								$('.gj-icon').addClass('text-danger');
								$('.gj-icon').addClass('has-danger');
								$('[id=' + input_ + '_error_icon]').text('');
							}

						} else {
              $('[id=' + input_error + ']').html('');
              $('[id=' + input_ + '_error_container]').removeClass('has-danger');
							$('[id=' + input_ + '_error_container]').addClass('has-success');
              $('[id=' + input_ + '_error_icon]').text('done');

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
		url = base_url + "shift/insert/";
	} else {
		url = base_url + "shift/update/";
	}
	var form = $('#form-shift')[0];
	var formData = new FormData(form);
	$.ajax({
		url: url,
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
			$('#form-shift')[0].reset();
			$('#modal-add').modal('hide');
			reload_table();
		}
	});
}

function reset_validasi(){
	var input_list = [
		'input_kode_shift',
		'input_nama_shift',
	];

	var input_list_error = [
		'input_kode_shift_error_detail',
		'input_nama_shift_error_detail',
	];

	for (let index = 0; index < input_list.length; index++) {
		const input_ = input_list[index];
		const input_error = input_list_error[index];

		$('[id=' + input_error + ']').html('');
		$('[id=' + input_ + '_error_container]').removeClass('has-danger');
		$('[id=' + input_ + '_error_container]').removeClass('has-success');
		$('[id=' + input_ + '_error_icon]').text('');

	}
}

function detail(id){
	$.ajax({
		url: base_url + "shift/detail/"+id,
		method: 'GET',
		dataType: 'json',
		success: function (data) {
			$('[name=id]').val(data.id);
			$('[name=input_nama_shift]').val(data.nama_shift);
			$('[name=input_kode_shift]').val(data.kode);
			$('[id=input_jenis_shift_'+data.jenis_shift+']').prop('checked',true);
			$('[name=input_jam_masuk]').val(data.jam_masuk);
			$('[name=input_jam_pulang]').val(data.jam_pulang);

			if(data.jenis_shift == 0){
				hide_jam();
			}
			save_method = "edit";
			$('.card-title').text('Edit Data');
			$('#modal-add').modal('show');
		}
	});
}

function hide_jam(){
  $('#jam_container').hide();
  // $("#jam_container").css("displ/ay", "none");
}

function show_jam(){
  $('#jam_container').show();
}

function hapus_data(id) {
	if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "shift/delete/" + id,
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
