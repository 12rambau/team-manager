$(document).on("click", "#comment_send", function (e) {
    
});

function sendComment(e){
    e.preventDefault();

    var form = $("form[name='comment']");
    var postId = $(e.target).data('id');

    // get the serialized properties and values of the form 
    var form_data = form.serialize();

    // always makes sense to signal user that something is happening
    $('#loading').show();

    // simple approach avoid submitting multiple times
    $('#comment_send').attr("disabled", true);

    // the actual ajax request
    $.ajax({
        url: Routing.generate('comment-add', {id: postId}),
        type: 'POST',
        dataType: 'json',
        data: form_data,
        success: function (data) {

            //update the list
            addComment(data);
            updateNbComment();

            // signal to user the action is done
            $('#loading').hide();
            $('#comment_send').attr("disabled", false);
            $('#comment_content').val('');
        }
    });
}

function addComment (comment){

    var date = $('<span>', {class: "far fa-clock", text: comment['publishDate']} );
    var small = $('<small>', {class:"float-right text-muted"}).append(date);
    var username = $('<strong>', {text: comment['username']});
    var header = $('<div>', {class: 'header'}).append(username, small);
    var content = $('<p>', { text: comment['content']} );
    var chat_body = $('<div>', {class: 'clearfix text-left'}).append(header, content);
    var img = $('<img>', {class: "rounded-circle", src: comment['profilePicUrl']} );
    var span = $('<span>', {class: 'float-left mr-3'}).append(img);
    var li =$('<li>', {class: "clearfix"}).append(span,chat_body);

    $('#comments-list').prepend(li); 

}

function updateNbComment(){

    var nbComment = parseInt($("#comment-section").text().replace(' comments',''),10)+1;

    $("#comment-section").text(nbComment+" comments");
    $("#nb-comment").text(nbComment+" comments");
}