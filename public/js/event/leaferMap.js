var lat = document.getElementById('event_location_lat').value;
var lng = document.getElementById('event_location_lng').value; 


var mymap = L.map('map').setView(
    [lat,lng],
    13
);

 L.tileLayer(
     'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', 
     {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox.streets',
        accessToken: 'pk.eyJ1IjoiYm9ybnRvYmVhbGl2ZSIsImEiOiJjanNuaWdkNnAwMm5tM3pvNmlrdG1xbzRmIn0.omq4r8w1DXr7iYr5eW0nMA',
    }
).addTo(mymap);

var marker = L.marker([lat, lng]).addTo(mymap);

function moveMarker(){

    lat = document.getElementById('event_location_lat').value;
    lng = document.getElementById('event_location_lng').value;

    var latLng = [lat, lng];
    marker.setLatLng(latLng).update();
    mymap.setView(latLng);
}