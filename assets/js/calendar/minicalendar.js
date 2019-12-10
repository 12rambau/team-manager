import $ from 'jquery';
import 'fullcalendar';

export function display() {
    $('#mini-calendar').fullCalendar({
        themeSystem: 'bootstrap4',
        header: {
            left: '',
            center: 'title',
            right: 'prev,today,next'
        },
        defaultView: 'listWeek',
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        slotLabelFormat: 'H:mm',
        nowIndicator: true,
        navLinks: false,

        events: Routing.generate('event-get'),

        displayEventTime: false,

        eventClick: function (event, jsevent, view) {
            window.open(Routing.generate('event-view', { slug: event.url, _locale: locale }));
            return false;
        }
    })
}