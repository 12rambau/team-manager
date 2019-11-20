//css

//js
import $ from 'jquery';
import * as calendar from './calendar/myCalendar';

$(function(){
    calendar.display();
});

$("#check-all-tag").click(function(){
    calendar.checkAll(this);
});

$("[id^='tag-button-']").click(function(){
    calendar.checkButton(this);
})

