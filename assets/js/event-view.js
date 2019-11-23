//css
import '../css/event.css';

//js 
import $ from 'jquery';
import 'bootstrap';
import 'jquery-ui/ui/widget';
import * as leaflet from './event/leaferMap';
import * as custom from './layout/customButton';
import * as view from './event/view';

$(function () {
    if ($("#map").length) leaflet.display();
});

$(".custom-radio").click(function(){
    custom.checkCustomRadio(this);
})

$(".draggable").draggable({
    snap: '.dropdown',
    revert: 'invalid'
});

$(".dropdown").droppable({
    drop: view.drop(ui.draggable, this),
    activate: view.changeBg(this),
    deactivate: view.changeBg(this),

})