import L from 'leaflet';

var map;
var marker;

// stupid hack so that leaflet's images work after going through webpack
{
    delete L.Icon.Default.prototype._getIconUrl;

    L.Icon.Default.mergeOptions({
        iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
        iconUrl: require('leaflet/dist/images/marker-icon.png'),
        shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
    });
}

//lat and lng are set directly by twi (global variable)
export function display() {
    map = L.map('map').setView(
        [lat.value, lng.value],
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
    ).addTo(map);

    marker = L.marker([lat.value, lng.value]).addTo(map);
}

export function changeSize(){
    map.invalidateSize();
}

export function moveMarker() {

    var latLng = [lat.value, lng.value];
    marker.setLatLng(latLng).update();
    map.setView(latLng);
}

