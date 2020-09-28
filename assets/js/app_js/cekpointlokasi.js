
$(document).ready(function () {
  initMap();
});
function initMap() {
  var map;
  var marker;
  var user = {lat:4.624335 , lng: -74.063644 }; //use this as user location
  var diameter = 600; // change this to the preferred size in meters
  map = new google.maps.Map(document.getElementById('mapss'), {
    center: user,
    zoom: 16
  });

  var user_marker = new google.maps.Marker({
    map: map,
    position: user
  });

  var cityCircle = new google.maps.Circle({ //created a circle to mark the radius
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 2,
    fillColor: '#FF0000',
    fillOpacity: 0.35,
    map: map,
    center: user,
    radius: diameter,
    clickable: false
  });


  google.maps.event.addListener(map, 'click', function(event) {
    var click = event.latLng;
    var locs = {lat: event.latLng.lat(), lng: event.latLng.lng()};
    var n = arePointsNear(user, locs, diameter);
    console.log(locs);
    if(n){
      marker = new google.maps.Marker({
        map: map,
        position: locs,
        label: {
          text:"I", //marking all jobs inside radius with I
          color:"white"
        }
      });
    }else{
      marker = new google.maps.Marker({
        map: map,
        position: locs,
        label: {
          text:"O", //marking all jobs outside radius with O
          color:"white"
        }
      });
    }
  });
}

function arePointsNear(checkPoint, centerPoint, m) { // credits to user:69083
  var km = m/1000;
  var ky = 40000 / 360;
  var kx = Math.cos(Math.PI * centerPoint.lat / 180.0) * ky;
  var dx = Math.abs(centerPoint.lng - checkPoint.lng) * kx;
  var dy = Math.abs(centerPoint.lat - checkPoint.lat) * ky;
  return Math.sqrt(dx * dx + dy * dy) <= km;
}
