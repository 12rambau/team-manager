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
import * as index from './event/index';
import * as custom from './layout/customButton';

$(function () {
    //$("[id^=datepicker]").datetimepicker({ format: 'L' });
    //$("[id^=timepicker]").datetimepicker({ format: 'LT' });

    if ($("#map").length) leaflet.display();
    if ($("[id$='location_value']").length) autoload.setInstance();
});

$("[id$='location_lat']").change(function () {
    leaflet.moveMarker();
});

$("[id^='select_event_tag_']").click(function () {
    tag.changeCheckValue(this);
});

$("#check-all-tag").click(function () {
    tag.checkAll(this);
});

$("[id^='tag-button-']").click(function () {
    tag.checkButton(this);
})

$("[id^='radio-button-']").click(function () {
    if (!$(this).hasClass('validated')) {
        index.updateIndex(this);
        custom.checkCustomRadio(this);
    }
})

