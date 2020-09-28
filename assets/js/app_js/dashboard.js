var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function () {
  table = $('#table_karyawan').DataTable({
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
			"url": base_url + "karyawan/ajax_list/",
			"type": "POST",
			"data" : function (data) {
			  data.id_departemen    = null;
			}
		},

		"columnDefs": [{
			"targets": [-1],
			"orderable": false,
		}, ],
	});
});

function open_modal_presensi_hari_ini(){

  //ajax load data

  // $('.modal-title').text('Data Presensi Hari Ini');
  // $('#modal_today_attendance').modal('show');
}

function open_modal_belum_hadir_hari_ini(){

  //ajax load data

  // $('.modal-title').text('Data Belum Hadir Hari Ini');
  // $('#modal_today_attendance').modal('show');
}

function open_modal_tidak_hadir_hari_ini(){

  //ajax load data

  // $('.modal-title').text('Data Tidak Hadir Hari Ini');
  // $('#modal_today_attendance').modal('show');
}

function open_modal_izin_hari_ini(){

  //ajax load data

  // $('.modal-title').text('Data Izin / Cuti / Sakit Hari Ini');
  // $('#modal_today_attendance').modal('show');
}
