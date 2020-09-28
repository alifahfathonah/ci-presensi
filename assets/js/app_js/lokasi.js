var marker;
var table;
var save_method;
var filter_date;
var start_filter_date;
var end_filter_date;
var parameter;
$(document).ready(function () {
  // cekStatusLokasi();
  if(window.location.pathname == "/presensi/lokasi/" || window.location.pathname == "/presensi/lokasi"){
    map_initialize();
  } else {
    map_add_initialize();
  }
  form_validation();

  table = $('#table_lokasi').DataTable({
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
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"+
    "<'row'<'col-sm-12  row_show '>>",
    "buttons": ['colvis'],

    "ajax": {
      "url": base_url + "lokasi/ajax_list/",
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

function map_initialize(){
  // Variabel untuk menyimpan informasi lokasi
  var infoWindow = new google.maps.InfoWindow;
  //  Variabel berisi properti tipe peta
  var mapOptions = {
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    gestureHandling: 'cooperative',
    disableDefaultUI: true,
    mapTypeControl: true,
  }
  // Pembuatan peta
  var peta = new google.maps.Map(document.getElementById('map-container'), mapOptions);
  // Variabel untuk menyimpan batas kordinat
  var bounds = new google.maps.LatLngBounds();

  $.ajax({
    url: base_url + 'lokasi/get_data',
    method: 'GET',
    dataType: 'json',
    success: function (data) {
      var i;
      for (i = 0; i < data.length; i++) {
        addMarker(data[i].lat, data[i].lon, data[i].nama_lokasi);
        const cityCircle = new google.maps.Circle({
          strokeColor: "#FF0000",
          strokeOpacity: 0.8,
          strokeWeight: 1,
          fillColor: "#FF0000",
          fillOpacity: 0.35,
          center: new google.maps.LatLng(data[i].lat, data[i].lon),
          radius: parseInt(data[i].radius),
          clickable: false
        });

        cityCircle.setMap(peta)
      }
    }
  });

  // Proses membuat marker
  function addMarker(lat, lng, info){
    var lokasi = new google.maps.LatLng(lat, lng);
    bounds.extend(lokasi);
    var marker = new google.maps.Marker({
      map: peta,
      position: lokasi,
      animation: google.maps.Animation.BOUNCE
    });
    peta.fitBounds(bounds);
    bindInfoWindow(marker, peta, infoWindow, info);
  }
  // Menampilkan informasi pada masing-masing marker yang diklik
  function bindInfoWindow(marker, peta, infoWindow, html){
    google.maps.event.addListener(marker, 'click', function() {
      infoWindow.setContent(html);
      infoWindow.open(peta, marker);
    });
  }

  google.maps.event.addListener(peta, 'click', function(event) {
    var click = event.latLng;
    var locs = {lat: event.latLng.lat(), lng: event.latLng.lng()};
    arePointsNearAjax(event.latLng.lat(), event.latLng.lng());
  });

}

function buatMarker(peta, posisiTitik){

    if( marker ){
      // pindahkan marker
      marker.setPosition(posisiTitik);
    } else {
      // buat marker baru
      marker = new google.maps.Marker({
        position: posisiTitik,
        map: peta
      });
    }

     // isi nilai koordinat ke form
    document.getElementById("input_latitude").value = posisiTitik.lat();
    document.getElementById("input_longitude").value = posisiTitik.lng();

}

function map_add_initialize() {

  if(save_method == "edit"){
    var propertiPeta = {
      center: new google.maps.LatLng(document.getElementById("input_latitude").value, document.getElementById("input_longitude").value),
  	  zoom: 17,
      mapTypeId:google.maps.MapTypeId.ROADMAP,
      gestureHandling: 'cooperative',
      disableDefaultUI: true,
      mapTypeControl: true,
    };

  } else {
    var propertiPeta = {
      center: new google.maps.LatLng("-4.933950524859861", "121.88888223101446"),
  	  zoom: 4,
      mapTypeId:google.maps.MapTypeId.ROADMAP,
      gestureHandling: 'cooperative',
      disableDefaultUI: true,
      mapTypeControl: true,
    };
  }

  var peta = new google.maps.Map(document.getElementById("map-container-add"), propertiPeta);

  // Variabel untuk menyimpan batas kordinat
  var bounds = new google.maps.LatLngBounds();

    var lokasi = new google.maps.LatLng(document.getElementById("input_latitude").value, document.getElementById("input_longitude").value);
    buatMarker(peta, lokasi);

    if(save_method == 'edit'){
      const cityCircle = new google.maps.Circle({
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        center: new google.maps.LatLng(document.getElementById("input_latitude").value, document.getElementById("input_longitude").value),
        radius: parseInt(document.getElementById("input_radius").value),
        clickable: false
      });

      cityCircle.setMap(peta)
    }



  google.maps.event.addListener(peta, 'click', function(event) {
    buatMarker(this, event.latLng);
  });

}

function arePointsNearAjax(lat, long) {
  var parameter = [lat, long];
  $.ajax({
		url: base_url + 'lokasi/cekLokasi',
		method: 'POST',
    dataType: 'json',
    data: {param: parameter},
		success: function (data) {
      console.log(data.message);
		}
	});
}

function arePointsNear(checkPoint, centerPoint, m) {
  // centerPoint = titik kordinat yg sudah ditentukan
  // checkPoint = titik kordinat inputan
  var km = m/1000;
  var ky = 40000 / 360;
  var kx = Math.cos(Math.PI * centerPoint.lat / 180.0) * ky;
  var dx = Math.abs(centerPoint.lng - checkPoint.lng) * kx;
  var dy = Math.abs(centerPoint.lat - checkPoint.lat) * ky;
  return Math.sqrt(dx * dx + dy * dy) <= km;
}


function form_validation() {
  $('#form-lokasi').on('submit', function (event) {
    event.preventDefault();
    event.stopPropagation();

    var input_list = [
      'input_nama_lokasi',
      'input_latitude',
      'input_longitude',
      'input_radius'
    ];

    var input_list_error = [
      'input_nama_lokasi_error_detail',
      'input_latitude_error_detail',
      'input_longitude_error_detail',
      'input_radius_error_detail'
    ];

    $.ajax({
      url: base_url + "lokasi/validation/",
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      beforeSent: function () {
        $('#btn-save').attr('disabled', true);
      },
      success: function (data) {

        if (data.error) {
          for (let index = 0; index < input_list.length; index++) {
            const input_ = input_list[index];
            const input_error = input_list_error[index];
            if (data[input_error] !== "") {
              $('[id=' + input_error + ']').html(data[input_error]);
              $('[id=' + input_ + '_error_container]').addClass('has-danger');
              $('[id=' + input_ + '_error_icon]').text('clear');
            } else {
              $('[id=' + input_error + ']').html('');
              $('[id=' + input_ + '_error_container]').removeClass('has-danger');
              $('[id=' + input_ + '_error_container]').addClass('has-success');
              $('[id=' + input_ + '_error_icon]').text('done');
            }
          }
        }

        if (data.success) {
          for (let index = 0; index < input_list.length; index++) {
            const input_ = input_list[index];
            const input_error = input_list_error[index]

            $('[id=' + input_error + ']').html('');
            $('[id=' + input_ + '_error_container]').removeClass('has-danger');
            $('[id=' + input_ + '_error_container]').addClass('has-success');
            $('[id=' + input_ + '_error_icon]').text('done');
          }
          save();
        }

        $('#btn-save').attr('disabled', false);
      }
    });
  });
}

function save() {
  var url;
  if (save_method == "add") {
    url = base_url + "lokasi/insert/";
  } else {
    url = base_url + "lokasi/update/";
  }
  var form = $('#form-lokasi')[0];
  var formData = new FormData(form);
  $.ajax({
    url: url,
    method: 'POST',
    data: formData,
    dataType: 'json',
    contentType: false,
    processData: false,
    success: function (data) {
      window.open(base_url + "lokasi", "_self");
      $('#form-lokasi')[0].reset();
    }
  });
}

function hapus_data(id) {
  if (confirm('Anda yakin akan menghapus data ini?')) {
    $.ajax({
      url: base_url + "lokasi/delete/" + id,
      method: "GET",
      dataType: 'json',
      success: function (data) {
        if (data.status) {
          reload_table();
          map_initialize();
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(errorThrown);
        alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
      }
    });
  }
}
