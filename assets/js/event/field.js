import $ from 'jquery';
import 'bootstrap';

/**
 * 
 * @param {HTMLElement} item the a triggered to add a field
 */
export function addField(item) {

    // Get the ul that holds the collection of stat
    var group = $(item).data('group');
    var collectionHolder = $('#' + group);

    // get the new index
    var index = parseInt(collectionHolder.data('index'), 10);

    // Get the data-prototype explained earlier
    var prototype = collectionHolder.data('prototype');

    var newForm = prototype;

    // Replace '__name__' in the prototype's HTML
    newForm = newForm.replace(/__name__/g, index);

    var li = $('<li>', {'id': "li-field-"+index, 'class': 'list-group-item'});
    li.append(newForm);

    //add the image-div
    var img = $('<div>', {'id': "img-field-"+index, 'class': "positionning col-11 text-center w-100 mt-3"});
    li.append(img);

    // Display the form in the page
    collectionHolder.append(li);

    //add 1 to the starting index 
    collectionHolder.data('index', index + 1);

}

export function addListener() {
    //popover for the templatefield buttons
    $('[data-toggle="popover"]').popover();

    /*$(".template-select").change(function (event) {
        updateTemplate();
    })*/

    $('[data-toggle="popover"]').on('shown.bs.popover', function () {
        var index = $(this).data('index');
        index = parseInt(index,10);

        addDeleteTooltip(index);
        addChangeTooltip(index);
        addResetTooltip(index);
    });
}

/**
 * 
 * @param {integer} index the index of the Li
 */
function addDeleteTooltip(index) {
    //add the delete 

    $(".remove-template").click(function (event) {
        event.preventDefault();

        //empty all positions 

        //update position form

        removeLi(index);

        removeTemplate(index);
    });
}

/**
 * 
 * @param {integer} index the index of the Li
 */
function addChangeTooltip(index) {

    $(".change-template").click(function () {
        event.preventDefault(index);
        //empty all the position

        //update position form

        updateTemplate(index);
        
    })
}

/**
 * 
 * @param {integer} index the index of the Li
 */
function addResetTooltip(index) {

    $(".reset-template").click(function () {
        event.preventDefault();

        //empty all position 

        //update position form

    })

}

/**
 * 
 * @param {integer} index the index of the li to remove
 */
function removeLi(index) {
    $("#li-field-" + index).remove();

    //replace all the positioned personn on the bench
}

/**
 * 
 * @param {integer} index the number of the <li> to modify
 * @param {array} template the template data
 * @param {ghostUrl} index the url of the ghost position
 */
export function addVisuel(index, template, ghostUrl) {

    //get the image container
    var imageContainer = $("#img-field-"+index);

    //empty the container
    imageContainer.empty();

    //create the image from the template 
    var img = $('<img>', { class: 'w-100', src: template['image'] });

    imageContainer.append(img);

    //add each position with ghost and ablsolut positionning
    template['positions'].forEach(function (position, pIndex) {
        var ghost = $('<div>', { class: "card position-card positionned" });
        ghost.css('top', position['vertical']+"%");
        ghost.css('left', position['horizontal']+"%");
        ghost.css('background', 'url('+ghostUrl+')');

        imageContainer.append(ghost);
    });
}

/**
 * 
 * @param {integer} index line number
 */
export function updateTemplate(index) {

    var form = $("form[name='template_select']");
    var event_id = form.data('event-id');
    var select = $('select[name="template_select[fields]['+index+'][template]"]');
    var template_id = parseInt(select.val(),10);

    // get the serialized properties and values of the form 
    var form_data = form.serialize();

    // always makes sense to signal user that something is happening
    $('#loading-add').show();

    // the actual ajax request
    $.ajax({
        url: Routing.generate('template-update', { 'event_id': event_id, 'template_id':template_id }),
        type: 'POST',
        dataType: 'json',
        data: form_data,
        success: function (data) {
            
            addVisuel(index,data, ghostUrl);
            // signal to user the action is done
            $('#loading-add').hide();
        }
    });

    return template;
}

/**
 * 
 * @param {integer} index line number
 */
export function removeTemplate(index) {

    var form = $("form[name='template_select']");
    var event_id = form.data('event-id');
    var select = $('select[name="template_select[fields]['+index+'][template]"]');
    var template_id = parseInt(select.val(),10);

    // get the serialized properties and values of the form 
    var form_data = form.serialize();

    // always makes sense to signal user that something is happening
    $('#loading-add').show();

    // the actual ajax request
    $.ajax({
        url: Routing.generate('template-update', { 'event_id': event_id, 'template_id':template_id }),
        type: 'POST',
        dataType: 'json',
        data: form_data,
        success: function (data) {
            
            // signal to user the action is done
            $('#loading-add').hide();
        }
    });
}