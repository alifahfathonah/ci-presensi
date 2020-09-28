var marker;
var map, infoWindow;

$(document).ready(function () {
  previewImage();
  
  if(window.location.pathname == "/presensi/user/presensimasuk"){
    initMap();
    // Check for the File API support.
    if (window.File && window.FileReader && window.FileList && window.Blob) {
      document.getElementById('files').addEventListener('change', handleFileSelect, false);
    } else {
      alert('The File APIs are not fully supported in this browser.');
    }
  } else if(window.location.pathname == "/presensi/user/presensipulang"){
    initMap();
    // Check for the File API support.
    if (window.File && window.FileReader && window.FileList && window.Blob) {
      document.getElementById('files').addEventListener('change', handleFileSelect, false);
    } else {
      alert('The File APIs are not fully supported in this browser.');
    }
  }
  
  form_validation_izin();
  $('#input_tanggal_awal').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  $('#input_tanggal_akhir').datepicker({
    showOtherMonths: true,
    format: 'dd-mm-yyyy'
  });

  if(window.location.pathname == "/presensi/user/add_izin"){
    validasi_cuti();
  }

});

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 17,
    // mapTypeId: 'satellite'

  });
  infoWindow = new google.maps.InfoWindow;

  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    console.log('oke');
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      console.log(position.coords.latitude);
      infoWindow.setPosition(pos);
      infoWindow.setContent('Anda Disini');
      infoWindow.open(map);
      map.setCenter(pos);
      arePointsNearAjax(position.coords.latitude, position.coords.longitude);
      // console.log(position.coords.latitude +" | "+ position.coords.longitude);
    }, function() {
      handleLocationError(true, infoWindow, map.getCenter());

    });

  } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, infoWindow, map.getCenter());
  }
}

function initMapPulang() {
  map = new google.maps.Map(document.getElementById('map_pulang'), {
    zoom: 17,
    // mapTypeId: 'satellite'

  });
  infoWindow = new google.maps.InfoWindow;

  // Try HTML5 geolocation.
  if (navigator.geolocation) {
    console.log('oke');
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      console.log(position.coords.latitude);
      infoWindow.setPosition(pos);
      infoWindow.setContent('Anda Disini');
      infoWindow.open(map);
      map.setCenter(pos);
      arePointsNearAjax(position.coords.latitude, position.coords.longitude);
      // console.log(position.coords.latitude +" | "+ position.coords.longitude);
    }, function() {
      handleLocationError(true, infoWindow, map.getCenter());

    });

  } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, infoWindow, map.getCenter());
  }
}


function handleLocationError(browserHasGeolocation, infoWindow, pos) {

  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
    ? "Error: The Geolocation service failed."
    : "Error: Your browser doesn't support geolocation."
  );
  infoWindow.open(map);
}

function modal_presensi(){
  var periode = $('#input_tahun_presensi').val() + "-" + $('#input_bulan_presensi').val();
  $.ajax({
    url: base_url + "user/listKehadiran/" + periode,
    method: "GET",
    success: function (html) {
      $('#result-presensi-container').html(html);
      $('#modal-rekap-presensi').modal('show');
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.log(errorThrown);
      alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
    }
  });

}

function modal_masuk(){
  // $('#modal-absen-masuk').modal('show');
  initMap();
}

// function modal_pulang(){
//   // $('#modal-absen-masuk').modal('show');
//   initMapPulang();
// }   

function arePointsNearAjax(lat, long) {
  $('#btn-save-upload').hide();
  var parameter = [lat, long];
  $.ajax({
    url: base_url + 'lokasi/cekLokasi',
    method: 'POST',
    dataType: 'json',
    data: {param: parameter},
    success: function (data) {
      console.log(data.message);
      
      console.log('status '+data.status);
      
        if( parseInt($('#status_lock').val()) === 1){ //jika status lock
          if(data.status){
            $('#btn-save-upload').show();
          } else {
            $('#btn-save-upload').hide();
          }
        } else if( parseInt($('#status_lock').val()) === 0) {
             $('#btn-save-upload').show();
        }
      
    //   if(data.status){
    //     if( $('#status_lock').val() == "1"){
    //       $('#btn-save-upload').show();
    //     } else {
    //       $('#btn-save-upload').hide();
    //     }
    //   } else {
    //     if( $('#status_lock').val() == "0"){
    //       $('#btn-save-upload').show();
    //     } else {
    //       $('#btn-save-upload').hide();
    //     }
    //   }
        
        $('#label-lokasi').text('Lokasi : '+data.message);
        $('#input_lat').val(lat);
        $('#input_long').val(long);
        $('#input_lokasi').val(data.message);
        
      if( $('#input_id_record').val()==""){
        $('#judul-modal-upload').text('Foto Presensi Masuk');
      } else {
        $('#judul-modal-upload').text('Foto Presensi Pulang');
      }
      
      $('#btn-save-upload').text('Submit');
      $('#btn-save-upload').attr('disabled', false);
    //   $('#modal-absen-masuk').modal('show');    
      
    }
  });
}


