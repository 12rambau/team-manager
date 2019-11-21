import $ from 'jquery';
import places from 'places.js';

var placesInstance;

export function setInstance() {
    const fixedOptions = {
        appId: algolia_place_app_id, //define in twig (global variable)
        apiKey: algolia_place_api_id, //define in twig (global variable)
        container: document.querySelector('[id$="location_value"]')
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
    $('[id$="location_type"]').val(e.suggestion.type || '');
    $('[id$="location_name"]').val(e.suggestion.name || '');
    $('[id$="location_city"]').val(e.suggestion.city || '');
    $('[id$="location_country"]').val(e.suggestion.country || '');
    $('[id$="location_countryCode"]').val(e.suggestion.countryCode || '');
    $('[id$="location_administrative"]').val(e.suggestion.administrative || '');
    $('[id$="location_county"]').val(e.suggestion.county || '');
    $('[id$="location_suburb"]').val(e.suggestion.suburb || '');
    $('[id$="location_lat"]').val(e.suggestion.latlng["lat"] || '');
    $('[id$="location_lng"]').val(e.suggestion.latlng["lng"] || '');
    $('[id$="location_postcode"]').val(e.suggestion.postcode || '');

    //to trigger the map 
    $('[id$="location_lat"]').trigger('change');
}