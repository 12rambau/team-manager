//css

//js
import $ from 'jquery';
import * as calendar from './calendar/myCalendar';
import * as tag from './event/tag'

$(function(){
    calendar.display();
});

$("#check-all-tag").click(function(){
    tag.checkAll(this);
});

$("[id^='tag-button-']").click(function(){
    tag.checkButton(this);
})

