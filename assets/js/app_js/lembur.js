var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
var marker;
$(document).ready(function () {
  filter_date = false;
  fetch_data();
  map_initialize_pulang();
  map_initialize_masuk();
  $('#input_tanggal_awal_filter').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_akhir_filter').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

});

function fetch_data( departemen = "", start_date="", end_date="", id_karyawan="", status_approval="" ) {
	table = $('#table_lembur').DataTable({
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
		"<'row'<'col-sm-12 col-md-6'l><'col-md-6 text-right filter_container'>>" +
		// "<'row'<'col-sm-12 filter_container'>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"+
		"<'row'<'col-sm-12 row_show '>>",
		"buttons": ['colvis'],
		"ajax": {
			"url": base_url + "lembur/ajax_list/",
			"type": "POST",
			"data" : function (data) {
			  data.start_date  = start_date;
        data.end_date    = end_date;
        data.departemen  = departemen;
        data.id_karyawan  = id_karyawan;
        data.status_approval = status_approval;
			}
		},

		"columnDefs": [{
			"targets": [-1],
			"orderable": false,
		}, ],
	});

	$('.filter_container').html(
		'<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">' +
		'<button onclick="open_modal_filter()" title="Filter" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-filter"></b></button>' +
    '<button onclick="reload_table()" title="Reload" type="button" class="btn btn-sm btn-outline-info" ><b class="fa fa-refresh"></b></button>' +
		'</div>'
	);
}

function reload_table() {
	$("#table_lembur").dataTable().fnDestroy();
	fetch_data();
}

function open_modal_filter(){
	$('#modal-filter').modal('show');
}

function search(){
  var dept  = $('#input_departemen_filter').val();
  var awal  = $('#input_tanggal_awal_filter').val();
  var akhir = $('#input_tanggal_akhir_filter').val();
  var id_karyawan = $('#input_karyawan_filter').val();
  var status_approval = $('#input_status_approval').val();

  $("#table_lembur").dataTable().fnDestroy();
  fetch_data(dept, awal, akhir, id_karyawan, status_approval);

}

function getKaryawanByDept(){
  $.ajax({
    url: base_url + "lembur/getKaryawanByDept/" + $('#input_departemen_filter').val(),
    method: "GET",
    success: function (html) {
      $('#input_karyawan_filter').html(html);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(errorThrown);
      alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
    }
  });
}

function updateStatusApproval(){
  var id = $('#input_id_lembur').val();
  var status = $('#input_status_approval').val();
  var parameter = [id, status];
  console.log(parameter);
  $.ajax({
		url: base_url + 'lembur/updateStatusApproval',
		method: 'POST',
    data: {param: parameter},
    dataType: 'json',
		success: function (data) {
      alert('Update OK');
		}
	});
}

function map_initialize_masuk(){
  var lat = $('#lat_masuk').val();
  var lon = $('#lon_masuk').val();
  var lokasi = $('#lokasi_masuk').val();
  // Variabel untuk menyimpan informasi lokasi
  var infoWindow = new google.maps.InfoWindow;
  //  Variabel berisi properti tipe peta
  var mapOptions = {
    center: new google.maps.LatLng(lat,lon),
	  zoom: 17,
    gestureHandling: 'cooperative',
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    disableDefaultUI: true,
    mapTypeControl: true,
  }
  // Pembuatan peta
  var peta = new google.maps.Map(document.getElementById('map-container-masuk'), mapOptions);
  // Variabel untuk menyimpan batas kordinat
  var bounds = new google.maps.LatLngBounds();

  addMarker(lat, lon, lokasi);

  // Proses membuat marker
  function addMarker(lat, lng, info){
    var lokasi = new google.maps.LatLng(lat, lng);
    bounds.extend(lokasi);
    var marker = new google.maps.Marker({
      map: peta,
      position: lokasi,
      animation: google.maps.Animation.BOUNCE
    });
    // peta.fitBounds(bounds);
    bindInfoWindow(marker, peta, infoWindow, info);
  }
  // Menampilkan informasi pada masing-masing marker yang diklik
  function bindInfoWindow(marker, peta, infoWindow, html){
    google.maps.event.addListener(marker, 'click', function() {
      infoWindow.setContent(html);
      infoWindow.open(peta, marker);
    });
  }
}

function map_initialize_pulang(){
  var lat = $('#lat_pulang').val();
  var lon = $('#lon_pulang').val();
  var lokasi = $('#lokasi_pulang').val();
  // Variabel untuk menyimpan informasi lokasi
  var infoWindow = new google.maps.InfoWindow;
  //  Variabel berisi properti tipe peta
  var mapOptions = {
    center: new google.maps.LatLng(lat,lon),
	  zoom: 17,
    gestureHandling: 'cooperative',
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    disableDefaultUI: true,
    mapTypeControl: true,
  }
  // Pembuatan peta
  var peta = new google.maps.Map(document.getElementById('map-container-pulang'), mapOptions);
  // Variabel untuk menyimpan batas kordinat
  var bounds = new google.maps.LatLngBounds();

  var lat = $('#lat_pulang').val();
  var lon = $('#lon_pulang').val();
  var lokasi = $('#lokasi_pulang').val();

  addMarker(lat, lon, lokasi);
  // console.log('lat '+lat);

  // Proses membuat marker
  function addMarker(lat, lng, info){
    var lokasi = new google.maps.LatLng(lat, lng);
    bounds.extend(lokasi);
    var marker = new google.maps.Marker({
      map: peta,
      position: lokasi,
      animation: google.maps.Animation.BOUNCE
    });
    // peta.fitBounds(bounds);
    bindInfoWindow(marker, peta, infoWindow, info);
  }
  // Menampilkan informasi pada masing-masing marker yang diklik
  function bindInfoWindow(marker, peta, infoWindow, html){
    google.maps.event.addListener(marker, 'click', function() {
      infoWindow.setContent(html);
      infoWindow.open(peta, marker);
    });
  }
}
