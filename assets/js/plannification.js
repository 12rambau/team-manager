// css
import '../css/plannification.css';

// js
import $ from 'jquery';
import * as field from './event/field';

$(function(){
    field.addListener();
})

$("#add-field").click(function (event) {
    event.preventDefault();
    field.addField(this);
    field.addListener();
    //add listener for the new buttons
});

