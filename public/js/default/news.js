

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

function shortHelper(element){

    //count the number of carracter in the input 
    var nbChar = element.value.length;
    var max = $(element).data('max');
    
    //change the helper value
    $('#blog_post_short_help').text(nbChar+"/"+max);
        
}