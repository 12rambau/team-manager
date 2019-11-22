import $ from 'jquery';

function moveBadge(index, username, value) {
    // check if the badge exist, if not create a new one
    var badge;
    if (!$("#badge-" + username + "-" + index).length) {
        badge = $('<span>', { 'class': 'badge badge-secondary', 'id': 'badge-' + username + "-" + index, 'text': username });
    } else {
        badge = $("#badge-" + username + "-" + index);
    }

    //move to the appropriate value (0 = out; 1=in)
    $("#tab-" + value + "-" + index).append(badge);

    //manage tabs 
    var tabIn = $("#tab-in-" + index);
    var ok = parseInt(tabIn.text(), 10);

    var tabOut = $("#tab-out-" + index);
    var out = parseInt(tabOut.text(), 10);

    if (value) {
        ok++;
        out = (out <= 1) ? 0 : out - 1;
    } else {
        ok = (ok <= 1) ? 0 : ok - 1;
        out++;
    }


    //update numbers in tabs
    tabIn.text(ok);
    tabOut.text(out);

}

function updateParticipant(index, value, username) {

    // set  +value on the number of participant (value should be true or false)
    var nbPlayers = parseInt($("#nbPlayers-" + index).text(), 10);
    if (value) {
        $("#nbPlayers-" + index).text(nbPlayers + 1);
    } else {
        if ($("#badge-" + username + "-" + index).length)
            $("#nbPlayers-" + index).text(nbPlayers - 1);
    }

    //check the maximum and change the bar appropriately 
    nbPlayers = parseInt($("#nbPlayers-" + index).text(), 10);
    var maxPlayers = parseInt($("#maxPlayers-" + index).text(), 10);

    var players100 = 0;
    var outbond100 = 0;
    var valid100 = 0;

    if (nbPlayers >= maxPlayers) {
        valid100 = maxPlayers * 100 / nbPlayers;
        outbond100 = (nbPlayers - maxPlayers) * 100 / nbPlayers;
    } else {
        players100 = nbPlayers * 100 / maxPlayers;
    }

    $("#progress-players-" + index).css('width', Math.floor(players100) + "%");
    $("#progress-outbonds-" + index).css('width', Math.floor(outbond100) + "%");
    $("#progress-valid-" + index).css('width', Math.floor(valid100) + "%");
}

function updateParticipation(element) {

    var index = $(element).data('index');
    var username = $(element).data('username');
    var value = $(element).data('value')

    updateParticipant(index, value, username);
    moveBadge(index, username, value);

}

export function updateIndex(element) {

    var index = $(element).data('index');

    var form = $("form");
    var page = form.data("page");

    // get the serialized properties and values of the form 
    var form_data = form.serialize();

    // always makes sense to signal user that something is happening
    $('#loading-' + index).show();

    // the actual ajax request
    $.ajax({
        url: Routing.generate('index-update', { 'page': page }),
        type: 'POST',
        dataType: 'json',
        data: form_data,
        success: function (data) {

            if (data['status']) {
                //update the list
                updateParticipation(element);

                // signal to user the action is done
                $('#loading-' + index).hide();
            }
            console.log("status ="+data['status']); //TODO for debug purpose
        }
    });
}
