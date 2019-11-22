import $ from 'jquery';
import 'fullcalendar';

export function display() {

    // page is now ready, initialize the calendar..

    var fullCalendar = $('#calendar').fullCalendar({
        plugins: ['dayGrid', 'timegrid'],
        themeSystem: 'bootstrap4',
        allDayDefault: false,
        header: {
            left: 'month,agendaWeek,agendaDay',
            center: 'title',
            right: 'prev,today,next'
        },
        defaultView: 'month',
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        slotLabelFormat: 'H:mm',
        nowIndicator: true,
        navLinks: true,
        allDaySlot: false,

        //TODO add a functionnal filter for the event 
        events: Routing.generate('event-get'),

        eventColor: 'purple',

        eventClick: function (event, jsevent, view) {
            window.open(Routing.generate('event-view', { slug: event.url }));
            return false;
        }
    });
}