function upload_file(){
  var form = $('#form-foto')[0];
  var formData = new FormData(form);
//   alert('Processing...');
  
  var url = "";
  
  if( $('#input_id_record').val()==""){
    url = base_url + "user/masuk/";
  } else {
    url = base_url + "user/pulang/";
  }
  
  $('#btn-save-upload').text('Proccessing...');
  $('#btn-save-upload').attr('disabled', true);
  
  
  $.ajax({
    url: url,
    method: 'POST',
    data: formData,
    dataType: 'json',
    contentType: false,
    processData: false,
    success: function (data) {
      if(data.status){
        //   window.open(base_irl + "user");
        
        if( $('#input_id_record').val()==""){
            alert('Presensi Masuk Berhasil');
        } else {
            alert('Presensi Pulang Berhasil');
        }
          
        $('#modal-absen-masuk').modal('hide');
        window.open(base_url + "user", "_self");
      } else {
        alert('Presensi Gagal. Silahkan coba lagi.'.data.message);
      }
    }
  });
}

// function upload_file_pulang(){
//   var form = $('#form-foto-pulang')[0];
//   var formData = new FormData(form);
//   alert('Processing...');
//   $.ajax({
//     url: base_url + "user/pulang/",
//     method: 'POST',
//     data: formData,
//     dataType: 'json',
//     contentType: false,
//     processData: false,
//     success: function (data) {
//       if(data.status){
//         //   window.open(base_irl + "user");
//         alert('Presensi Masuk Berhasil');
//         $('#modal-absen-pulang').modal('hide');
//       } else {
//         alert('Presensi Pulang Gagal. Silahkan coba lagi.');
//       }
//     }
//   });
// }

function previewImage() {
  $(document).on('change', '.btn-file :file', function() {
    var input = $(this),
    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      input.trigger('fileselect', [label]);
    });

    $('.btn-file :file').on('fileselect', function(event, label) {

      var input = $(this).parents('.input-group').find(':text'),
      log = label;

      if( input.length ) {
        input.val(log);
      } else {
        if( log ) console.log(log);
      }

    });
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#img-upload').attr('src', e.target.result);
          
        }

        reader.readAsDataURL(input.files[0]);
        // handleFileSelect(input);
      }
    }

    $("#imgInp").change(function(e){
      readURL(this);
    });
    
    var input = document.querySelector('input[type=file]');

        // You will receive the Base64 value every time a user selects a file from his device
        // As an example I selected a one-pixel red dot GIF file from my computer
        input.onchange = function () {
          var file = input.files[0],
            reader = new FileReader();
        
          reader.onloadend = function () {
            // Since it contains the Data URI, we should remove the prefix and keep only Base64 string
            var b64 = reader.result.replace(/^data:.+;base64,/, '');
            document.getElementById('input_img_base64').value = b64;
          };
        
          reader.readAsDataURL(file);
        };
        
    if (window.File && window.FileReader && window.FileList && window.Blob) {
    //   document.getElementById('files').addEventListener('change', handleFileSelect, false);
    } else {
      alert('The File APIs are not fully supported in this browser.');
    }    
  }
  
//   function previewImagePulang() {
//   $(document).on('change', '.btn-file :file', function() {
//     var input = $(this),
//     label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
//       input.trigger('fileselect', [label]);
//     });

//     $('.btn-file :file').on('fileselect', function(event, label) {

//       var input = $(this).parents('.input-group').find(':text'),
//       log = label;

//       if( input.length ) {
//         input.val(log);
//       } else {
//         if( log ) console.log(log);
//       }

//     });
//     function readURL(input) {
//       if (input.files && input.files[0]) {
//         var reader = new FileReader();

//         reader.onload = function (e) {
//           $('#img-upload-pulang').attr('src', e.target.result);
//         }

//         reader.readAsDataURL(input.files[0]);
//       }
//     }

