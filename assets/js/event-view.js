//css
import '../css/event.css';

//js 
import $ from 'jquery';
import 'bootstrap';
import * as leaflet from './event/leaferMap';
import * as autoload from './event/algolia';
import * as index from './event/index';
import * as custom from './layout/customButton';

$(function () {
    if ($("#map").length) leaflet.display();
});

$(".custom-radio").click(function(){
    custom.checkCustomRadio(this);
})