$(function() {

    // page is now ready, initialize the calendar..

    var fullCalendar = $('#calendar').fullCalendar({
        themeSystem: 'bootstrap4',
        header:{
            left: 'month,agendaWeek,agendaDay',
            center: 'title',
            right: 'prev,today,next'
        },
        defaultView: 'month',
        weekNumbers: true, 
        weekNumberTitle: "Week",
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        slotLabelFormat: 'H:mm',
        nowIndicator: true,
        navLinks: true,

        events: Routing.generate('event-get'),

        eventColor: 'purple',

        eventClick: function(event, jsevent, view){
            window.open(Routing.generate('event-view', {slug: event.url}));
            return false;
        }
    });
});