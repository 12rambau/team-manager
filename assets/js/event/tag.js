import $ from 'jquery';

//function to change the radio button according to nicer link designed with bootstrap
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