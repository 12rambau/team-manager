//css

//js
import $ from 'jquery';

import * as form from './blog/form';
import * as comment from './blog/comment';
import * as index from './blog/index'

$("#blog_post_short").keyup(function () {
    form.shortHelper(this);
});

$(".chevron-display").click(function (e) {
    index.displayDetails(e);
});

$("#comment_send").click(function (e) {
    comment.sendComment(e);
});






