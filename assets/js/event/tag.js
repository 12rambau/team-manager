import $ from 'jquery';

//function to change the radio button according to nicer link designed with bootstrap

/**
 * 
 * @param {HTMLElement} item the element button that has been checked
 */
export function changeCheckValue(item) { // unselect all the other item
    $(item).parent().find("i").attr('class', 'far fa-square');
    var divs = $(item).parent().find("div");
    divs.each(function () {
    $(this).attr('class', 'btn btn-outline-' + $(this).attr('data-color'));
    });
    $(item).find('i').attr('class', 'far fa-check-square');
    $(item).attr('class', 'btn btn-' + $(item).attr('data-color'))
    var input = document.getElementById(item.id.replace("select_", ""));
    input.checked = true;
    }


//TODO reduce code length using jquery

/**
 * 
 * @param {HTMLElement} element the check-all  DOM element
 */
export function checkAll(element) {

    var nbButton = $("#tag-list").data('tags');

    var checkedAll = $(element).hasClass('btn-outline-white');

    //check all buttons
    for (let i = 0; i < nbButton; i++) {
        var button = $("#tag-button-" + i);
        var check = $("#tag-check-" + i)
        var color = button.data('color');

        //fill or empty the button
        var wrongClass = (checkedAll) ? 'btn-outline-' + color : 'btn-' + color;
        if (button.hasClass(wrongClass)) {
            button.toggleClass('btn-outline-' + color);
            button.toggleClass('btn-' + color);
            check.toggleClass('fa-square');
            check.toggleClass('fa-check-square');
        }
    }

    //change the final button
    testAllCheck();
}

/**
 * 
 * @param {HTMLElement} element the checked button
 */
export function checkButton(element) {
   
    var color = $(element).data('color');

    $(element).toggleClass('btn-outline-' + color);
    $(element).toggleClass('btn-' + color);
    $(element).find("span").toggleClass('fa-square');
    $(element).find("span").toggleClass('fa-check-square');

    testAllCheck();

}

function testAllCheck() {

    var nbButton = $("#tag-list").data('tags');
    var checkAllButton = $("#check-all-tag");

    var check = true;

    for (let i = 0; i < nbButton; i++) {
        //check if button are all checked 
        var button = $("#tag-button-" + i);
        var color = button.data('color');

        if (button.hasClass('btn-outline-' + color)) {
            check = false;
            break;
        }
    }

    //fill or empty the button
    var wrongClass = (check) ? 'btn-outline-white' : 'btn-white';
    if (checkAllButton.hasClass(wrongClass)) {
        checkAllButton.toggleClass('btn-outline-white');
        checkAllButton.toggleClass('btn-white');
        checkAllButton.find("span").toggleClass('fa-square');
        checkAllButton.find("span").toggleClass('fa-check-square');
    }
}