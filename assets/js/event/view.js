import $ from 'jquery';

/**
 * @param {Object} ui The draggable associated object
 * @param {HTMLElement} drop the div that host the drop
 */
export function drop(ui, drop) {

    var x = ui.helper.clone();
    x.removeClass("ui-draggable-dragging");
    x.attr('style', '');
    x.css({'position':'relative'});

    var userIndex = $(x).data('userIndex');
    // 0 is for true, 1 for false (autogenerated by symfony)
    var value = $(drop).data('value');

    //append the element in the drop
    $(drop).append(x);
    ui.helper.remove();

    //make it draggable again 
    x.draggable();

    //look for the custom button and trigger a click
    $("#button-participation-" + value + "-" + userIndex).trigger('click');
    alert($("#button-participation-" + value + "-" + userIndex).data('color'));
}

/**
 * 
 * @param {HTMLElement} drop The div that host the drop
 */
export function changeBg(drop) {
    var color = $(drop).data('color');
    $(drop).toggleClass('bg-outline-' + color);
    $(drop).toggleClass('bg-' + color);
}

/**
 * 
 * @param {HTMLElement} radio the button to select between time and values
 */
export function changestat(radio) {

    var index = $(radio).data('index');
    var value = $('#value-selector-'+index);
    var time = $('#time-selector-'+index);

    //change the value visibility 
    value.toggleClass('sr-only');
    value.toggleClass('form-group');

    //change the time visibility 
    time.toggleClass('sr-only');
    time.toggleClass('form-group');
}

/**
 * 
 * @param {HTMLElement} item the a triggered to add a stat
 */
export function addStat(item) {

    // Get the ul that holds the collection of stat
    var group = $(item).data('group');
    var collectionHolder = $('#' + group);

    // get the new index
    var index = parseInt(collectionHolder.data('index'),10);

    // Get the data-prototype explained earlier
    var prototype = collectionHolder.data('prototype');

    var newForm = prototype;

    // Replace '__name__' in the prototype's HTML
    newForm = newForm.replace(/__name__/g, index);

    // Display the form in the page
    collectionHolder.append(newForm);

    //add 1 to the starting index 
    collectionHolder.data('index', index+1);

}

/**
 * 
 * @param {HTMLElement} item the button from the row to delete
 */
export function removeStat(item) {
    // remove the tr
    $(item).parent().parent().remove();
}

export function addListener(){
    $('.delete-stat').click(function(){  
        removeStat(this);  
    });

    $('.time-value').change(function(){
        changestat(this);
    })
}