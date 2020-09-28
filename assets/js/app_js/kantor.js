var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function () {
  form_validation();

	filter_date = false;

  table = $('#table_kantor').DataTable({
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
			"url": base_url + "kantor/ajax_list/",
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

function get_kota(){
	var id_prov = $('#input_provinsi').val();
	$.ajax({
			url: base_url + 'Indonesia/ajax_kota/' + id_prov,
			method: 'GET',
			success: function (html) {
				if(id_prov == "x"){
					$('#input_kota').attr("disabled", true);
					$('#input_kota').html('<option value="x" selected>-- Silahkan Pilih Provinsi Dahulu --</option>');
				} else {
					$('#input_kota').removeAttr("disabled");
					$('#input_kota').html(html);
				}
			}
	});
}


function form_validation() {
	$('#form-add-kantor').on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();

		var input_list = [
			'input_nama_kantor',
			'input_alamat',
			'input_no_telp_1',
      'input_jenis_kantor',
      'input_kota',
      'input_provinsi'
		];

		var input_list_error = [
			'input_nama_kantor_error_detail',
			'input_alamat_error_detail',
			'input_no_telp_1_error_detail',
      'input_jenis_kantor_error_icon',
      'input_kota_error_icon',
      'input_provinsi_error_icon'
		];

		$.ajax({
			url: base_url + "kantor/validation/",
			method: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			beforeSent: function () {
				$('#btn-save').attr('disabled', true);
			},
			success: function (data) {

        var id_kota = $('#input_kota').val();
        var id_prov = $('#input_provinsi').val();
        var id_jenis_kantor = $('#input_jenis_kantor').val();

				if (data.error) {
					for (let index = 0; index < input_list.length; index++) {
            console.log("------");
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
		url = base_url + "kantor/insert/";
	} else {
		url = base_url + "kantor/update/";
	}
	var form = $('#form-add-kantor')[0];
	var formData = new FormData(form);
	$.ajax({
		url: url,
		method: 'POST',
		data: formData,
		dataType: 'json',
		contentType: false,
		processData: false,
		success: function (data) {
      window.open(base_url + "kantor", "_self");
      $('#form-add-kantor')[0].reset();
		}
	});
}

function hapus_data(id) {
	if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "kantor/delete/" + id,
			method: "GET",
			dataType: 'json',
			success: function (data) {
				if (data.status) {
					window.open(base_url + "kantor", "_self");
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			}
		});
	}
}
