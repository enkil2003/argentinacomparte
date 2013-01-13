$(function() {
    var actualDate = $('#date').val();
    $('#date').datepicker();
    $('#date').datepicker( "option", "dateFormat", "dd/mm/yy" );
    if (actualDate) {
        $('#date').datepicker('setDate', actualDate);
    } else {
        $('#date').datepicker('setDate', new Date());
    }
});