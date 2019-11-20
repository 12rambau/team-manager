import $ from 'jquery'

export function changeValue(element) {

    element = $(element);

    var id = element.parent().data('id');
    var value = element.val();

    //guess if vertical or horizontal (1 for x and 0 for y) 
    var direction = element.data('direction');

    if (direction) {
        var init = $("#img-field").width();
        value = init * value / 100
        $("#cross-position-" + id).css({ left: value + "px" });
    } else {
        var init = $("#img-field").height();
        value = init * value / 100
        $("#cross-position-" + id).css({ bottom: value + "px" });
    }
}

function setCross(element, vertical, horizontal) {
    //get the cross
    var cross = $(element);

    //change the values
    var initH = $("#img-field").width();
    var initV = $("#img-field").height();

    horizontal = horizontal / 100 * initH;
    vertical = vertical / 100 * initV;

    cross.css({ bottom: vertical + "px", left: horizontal + "px" });
}

function createCross(spanId) {
    //create the dom object 
    var cross = $('<span>', { class: "fa fa-times text-light bold", id: "cross-position-" + spanId });

    var horizontal = $("#img-field").width();
    var vertical = $("#img-field").height();


    cross.css({ bottom: vertical + "px", left: horizontal + "px", position: 'absolute', zIndex: 4 })
    $("#img-field").append(cross);

    return cross;
}

export function initCross() {
    //search for all the positions
    var list = $("#positions-list");

    list.children().each(function () {
        var id = $(this).data('id');
        var cross = createCross(id);
        var xIndex = $(this).find(".range-horizontal").val();
        var yIndex = $(this).find(".range-vertical").val();

        setCross(cross, yIndex, xIndex);
    });
}

export function initText() {

    var listRange = $("#positions-list").find("input[type='range']");

    listRange.each(function () {
        updateText(this);
    })
}

export function updateRange(element) {
    $(element).prev().val($(element).val());
}

export function updateText(element) {
    $(element).next().val($(element).val());
}

export function removePosition(positionId) {
    $("#card-position-" + positionId).remove();
    $("#cross-position-" + positionId).remove();
}

export function addPosition() {

    //create the dom element in the tab
    var group = $('#positions-list'); // Get the div that holds the collection of fields
    var index = group.data('index') + 1; // get the new index
    var prototype = group.data('prototype'); // Get the data-prototype explained earlier

    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index); // Replace '__name__' in the prototype's HTML    
    group.append(newForm); // Display the form in the page
    initText();

    //create the cross
    var cross = createCross(index);
    setCross(cross, 50, 50); //as 50 is the default value for fieldtemplate

    //set the new index in div
    group.data('index', index);

}

export function bindPositions(){
    $('input[type="range"]').change(function (e) {
        updateText(this);
        changeValue(this);
    });
    
    $('.range-text').change(function () {
        updateRange(this);
        changeValue(this);
    });

    $('a[id^="position-delete-"]').click(function(){
        removePosition($(this).data('id'));
    })
}