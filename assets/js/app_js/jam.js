$(document).ready(function () {
  setTimeout("waktu()", 1000);
});

function waktu() {
  var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

  var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum&#39;at', 'Sabtu'];

  var date = new Date();

  var day = date.getDate();

  var month = date.getMonth();

  var thisDay = date.getDay(),

     thisDay = myDays[thisDay];

  var yy = date.getYear();

  var year = (yy < 1000) ? yy + 1900 : yy;

  var tanggal = thisDay + ', ' + day + ' ' + months[month] + ' ' + year;

  var waktu = new Date();
  setTimeout("waktu()", 1000);
  var jam = waktu.getHours() + ":" + waktu.getMinutes() + ":" + waktu.getSeconds()
  document.getElementById("jam").innerHTML  = '<a style="font-size: 14px" class="navbar-brand" style="" href="javascript:;">'+tanggal+', '+jam+'</a>';
}
