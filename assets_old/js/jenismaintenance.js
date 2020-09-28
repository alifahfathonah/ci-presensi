var table;
var save_method;
$(document).ready(function () {

	$('#sandbox-container .input-daterange').datepicker({
		format: 'd M yyyy',
		todayHighlight: true,
		language: "id"
	});

	table = $('#table_jenismaintenance').DataTable({
		"processing": true,
		"serverSide": true,
		"order": [],

		"ajax": {
			"url": base_url + "jenismaintenance/ajax_list/",
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

function hapus_data(id, id_barang) {
	if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "jenismaintenance/hapus/" + id,
			method: "GET",
			success: function (data) {
				window.open(base_url + "jenismaintenance", "_self");
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			}
		});
	}
}