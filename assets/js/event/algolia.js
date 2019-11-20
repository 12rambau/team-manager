import $ from 'jquery';
import places from 'places.js';

var placesInstance;

export function setInstance() {
    const fixedOptions = {
        appId: algolia_place_app_id, //define in twig (global variable)
        apiKey: algolia_place_api_id, //define in twig (global variable)
        container: document.querySelector('#event_location_value')
    };

    const reconfigurableOptions = {
        language: locale, // define in twig (global variable)
        aroundLatLngViaIP: true // unable the extra search/boost around the source IP
    };

    placesInstance = places(fixedOptions).configure(reconfigurableOptions);

    placesInstance.on('change', function (e) {
        resultSelected(e);
    });
}


function resultSelected(e) {
    $("#event_location_type").val(e.suggestion.type || '');
    $("#event_location_name").val(e.suggestion.name || '');
    $("#event_location_city").val(e.suggestion.city || '');
    $("#event_location_country").val(e.suggestion.country || '');
    $("#event_location_countryCode").val(e.suggestion.countryCode || '');
    $("#event_location_administrative").val(e.suggestion.administrative || '');
    $("#event_location_county").val(e.suggestion.county || '');
    $("#event_location_suburb").val(e.suggestion.suburb || '');
    $("#event_location_lat").val(e.suggestion.latlng["lat"] || '');
    $("#event_location_lng").val(e.suggestion.latlng["lng"] || '');
    $("#event_location_postcode").val(e.suggestion.postcode || '');

    //to trigger the map 
    $("#event_location_lat").trigger('change');
}