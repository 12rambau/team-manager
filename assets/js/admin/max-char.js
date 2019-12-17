import $ from 'jQuery';

export function updateMax(textarea) {

    //get the number of character 
    var nbChar = $(textarea).val().length;

    //change the help accordingly
    var help = $(textarea).next().children("#max-char");

    help.text(nbChar);

    testMaxReached(textarea);
}

function testMaxReached(textarea) {
    //get the helper
    var helper = $(textarea).next();

    //get the max character
    var max = parseInt(helper.children("#max").text(), 10);

    var nbChar = $(textarea).val().length;

    var toRemove = (nbChar >= max) ? 'text-muted' : 'text-danger';
    var toAdd = (nbChar >= max) ? 'text-danger' : 'text-muted';

    helper.removeClass(toRemove);
    helper.addClass(toAdd);
}