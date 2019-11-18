

function displayDetails(e) {

    e.preventDefault();
    var slug = e.target.id.replace('-button', '');

    if ($(e.target).attr('class') == "fa fa-chevron-down") {
        $("#" + slug + "-details").slideDown(200);
        $(e.target).removeClass("fa-chevron-down");
        $(e.target).addClass("fa-chevron-up");
    } else if ($(e.target).attr('class') == "fa fa-chevron-up") {
        $("#" + slug + "-details").slideUp(200);
        $(e.target).removeClass("fa-chevron-up");
        $(e.target).addClass("fa-chevron-down");
    }
}