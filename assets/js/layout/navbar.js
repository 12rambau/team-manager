import $ from 'jquery';

export function update() {
    var headerHeight = $('#logo').outerHeight(); //2px padding top of the body element
    // Collapse Navbar
    var navbarCollapse = function () {
        if ($(window).scrollTop() > headerHeight) {
            $('#secondNav').slideDown(200);
        } else {
            $('#secondNav').slideUp(200);
        }
    };
    // Collapse now if page is not at top
    navbarCollapse();
    // Collapse the navbar when page is scrolled
    $(window).scroll(navbarCollapse);
}