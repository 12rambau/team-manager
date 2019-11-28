// css
import '../css/plannification.css';

// js
import $ from 'jquery';
import jQuery from 'jquery';
import 'webpack-jquery-ui';
import * as field from './event/field';
import * as participation from './event/participation';

$(function(){
    field.addListener();
    $("dropdown").each(function(item){
        participation.checkDroppability(item);
    })
})

$("#add-field").click(function (event) {
    event.preventDefault();
    field.addField(this);
    field.addListener();
});


$(".draggable").draggable({
    cursor: "move",
    snap: '.dropdown',
    revert: 'invalid'
});

$(".dropdown").droppable({
    drop: function(event,ui){
        //change the position and/or field
        participation.drop(ui, this);
        //save
        participation.updatePositions();
        participation.checkDroppability(this);
    },
    out: function(event,ui){
        participation.checkDroppability(this);
    }
});
