var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function(){
  $('.next span').removeClass('glyphicon fa-arrow-right');
  $('.next span').addClass('fa fa-arrow-right');

  $('.prev span').removeClass('glyphicon fa-arrow-left');
  $('.prev span').addClass('fa fa-arrow-left');

  filter_date = false;
  fetch_data();
});

function fetch_data(start_date='', end_date=''){
  table = $('#table_pelunasan').DataTable({
    "createdRow": function( row, data, dataIndex){
                if( data['merah'] === 1){
                    $(row).addClass('table-danger');
                }
            },
    "lengthChange" : true,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "autoWidth": true,
    "processing": true,
    "serverSide": true,
    "order": [],
    "dom" :
    "<'row'<'col-sm-12'<'col-md-6 pol_kiri'l><'col-md-6 text-right export_button_group_container'B>>>" +
    "<'row'<'col-sm-12 col-md-6 filter_container'><'col-sm-12 col-md-6'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    "buttons": ['colvis'],
    "ajax": {
      "url": base_url+"pelunasan/ajax_list/",
      "type": "POST",
      "data" : function (data) {
        data.start_date     = start_date;
        data.end_date       = end_date;
      }
    },

    "columnDefs": [
      {
        "targets": [ -1 ],
        "orderable": false,
      },
    ],
  });

  $('.export_button_group_container .btn').removeClass('btn-secondary');
  $('.export_button_group_container .btn').addClass('btn-sm btn-outline-secondary');
  $('.filter_container').html(
    '<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">' +
    '<button onclick="reload_table()" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-refresh"></b></button>' +
    '<div id="reportrange" class="btn btn-sm btn-outline-secondary ">' +
    '<b class="fa fa-filter"></b>'+
      '</div>'+
    '</div>'
  );
  //daterange datatables
  $(function() {
    var start = moment("2017-01-01 12:34:16");
    var end = moment("2018-03-03 10:08:07");

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


  $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
    var start = picker.startDate.format('YYYY-MM-DD');
    var end = picker.endDate.format('YYYY-MM-DD');
    end_filter_date = end;
    start_filter_date = start;
    filter_date = true;
    $('#table_pelunasan').DataTable().destroy();
    fetch_data(start, end);
  });
}

function reload_table(){
  window.open(base_url + 'pelunasan', "_self");
}
