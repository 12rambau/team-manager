(function($) {
    "use strict"; // Start of use strict
  
    var headerHeight = 270;//$("#mainNav").height()*2+$('#logo').height();
    // Collapse Navbar
    var navbarCollapse = function() {
      if ($(window).scrollTop() > headerHeight) {
        $("#mainNav").addClass("fixed-top");
        $("#mainNav").addClass("bg-primary");
        $('.navbar-brand').removeClass("sr-only");
        $('#firstLi').removeClass("with-logo")
      } else {
        $("#mainNav").removeClass("fixed-top");
        $("#mainNav").removeClass("bg-primary");
        $('.navbar-brand').addClass("sr-only");
        $('#firstLi').addClass("with-logo")
      }
    };
    // Collapse now if page is not at top
    navbarCollapse();
    // Collapse the navbar when page is scrolled
    $(window).scroll(navbarCollapse);
  
  })(jQuery); // End of use strict

  