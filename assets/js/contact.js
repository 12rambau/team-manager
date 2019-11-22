//css
import '../css/contact.css'

//js
import $ from 'jquery';
import 'bootstrap';
import * as leaflet from './event/leaferMap';

$(function () {
    if ($("#map").length) leaflet.display();
});