var table;
var table_2;
var save_method;
$(document).ready(function () {
	
	$('#sandbox-container .input-daterange').datepicker({
		format: 'd M yyyy',
		todayHighlight: true,
		language: "id"
	});

	table = $('#table_pengadaan').DataTable({
		"processing": true,
		"serverSide": true,
		"order": [],

		"ajax": {
			"url": base_url + "pengadaan/ajax_list/",
			"type": "POST"
		},

		"columnDefs": [{
			"targets": [-1],
			"orderable": false,
		}, ],
	});

	var id_pengadaan = $('#id_pengadaan').val();
	console.log('id_pengadaan ' + id_pengadaan);
	

	table_2 = $('#table_daftar_barang').DataTable({
		"processing": true,
		"serverSide": true,
		"order": [],

		"ajax": {
			"url": base_url + "pengadaan/ajax_list_detail/" + id_pengadaan,
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

function delete_data(id) {
	if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "pengadaan/delete/" + id,
			method: "GET",
			dataType: 'json',
			success: function (data) {
				if (data.status) {
					alert("Data berhasil dihapus");
					reload_table();
				} else {
					alert("Data gagal dihapus");
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			}
		});
	}
}
