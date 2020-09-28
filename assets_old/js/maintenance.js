var table;
var save_method;
$(document).ready(function () {

	$('#sandbox-container .input-daterange').datepicker({
		format: 'd M yyyy',
		todayHighlight: true,
		language: "id"
	});

	// table = $('#table_barang').DataTable({
	// 	"processing": true,
	// 	"serverSide": true,
	// 	"order": [],

	// 	"ajax": {
	// 		"url": base_url + "barang/ajax_list/",
	// 		"type": "POST"
	// 	},

	// 	"columnDefs": [{
	// 		"targets": [-1],
	// 		"orderable": false,
	// 	}, ],
	// });


});

// function reload_table() {
// 	table.ajax.reload(null, false);
// }

