import $ from 'jquery';

export function checkCustomRadio(element){
    //unchecked all the other custom radio from this form
    var buttons = $(element).parent().children();

    buttons.each(function(){
         var color = $(this).data('color');
        if (!$(this).hasClass('btn-outline-'+color)) $(this).addClass('btn-outline-'+color);
        if ($(this).hasClass('btn-'+color)) $(this).removeClass('btn-'+color);
        if ($(this).hasClass('validated')) $(this).removeClass('validated');
    })

    //change the real radio
    var radio = $("#"+$(element).data('radioId'));
    radio.prop("checked", true).trigger("click");

    var color = $(element).data('color');
    $(element).addClass('btn-'+color);
    $(element).removeClass('btn-outline-'+color);
    $(element).addClass('validated');
}