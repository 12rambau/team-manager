$(function () {

    // page is now ready, initialize the calendar..

    var fullCalendar = $('#calendar').fullCalendar({
        themeSystem: 'bootstrap4',
        header: {
            left: 'month,agendaWeek,agendaDay',
            center: 'title',
            right: 'prev,today,next'
        },
        defaultView: 'month',
        weekNumbers: false,
        //weekNumberTitle: "Week",
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        slotLabelFormat: 'H:mm',
        nowIndicator: true,
        navLinks: true,

        //TODO add a functionnal filter for the event 
        events: Routing.generate('event-get'),

        eventColor: 'purple',

        eventClick: function (event, jsevent, view) {
            window.open(Routing.generate('event-view', { slug: event.url }));
            return false;
        }
    });
});

function checkAll(element) {

    var nbButton = $("#tag-list").data('tags');

    checkedAll = $(element).hasClass('btn-outline-white');

    //check all buttons
    for (let i = 0; i < nbButton; i++) {
        var button = $("#tag-button-" + i);
        var check = $("#tag-check-" + i)
        var color = button.data('color');

        //fill or empty the button
        wrongClass = (checkedAll) ? 'btn-outline-' + color : 'btn-' + color;
        if (button.hasClass(wrongClass)) {
            button.toggleClass('btn-outline-' + color);
            button.toggleClass('btn-' + color);
            check.toggleClass('fa-square');
            check.toggleClass('fa-check-square');
        }
    }

    //change the final button
    testAllCheck();
}

function checkButton(element, color) {
    $(element).toggleClass('btn-outline-' + color);
    $(element).toggleClass('btn-' + color);
    $(element).find("span").toggleClass('fa-square');
    $(element).find("span").toggleClass('fa-check-square');

    testAllCheck();

}

function testAllCheck() {

    var nbButton = $("#tag-list").data('tags');
    var checkAllButton = $("#check-all-tag");

    var check = true;

    for (let i = 0; i < nbButton; i++) {
        //check if button are all checked 
        var button = $("#tag-button-" + i);
        var color = button.data('color');

        if (button.hasClass('btn-outline-' + color)) {
            check = false;
            break;
        }
    }

    //fill or empty the button
    wrongClass = (check) ? 'btn-outline-white' : 'btn-white';
    if (checkAllButton.hasClass(wrongClass)) {
        checkAllButton.toggleClass('btn-outline-white');
        checkAllButton.toggleClass('btn-white');
        checkAllButton.find("span").toggleClass('fa-square');
        checkAllButton.find("span").toggleClass('fa-check-square');
    }



}