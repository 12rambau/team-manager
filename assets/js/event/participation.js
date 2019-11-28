import $ from 'jquery';

/**
 * @param {Object} ui The draggable associated object
 * @param {HTMLElement} drop the div that host the drop
 */
export function drop(ui, drop) {

    var x = ui.helper.clone();
    x.removeClass("ui-draggable-dragging");
    x.attr('style', '');
    x.css({ 'position': 'relative' });

    var participationId = $(x).data('participationId');
    var fieldId = $(drop).data('fieldId');
    var positionId = $(drop).data('positionId');

    alert("particpationId : "+participationId+" Field : "+fieldId+" Position : "+positionId);

    //append the element in the drop
    $(drop).append(x);
    ui.helper.remove();

    //forbid anny other to be dropped in 
    $(drop).droppable("option", "disabled", true);

    //make it draggable again 
    x.draggable();

    //change the form
    $("#event_fields_participations_"+participationId+"_position").val(positionId);
    $("#event_fields_participations_"+participationId+"_field").val(fieldId);
}

export function updatePositions() {

    var form = $("form[name='event_fields']");
    var event_id = form.data('event-id');

    // get the serialized properties and values of the form 
    var form_data = form.serialize();

    // always makes sense to signal user that something is happening
    $('#loading-position').show();

    // the actual ajax request
    $.ajax({
        url: Routing.generate('positions-update', { 'id': event_id }),
        type: 'POST',
        dataType: 'json',
        data: form_data,
        success: function (data) {
            
            // signal to user the action is done
            $('#loading-position').hide();
        }
    });
}

export function checkDroppability(drop){
    /* TODO don't allow to redrop on an already done position
    
    drop = $(drop);
    var droppable =true
    if (drop.attr('id') != "dropback" && drop.children().length != 0){
        alert("j'ai bloqué le drop sur "+drop.attr('id'));
        droppable = false;
    }

    drop.droppable("option", "disabled", droppable)
    */
}