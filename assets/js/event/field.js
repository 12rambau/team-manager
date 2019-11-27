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

    // Display the form in the page
    collectionHolder.append(newForm);

    //add 1 to the starting index 
    collectionHolder.data('index', index + 1);

}

export function addListener() {
    //popover for the templatefield buttons
    $('[data-toggle="popover"]').popover({
        html: true

    });

    $(".template-select").change(function (event) {
        updateTemplate();
    })

    $('[data-toggle="popover"]').on('shown.bs.popover', function () {
        $(".remove-template").click(function (event) {
            event.preventDefault();
            // TODO make it work 
            //var index = $(this).data('index');
            var index = $(this).attr('class').replace('remove-template ','');

            $("#li-field-"+index).remove();
            //replace all the positioned personn on the bench
            updateTemplate();
        });
    });
}

/**
 * 
 * @param {integer} index the number of the <li> to modify
 */
export function addVisuel(index, template, ghostUrl) {

    //create the image from the template 
    var img = $('<img>', { class: 'w-100', src: template['image'] });
    var imgContainer = $('<div>', { class: "col-12 w-100", id: 'img-field-' + index });
    imageContainer.append(igm);

    //add each position with ghost and ablsolut positionning
    template['positions'].forEach(function (position, pIndex) {
        var ghost = $('<div>', { class: "card position-card positioned drop-card", id: "field_" + index + "_position_" + pIndex });
        ghost.css('top', position['vertical']);
        ghost.css('left', position['horizontal']);
        ghost.css('background', ghostUrl);

        imageContainer.append(ghost);
    });

    //insert into the <li>
    $("#li-field-" + index).append(imageContainer);
}


export function updateTemplate() {

    var form = $("form[name='template_select']");
    var slug = form.data('slug');

    // get the serialized properties and values of the form 
    var form_data = form.serialize();

    // always makes sense to signal user that something is happening
    $('#loading-add').show();

    // the actual ajax request
    $.ajax({
        url: Routing.generate('template-update', { 'slug': slug }),
        type: 'POST',
        dataType: 'json',
        data: form_data,
        success: function (data) {

            // signal to user the action is done
            $('#loading-add').hide();
        }
    });
}