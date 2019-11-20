import $ from 'jquery';

export function shortHelper(element){

    //count the number of carracter in the input 
    var nbChar = element.value.length;
    var max = $(element).data('max');
    
    //change the helper value
    $('#blog_post_short_help').text(nbChar+"/"+max);
        
}