import $ from 'jquery';
import * as custom from '../layout/customButton';

/**
 * @param {HTMLElement} user The span tha trigger the droppable
 * @param {HTMLElement} drop the div that host the drop
 */
export function drop(user, drop) {

    var userIndex = user.data('userIndex');
    // 0 is for true, 1 for false (autogenerated by symfony)
    var value = drop.data('value');

    //look for the custom button and trigger a click
    $("#button-participation-" + value + "-" + userIndex).trigger('click');
}

/**
 * 
 * @param {HTMLElement} drop The div that host the drop
 */
export function changeBg(drop) {
    var color = drop.data('color');
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