

function displayDetails(e) {

    //prevent the default behaviour
    e.preventDefault();

    //identify the dom elements
    var slug = e.target.id.replace('-button', '');
    var details = $("#" + slug + "-details");

    //change visibility
    (details.is(':visible'))?details.slideUp(200):details.slideDown(200);

    //change the chevron orientation
    $(e.target).toogleClass("fa-chevron-down");
    $(e.target).tooglelass("fa-chevron-up");
}