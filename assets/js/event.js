//css
import '../css/event.css';

//js 
import $ from 'jquery';
import 'bootstrap';
import moment from 'moment';
//import 'tempusdominus-bootstrap-4';
import * as tag from './event/tag';
import * as leaflet from './event/leaferMap';
import './event/algolia';

$(function () {
    //$("[id^=datepicker]").datetimepicker({ format: 'L' });
    //$("[id^=timepicker]").datetimepicker({ format: 'LT' });
    //$("[id^=timepicker]").datetimepicker({ format: 'H:m:s' });

    leaflet.display();
    autoload.setInstance();
});

$("#event_location_lat").change(function () {
    leaflet.moveMarker();
});

$("[id^='select_event_tag_']").click(function () {
    tag.changeCheckValue(this);
});

alert('toto');

