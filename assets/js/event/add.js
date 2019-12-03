import $ from 'jquery';

function createTag(tag) {

    //TODO ger the margin for the span 
    
    //build unchecked 
    var span_unchecked = $('<span>', { class: 'far fa-square mr-1' });
    span_unchecked.append(tag['name']);
    var button_unchecked = $(
        '<button>',
        {
            type: 'button',
            class: 'uncheck btn btn-outline-' + tag['color'] + ' m-1'
        }
    )
    button_unchecked.append(span_unchecked);

    //build checked 
    var span_checked = $('<span>', { class: 'far fa-check-square mr-1', text: tag['name'] });
    var button_checked = $(
        '<button>',
        {
            type: 'button',
            class: 'check btn btn-' + tag['color'] + ' m-1'
        }
    )
    button_checked.append(span_checked);

    //build input 
    var input = $('<input>', {
        type: 'radio',
        id: 'event_tag_' + tag['id'],
        name: 'tag',
        required: true,
        value: tag['id']
    });


    //display
    var label = $('<label>', { class: 'custom-radio' });
    label.append(input).append(button_unchecked).append(button_checked);
    $("#tags-list").append(label);
}

function emptyList() {
    $("#tags-list").empty();
}

export function updateTags(teamId) {

    // always makes sense to signal user that something is happening
    $('#loading-tags').show();

    //the actual ajax request
    $.ajax({
        url: Routing.generate('tags-update', { 'id': teamId }),
        success: function (tags) {

            emptyList();

            tags.forEach(createTag);

            // signal to user the action is done
            $('#loading-tags').hide();
        }
    });

}