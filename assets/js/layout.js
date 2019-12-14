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
$(function () {
    $("#chevron").click(function (e) {
        chat.changeChevron(e.target);
    });

    $("#chat-submit").click(function (e) {
        chat.submitChat(e);
    });

    //si hauteur du bas de la page atteint celle du footer alors hauteur == hauteur du footer
    var heightFooter = $('footer').outerHeight();


    $(window).scroll(function () {
        var scrollBottom = $(document).height() - $(window).height() - $(window).scrollTop();
        if (scrollBottom < heightFooter) {
            $("#chat").css('bottom', (heightFooter - scrollBottom) + "px");
        } else {
            $("#chat").css('bottom', 0);
        }
    });

});


