import $ from 'jquery';

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

export function addListener(){
    //popover for the templatefield buttons
    $('[data-toggle="popover"]').popover({
        html: true
    }); 
}

/**
 * 
 * @param {integer} index the number of the <li> to modify
 */
export function addVisuel(index, template, ghostUrl){

    //create the image from the template 
    var img = $('<img>', {class:'w-100', src: template['image']});
    var imgContainer = $('<div>', {class: "col-12 w-100", id: 'img-field-'+index});
    imageContainer.append(igm);

    //add each position with ghost and ablsolut positionning
    template['positions'].forEach(function(position, pIndex){
        var ghost = $('<div>', {class:"card position-card positioned drop-card", id:"field_"+index+"_position_"+pIndex});
        ghost.css('position', 'relative');
        ghost.css('top', position['vertical']);
        ghost.css('left', position['horizontal']);
        ghost.css('background', ghostUrl);

        imageContainer.append(ghost);
    });

    //insert into the <li>
    $("#li-field-"+index).append(imageContainer);
}