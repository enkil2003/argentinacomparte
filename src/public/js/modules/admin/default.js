$(function() {
    $('.dropdown a').bind(
        'click.closeOpenedDropdowns',
        function() {
            $('.open').removeClass('open');
        }
    );
    $('.dropdown-toggle').dropdown();
});