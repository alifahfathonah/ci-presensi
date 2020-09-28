var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function () {
  form_validation();

	filter_date = false;

  table = $('#table_jabatan').DataTable({
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
			"url": base_url + "jabatan/ajax_list/",
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

function form_validation() {
	$('#form-jabatan').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

		var input_list = [
			'input_nama_jabatan',
			'input_keterangan',
		];

		var input_list_error = [
			'input_nama_jabatan_error_detail',
			'input_keterangan_error_detail'
		];

		$.ajax({
			url: base_url + "jabatan/validation/",
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
		url = base_url + "jabatan/insert/";
	} else {
		url = base_url + "jabatan/update/";
	}
	var form = $('#form-jabatan')[0];
	var formData = new FormData(form);
	$.ajax({
		url: url,
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
      window.open(base_url + "jabatan", "_self");
      $('#form-jabatan')[0].reset();
		}
	});
}

function hapus_data(id) {
	if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "jabatan/delete/" + id,
			method: "GET",
			dataType: 'json',
			success: function (data) {
				if (data.status) {
					window.open(base_url + "jabatan", "_self");
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			}
		});
	}
}
