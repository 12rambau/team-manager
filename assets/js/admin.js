//css
import '../css/admin.css';

//js
import $ from 'jquery';
import 'bootstrap';
import * as leaflet from './event/leaferMap';
import * as autoload from './event/algolia';
import * as position from './admin/position';
import * as maxChar from './admin/max-char';

$(function () {
    if ($("#map").length) leaflet.display();
    if ($("[id$='location_value']").length) autoload.setInstance();
});

//need to be set manually to make sure it send the shown.bs.event
$(".nav-tabs a").click(function () {
    $(this).tab('show');
});

//the element name is generated by easy-admin
$('a[href="#_easyadmin_form_design_element_8"]').on('shown.bs.tab', function () {
    leaflet.changeSize()
});

$("[id$='location_lat']").change(function () {
    leaflet.moveMarker();
});

$("#_easyadmin_form_design_element_3-tab").one('shown.bs.tab', function (e) {
    //prevent the default behaviour of the "add field" button
    $("#easyadmin-add-collection-item-fieldtemplate_positions").removeAttr('onclick');
    position.initCross();
    position.initText();
    position.bindPositions();
});

$('#easyadmin-add-collection-item-fieldtemplate_positions').click(function (e) {
    e.preventDefault();
    position.addPosition();
    position.bindPositions();
});

$(".icon-input").change(function () {
    $("#"+$(this).attr('id')+"_preview").attr('class', "fab fa-" + $(this).val());
});

$(".color-input").change(function () {
    $("#"+$(this).attr('id')+"_preview").css('background-color', $(this).val());
});

$(".bootstrap-color-input").change(function () {
    $("#"+$(this).attr('id')+"_preview").attr('class', "form-control col-1 ml-2 bg-"+$(this).val());
});

//dealing with the max-character type area
$( function(){
    //count characters on start
    $(".max-character").each(function(){
        maxChar.updateMax(this);
    });
});

$('.max-character').keyup(function(){
    maxChar.updateMax(this);
});




