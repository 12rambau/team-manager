//css
import '../css/event.css';

//js 
import $ from 'jquery';
import jQuery from 'jquery';
import 'bootstrap';
import 'webpack-jquery-ui';
import moment from 'moment';
import * as leaflet from './event/leaferMap';
import * as view from './event/view';

//global function just for tempus-dominus TODO wait for evolution
global.jQuery = jQuery;
global.moment = moment;
require('tempusdominus-bootstrap-4');

$(function () {
    if ($("#map").length) leaflet.display();
    view.addListener();
    $("[id^=timepicker]").datetimepicker({ format: 'hh:mm:ss,SS' });
});

$(".draggable").draggable({
    cursor: "move",
    snap: '.dropdown',
    revert: 'invalid'
});

$(".dropdown").droppable({
    drop: function(event,ui){
        view.drop(ui, this);
        $("#myParticipation-button").trigger('click'); //to launch the saving
    }
});

$("#add-stat").click(function(event){
    event.preventDefault();
    view.addStat(this);
    view.addListener();
});

$("#myComment").focusout(function(){
    view.copyComment($(this).val());
});

$("[id^='list_participation_participations_']").change(function(){
    view.updateParticipation();
});

$("#myParticipation-button").click(function()
{
    view.updateParticipation();
});

