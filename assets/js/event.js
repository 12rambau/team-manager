//css
import '../css/event.css';

//js 
import $ from 'jquery';
import 'bootstrap';
import moment from 'moment';
//import 'tempusdominus-bootstrap-4';
import * as tag from './event/tag';
import * as leaflet from './event/leaferMap';
import * as autoload from './event/algolia';

$(function () {
    //$("[id^=datepicker]").datetimepicker({ format: 'L' });
    //$("[id^=timepicker]").datetimepicker({ format: 'LT' });
    //$("[id^=timepicker]").datetimepicker({ format: 'H:m:s' });
});

$("[id^='select_event_tag_']").click(function () {
    tag.changeCheckValue(this);
});

autoload.placesInstance.on('change', function (e) {
    autoload.resultSelected(e);
    leaflet.moveMarker();
});

