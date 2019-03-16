$(function() {

    // page is now ready, initialize the calendar..

    $('#calendar').fullCalendar({
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

        events: getEvents
    });
});