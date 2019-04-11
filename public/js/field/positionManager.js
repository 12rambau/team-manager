// setup an "add a tag" link
var addPositionButton = $('<button type="button" class="btn btn-primary">Add a position</button>');
var newLinkLi = $('<li class="list-group-item"></li>').append(addPositionButton);

$(document).ready(function() {
    // Get the ul that holds the collection of tags
    var collectionHolder = $('#positions');

    //get the ul that holds the position names
    var namesHolder = $("#position-name");

    // add the "add a tag" anchor and li to the tags ul
    namesHolder.append(newLinkLi);

    // count the current form inputs we have, use that as the new
    // index when inserting a new item
    collectionHolder.data('index', collectionHolder.find(':input').length);

    addPositionButton.on('click', function(e) {
        addPositionForm(collectionHolder, newLinkLi);
    });
});

function addPositionForm(collectionHolder, newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = collectionHolder.data('prototype');

    // get the new index
    var index = collectionHolder.data('index');

    var newForm = prototype;

    // Replace '__name__' in the prototype's HTML
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var newFormLi = $('<li class="list-group-item"></li>').append(newForm);
    collectionHolder.append(newFormLi);

    //add the name in the aside list
    var newNameLi = $('<li class="list-group-item"></li>');
    newNameLi.attr('id', newFormLi.children(":first").attr('id')+"_namer");
    newNameLi.text('automatic name');
    newLinkLi.before(newNameLi);

    var card = $('<div/>',
    {
        id: newFormLi.children(":first").attr('id')+"_card",
        "class": "card position-card positionned",
        css:{
            "top": "0%",
            "left": "0%",
            "background": 'url('+phantomUrl+')'
        }
    });

    //display the new phantom on the field
    $("#positionning").append(card);

}

function removePosition(event){
    //prevent the button from validating the form
    event.preventDefault();

    //prevent the i from launching anything
    if ($(event.target).is('i')) return;
    
    //get the Id of the position
    var name = $(event.target).parent().attr('id');
    
    //remove the phantom card
    $("#"+name+"_card").remove();

    //remove the name in aside 
    $("#"+name+"_namer").remove();

    //remove the position form
    $(event.target).parent().parent().remove();
}


$(document).on("change", 'input[type=range]', function(event){

    //get the target HTML range input
    var target = $(event.target);

    //get the name of the position associated
    var position = target.parent().parent().attr('id');
    
    //change vertical or horizontal value
    if(target.attr('id') == position+"_horizontal")
        $("#"+position+"_card").css('left', target.val()+"%");
    else if(target.attr('id') == position+"_vertical")
        $("#"+position+"_card").css('top', target.val()+"%");

});

$(document).on('change', 'input[type=text][id^="template_positions_"]', function(event){
    //get the target HTML range input
    var target = $(event.target);

    //get the name of the position associated
    var position = target.parent().parent().attr('id');

    //change the name in the name list
    $("#"+position+"_namer").text(target.val());
})