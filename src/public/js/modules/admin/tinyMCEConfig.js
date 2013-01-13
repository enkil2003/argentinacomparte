$(function() {
    $('#body').tinymce({
        entity_encoding : "raw",
        theme : "advanced",
        theme_advanced_buttons1 : "bold,italic,underline,|,undo,redo,|,cleanup,|,bullist,numlist,|,link,code",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top"
    }).css({'width': '100%','height': '250px'});
});