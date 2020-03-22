var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
$(document).ready(function () {

    filter_date = false;
    fetch_data();

});

function fetch_data(start_date = '', end_date = '', start_2 = '', end_2 = '') {
    
    if (start_date == '' || end_date == '') {
    	var dt = new Date()
    	start_date = dt.getFullYear() + "-01-01";
        end_date = dt.getFullYear() + "-12-31";
        end_filter_date = end_date;
        start_filter_date = start_date;
        start_2 = '01 Jan 2020';
        end_2   = '31 Dec 2020';
    }

    $('#label_page').text("Laporan Data Kas Per Periode " + start_2 + "-" + end_2);

	table = $('#table_lap_trans_kas').DataTable({
        
		"lengthChange": true,
		"lengthMenu": [
			[10, 25, 50, -1],
			[10, 25, 50, "All"]
		],
		"autoWidth": true,
		"processing": true,
		"serverSide": true,
		"order": [],
        "dom": 
            "<'row'<'col-md-6 filter_container'><'col-md-6 text-right'l>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
		"buttons": ['colvis'],
		"ajax": {
			"url": base_url + "laptranskas/ajax_list/",
			"type": "POST",
			"data": function (data) {
				data.start_date = start_date;
				data.end_date = end_date;
			}
		},

		"columnDefs": [{
			"targets": [-1],
			"orderable": false,
        }, ],
	});

	$('.export_button_group_container .btn').removeClass('btn-secondary');
	$('.export_button_group_container .btn').addClass('btn-sm btn-outline-secondary');
	$('.filter_container').html(
		'<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">' +
		// '<button onclick="reload_table()" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-refresh"></b></button>' +
		'<div id="reportrange" class="btn btn-sm btn-outline-secondary ">' +
		'<b class="fa fa-calendar"></b>' +
		'</div>' +
		'<button class="btn btn-sm btn-outline-secondary" type="button" onclick="cetak_laporan()"><b class="fa fa-print"></b> Cetak Laporan</button>' +
		'<button onclick="reload_table()" type="button" class="btn btn-sm btn-outline-secondary" ><b class="fa fa-times"></b> Hapus Filter</button>' +
		'</div>'
    );
    
    $('#reportrange').html('<b class="fa fa-calendar"></b> ' + start_2 + ' - ' + end_2);
	//daterange datatables
	$(function () {
		var start = moment();
		var end = moment();

		function cb(start, end) {
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		}

		$('#reportrange').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
				'This Year': [moment().startOf('year').startOf('month'), moment().endOf('year').endOf('month')],
				'Last Year': [moment().subtract('year', 1).startOf('year').startOf('month'), moment().subtract('year', 1).endOf('year').endOf('month')]
			}
		}, cb);

		cb(start, end);

	});


	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		var start = picker.startDate.format('YYYY-MM-DD');
        var end = picker.endDate.format('YYYY-MM-DD');
        
        var start_2 = picker.startDate.format('DD MMM YYYY');
        var end_2 = picker.endDate.format('DD MMM YYYY');

		end_filter_date = end;
		start_filter_date = start;
        filter_date = true;
        $('#table_lap_trans_kas').DataTable().destroy();
        
		fetch_data(start, end, start_2, end_2);
	});
}

function reload_table() {
	window.open(base_url + 'laptranskas', "_self");
}

function cetak_laporan(){
    window.open(base_url + 'laptranskas/cetak/' + start_filter_date + "/" + end_filter_date, "_blank");
}   