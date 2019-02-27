$(function () {
    $("[id^=datepicker]").datetimepicker({
        format: 'L'
    });

    $("[id^=timepicker]").datetimepicker({
        format: 'LT'
    });
});