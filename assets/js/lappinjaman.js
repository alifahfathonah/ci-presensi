var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var start_2;
var end_2;
$(document).ready(function () {
	var dt = new Date()
	start_date = dt.getFullYear() + "-01-01";
	end_date = dt.getFullYear() + "-12-31";
	end_filter_date = end_date;
	start_filter_date = start_date;
	start_2 = '01 Jan 2020';
	end_2 = '31 Dec 2020';
	$('#reportrange').html('<b class="fa fa-calendar"></b> ' + start_2 + ' - ' + end_2);
	getdata(start_date, end_date);
	filter_date = false;
	fetch_data(start_filter_date, end_filter_date);

});

function getdata(start_date, end_date) {
	console.log('start ' + start_date);
	console.log('end ' + end_date);
	$.ajax({
		url: base_url + "lappinjaman/getdata/" + start_date + "/" + end_date,
		method: 'GET',
		success: function (html) {
			$('#content_lappinjaman').html(html);
		}
	});
}

function fetch_data(start_date = '', end_date = '') {

	var start = moment();
	var end = moment();

	$('#reportrange').daterangepicker({
		startDate: start,
		endDate: end,
		ranges: {
			'This Year': [moment().startOf('year').startOf('month'), moment().endOf('year').endOf('month')],
			'Last Year': [moment().subtract('year', 1).startOf('year').startOf('month'), moment().subtract('year', 1).endOf('year').endOf('month')]
		}
	});

	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		var start = picker.startDate.format('YYYY-MM-DD');
		var end = picker.endDate.format('YYYY-MM-DD');

		var start_2 = picker.startDate.format('DD MMM YYYY');
		var end_2 = picker.endDate.format('DD MMM YYYY');

		end_filter_date = end;
		start_filter_date = start;
		filter_date = true;
		getdata(start_filter_date, end_filter_date);
		$('#reportrange').html('<b class="fa fa-calendar"></b> ' + start_2 + ' - ' + end_2);
	});
}

function reload_table() {
	window.open(base_url + 'lappinjaman', "_self");
}

function cetak_laporan() {
	window.open(base_url + 'lappinjaman/cetak/' + start_filter_date + "/" + end_filter_date, "_blank");
}