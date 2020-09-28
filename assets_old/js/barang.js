var table;
var save_method;
$(document).ready(function () {

	$('.carousel').carousel();

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#img-upload').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}

	$("#imgInp").change(function () {
		readURL(this);
	});

	$('#sandbox-container .input-daterange').datepicker({
		format: 'd M yyyy',
		todayHighlight: true,
		language: "id"
	});

	table = $('#table_barang').DataTable({
		"processing": true,
		"serverSide": true,
		"order": [],

		"ajax": {
			"url": base_url + "barang/ajax_list/" ,
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

// function delete_data(id) {
// 	if (confirm('Anda yakin akan menghapus data ini?')) {
// 		$.ajax({
// 			url: base_url + "pengadaan/delete/" + id,
// 			method: "GET",
// 			dataType: 'json',
// 			success: function (data) {
// 				if (data.status) {
// 					alert("Data berhasil dihapus");
// 					reload_table();
// 				} else {
// 					alert("Data gagal dihapus");
// 				}
// 			},
// 			error: function (jqXHR, textStatus, errorThrown) {
// 				console.log(errorThrown);
// 				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
// 			}
// 		});
// 	}
// }

function hapus_data(id, id_barang) {
	if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "maintenance/hapus/" + id + "/" + id_barang,
			method: "GET",
			success: function (data) {
				window.open( base_url + 'barang/cek_histori_perawatan/'+id_barang, "_self");
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			}
		});
	}
}

function hapus_gambar(id, id_barang) {
	if (confirm('Anda yakin akan menghapus data ini?')) {
		$.ajax({
			url: base_url + "barang/hapus_gambar/" + id,
			method: "GET",
			success: function (data) {
				window.open(base_url + 'barang/cek_histori_perawatan/' + id_barang, "_self");
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
				alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
			}
		});
	}
}