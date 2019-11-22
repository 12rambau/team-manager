//css
import '../css/layout.css';

//js
import $ from 'jquery';
import * as miniCalendar from './calendar/minicalendar';
import * as navbar from './layout/navbar';
import * as chat from './layout/chat';
import * as custom from './layout/customButton';

//display the mini calendar
$(function () {
    miniCalendar.display(); 
});

//update the display of the navbar
$(function ($) {
    navbar.update();
});

//functions for the chat window management 
$("#chevron").click(function(e) {
    chat.changeChevron(e);
});

$("#chat-submit").click(function() {
    chat.submitChat(e);
});

/*$('.custom-radio').click(function(){
    custom.checkCustomRadio(this);
})*/
    

