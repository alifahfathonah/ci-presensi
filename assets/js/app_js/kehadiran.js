var marker;
$(document).ready(function (){
  open_filter();

  var url = window.location.href;
  var url = url.split("/");

  if(url[5] == 'detail'){
    map_initialize_pulang();
    map_initialize_masuk();
  }

  $('#input_tanggal_awal_filter').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_akhir_filter').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_awal_filter_2').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_akhir_filter_2').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

});

function open_filter(){
  $('#modal-filter').modal('show');
}

function export_dept(){
  var departemen  = $('#input_departemen_filter').val();
  var start       = $('#input_tanggal_awal_filter').val();
  var end         = $('#input_tanggal_akhir_filter').val();
  window.open(base_url + 'kehadiran/excel_dept/' + departemen +'/'+ start +'/'+ end, "_self");
}

function load_data(){
  $('#table-scroll').html("<i>Processing...</i>");

  var departemen  = $('#input_departemen_filter').val();
  var start       = $('#input_tanggal_awal_filter').val();
  var end         = $('#input_tanggal_akhir_filter').val();

  var parameter = [departemen, start, end];
  $.ajax({
		url: base_url + 'kehadiran/load_data',
		method: 'POST',
    data: {param: parameter},
		success: function (html) {
      data_departemen(departemen, start, end);
      $('#table-scroll').html(html);
      jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
      $('#modal-filter').modal('hide');
		}
	});
}

function data_departemen(departemen, start="", end=""){
  $.ajax({
		url: base_url + 'departemen/json_detail/' + departemen,
		method: 'GET',
		success: function (data){
      console.log(data);
      if(departemen !== 'x'){
        var obj = JSON.parse(data);
        if(start == "" && end == ""){
          $('#judul_page_kehadirankaryawan').html('Data Kehadiran Dept. <b>'+obj.nama_departemen+'</b> Bulan Ini');
        } else {
          $('#judul_page_kehadirankaryawan').html('Data Kehadiran Dept. <b>'+obj.nama_departemen+'</b> Periode <b>'+start+'</b> s.d <b>'+end+'</b>');
        }
      } else if(departemen == 'x'){
        var obj = JSON.parse(data);
        if(start == "" && end == ""){
          $('#judul_page_kehadirankaryawan').html('Data Kehadiran All Dept. Bulan Ini');
        } else {
          $('#judul_page_kehadirankaryawan').html('Data Kehadiran All Dept. Periode <b>'+start+'</b> s.d <b>'+end+'</b>');
        }
      }
		}
	});
}

function cari(){
  var id_karyawan = $('#id_karyawan').val();
  var start = $('#input_tanggal_awal_filter_2').val();
  var end = $('#input_tanggal_akhir_filter_2').val();
  if(start != "" && end!=""){
    window.open(base_url + 'kehadiran/search/' + id_karyawan + '/' + start + '/' + end, "_self");
  }
  // $.ajax({
	// 	url: base_url + 'kehadiran/search/' + id_karyawan + '/' + start + '/' + end,
	// 	method: 'GET',
	// 	success: function (data){
  //     windo
	// 	}
	// });
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
