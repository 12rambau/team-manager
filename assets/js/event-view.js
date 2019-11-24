//css
import '../css/event.css';

//js 
import $ from 'jquery';
import 'bootstrap';
//import 'jquery-ui/ui/draggable';
import 'webpack-jquery-ui';
//import 'webpack-jquery-ui/droppable';
import * as leaflet from './event/leaferMap';
import * as view from './event/view';

$(function () {
    if ($("#map").length) leaflet.display();
    view.addListener();
});

$(".draggable").draggable({
    snap: '.dropdown',
    revert: 'invalid'
});

$(".dropdown").droppable({
    drop: function(event,ui){
        view.drop(ui.draggable, this);
    },
    activate: view.changeBg(this),
    deactivate: view.changeBg(this),

});

//add the datetimpicker 

$("#add-stat").click(function(event){
    event.preventDefault();
    view.addStat(this);
    view.addListener();
})