//     $("#imgInpPulang").change(function(){
//       readURL(this);
//     });
//   }

  function getKaryawanByDept(){
    $.ajax({
      url: base_url + "user/getKaryawanByDept/" + $('#input_departemen').val(),
      method: "GET",
      success: function (html) {
        $('#input_karyawan').html(html);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(errorThrown);
        alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
      }
    });
  }

  function validasi_cuti() {
    var id = $('#input_karyawan').val();
    if(id !== 'x'){
      $.ajax({
        url: base_url + "izin/validasi_cuti/" + id,
        method: "GET",
        dataType: 'json',
        success: function (data) {
          if(parseInt(data.jatah) == data.sudah_ambil){
            $('#input_karyawan_sisa_cuti').text("Sisa Cuti : 0 hari ");
            $("#input_karyawan_sisa_cuti").css({ 'display' : ''});
          } else {
            $('#input_karyawan_sisa_cuti').text('Sisa Cuti : '+ (parseInt(data.jatah)-data.sudah_ambil) + " hari ");
            $("#input_karyawan_sisa_cuti").css({ 'display' : ''});
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.log(errorThrown);
          alert('Error get data from ajax' + jqXHR + textStatus + errorThrown);
        }
      });
    } else {
      $("#input_karyawan_sisa_cuti").css({ 'display' : 'none'});
    }
  }

  function form_validation_izin() {
    $('#form-izin').on('submit', function (event) {
      event.preventDefault();
      event.stopPropagation();

      var input_list = [
        // 'input_nama_karyawan',
        'input_tanggal_awal',
        'input_tanggal_akhir',
        'input_keterangan',
        'input_status_approval',
        'input_jenis_izin',
        'input_departemen',
        'input_karyawan'
      ];

      var input_list_error = [
        // 'input_nama_karyawan_error_detail',
        'input_tanggal_awal_error_detail',
        'input_tanggal_akhir_error_detail',
        'input_keterangan_error_detail',
        'input_status_approval_error_icon',
        'input_jenis_izin_error_icon',
        'input_departemen_error_icon',
        'input_karyawan_error_icon'
      ];

      $.ajax({
        url: base_url + "izin/validation/",
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

                if( input_error == "input_tanggal_awal_error_detail"){
                  $('#input_tanggal_awal_error_container .gj-icon').addClass('text-danger');
                  $('#input_tanggal_awal_error_container .gj-icon').addClass('has-danger');
                  $('[id=' + input_ + '_error_icon]').text('');
                }

                if( input_error == "input_tanggal_akhir_error_detail"){
                  $('#input_tanggal_akhir_error_container .gj-icon').addClass('text-danger');
                  $('#input_tanggal_akhir_error_container .gj-icon').addClass('has-danger');
                  $('[id=' + input_ + '_error_icon]').text('');
                }

              } else {
                $('[id=' + input_error + ']').html('');
                $('[id=' + input_ + '_error_container]').removeClass('has-danger');
                $('[id=' + input_ + '_error_container]').addClass('has-success');
                $('[id=' + input_ + '_error_icon]').text('done');

                if( input_error == "input_tanggal_awal_error_detail"){
                  $('#input_tanggal_awal_error_container .gj-icon').addClass('text-success');
                  $('#input_tanggal_awal_error_container .gj-icon').addClass('has-success');
                  $('[id=' + input_ + '_error_icon]').text('');
                }

                if( input_error == "input_tanggal_akhir_error_detail"){
                  $('#input_tanggal_akhir_error_container .gj-icon').addClass('text-success');
                  $('#input_tanggal_akhir_error_container .gj-icon').addClass('has-success');
                  $('[id=' + input_ + '_error_icon]').text('');
                }
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
            save_izin();
          }
          $('#btn-save').attr('disabled', false);
        }
      });
    });
  }

  function save_izin() {
    var url;
    if (save_method == "add") {
      url = base_url + "izin/insert/";
    } else {
      url = base_url + "izin/update/";
    }
    var form = $('#form-izin')[0];
    var formData = new FormData(form);
    $.ajax({
      url: url,
      method: 'POST',
      data: formData,
      dataType: 'json',
      contentType: false,
      processData: false,
      success: function (data) {
        $('#form-izin')[0].reset();
        window.open(base_url + "user", "_self");
      }
    });
  }

  function preview_image_upload(){
    $(document).on('change', '.btn-file :file', function() {
      var input = $(this),
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [label]);
      });

      $('.btn-file :file').on('fileselect', function(event, label) {

        var input = $(this).parents('.input-group').find(':text'),
        log = label;

        if( input.length ) {
          input.val(log);
        } else {
          if( log ) alert(log);
        }

      });
      function readURL(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            $('#img-upload').attr('src', e.target.result);
            $('#btn-hps-attachment').show();
          }

          reader.readAsDataURL(input.files[0]);
        }
      }

      $("#imgInp").change(function(){
        readURL(this);
      });
      
      
    }


// function handleFileSelect(param) {
//   var f = target.files[0]; // FileList object
//   var reader = new FileReader();
//   // Closure to capture the file information.
//   reader.onload = (function(theFile) {
//     return function(e) {
//       var binaryData = e.target.result;
//       //Converting Binary Data to base 64
//       var base64String = window.btoa(binaryData);
//       //showing file converted to base64
//       document.getElementById('base64').value = base64String;
//       console.log(base64String)
//     //   alert('File converted to base64 successfuly!\nCheck in Textarea');
//     };
//   })(f);
//   // Read in the image file as a data URL.
//   reader.readAsBinaryString(f);
// }