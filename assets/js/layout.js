//css
import '../css/layout.css';

//js
import $ from 'jquery';
import * as miniCalendar from './calendar/minicalendar';
import * as navbar from './layout/navbar';
import * as chat from '../../vendor/btba/chat-bundle/assets/js/chat';

//display the mini calendar
$(function () {
    miniCalendar.display(); 
});

//update the display of the navbar
$(function ($) {
    navbar.update();
});

//functions for the chat window management 
$(function(){
    $("#chevron").click(function(e) {
        chat.changeChevron(e.target);
    });
    
    $("#chat-submit").click(function(e) {
        chat.submitChat(e);
    });
});
    

