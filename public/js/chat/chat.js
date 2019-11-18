function scrollToBottom() {
    messages = $("#chatwindow");
    messages.scrollTop(messages[0].scrollHeight);
}

$(document).on("click", "#chevron", function (e) {
    e.target.classList.toggle('fa-chevron-up');
    e.target.classList.toggle('fa-chevron-down');
    scrollToBottom();
});

$(document).on("click", "#chat-submit", function (e) {
    e.preventDefault();

    var form = $("form[name='chat_message']");

    // get the serialized properties and values of the form 
    var form_data = form.serialize();

    // always makes sense to signal user that something is happening
    $('#loading-down').show();
    scrollToBottom();

    // simple approach avoid submitting multiple times
    $('#chat-submit').attr("disabled", true);

    // the actual ajax request
    $.ajax({
        url: Routing.generate('chat-add'),
        type: 'POST',
        dataType: 'json',
        data: form_data,
        success: function (data) {

            //actualise the list
            addMessage(data, true);
            scrollToBottom();

            // signal to user the action is done
            $('#loading-down').hide();
            $('#chat-submit').attr("disabled", false);
            $('#chat_message_content').val('');
        }
    });
});

function addMessage (message, me=false){

    //definition of the sides 
    var s0 = me ? "left" : "right";
    var s1 = me ? "right" : "left";

    var date = $('<span>', {class: "far fa-clock", text: message['date']} );
    var small = $('<small>', {class:"float-"+s0+" text-muted"}).append(date);
    var username = $('<strong>', {text: message['username']});
    var header = $('<div>', {class: 'header'}).append(username, small);
    var content = $('<p>', { text: message['content']} );
    var chat_body = $('<div>', {class: 'chat-body clearfix text-'+s1}).append(header, content);
    var img = $('<img>', {class: "rounded-circle", src: message['profilePicUrl']} );
    var span = $('<span>', {class: 'float-'+s0}).append(img);
    var li =$('<li>', {class: s1+" clearfix"}).append(span,chat_body);

    $('#chat-messages').append(li);

}

