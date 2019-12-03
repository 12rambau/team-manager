//css
import '../css/event.css';

//js 
import $ from 'jquery';
import jQuery from 'jquery';
import 'webpack-jquery-ui';
import 'bootstrap';
import moment from 'moment';
import * as tag from './event/tag';
import * as leaflet from './event/leaferMap';
import * as autoload from './event/algolia';
import * as index from './event/index';
import * as add from './event/add'

//global function just for tempus-dominus TODO wait for evolution
global.jQuery = jQuery;
global.moment = moment;
require('tempusdominus-bootstrap-4');

$(function () {
    $("[id^=datepicker]").datetimepicker({ format: 'L' });
    $("[id^=timepicker]").datetimepicker({ format: 'LT' });

    if ($("#map").length) leaflet.display();
    if ($("[id$='location_value']").length) autoload.setInstance();
});

$("[id$='location_lat']").change(function () {
    leaflet.moveMarker();
});

$('input[id^="list_participation_participations"]').change(function () {
    index.updateIndex(this);
});

$("#event_team").change(function(){
    add.updateTags($(this).val());
});

