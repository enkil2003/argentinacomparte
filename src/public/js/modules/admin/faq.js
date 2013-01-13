$(function() {

    $('#copy').limit('255','#charsLeft');
    
    $('#body').tinymce({
    	entity_encoding : "raw",
        theme : "advanced",
        theme_advanced_buttons1 : "bold,italic,underline,|,undo,redo,|,cleanup,|,bullist,numlist,|,link,code",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top"
    }).css({'width': '100%','height': '250px'});
    
  
    // Prevents 'enter' to submits the form 
    $('input').on({
        keypress: function(e) {
            // ENTER PRESSED
            if (e.keyCode == 13) {
                // FOCUS ELEMENT 
                var inputs = $(this).parents("form").eq(0).find(":input:visible");
                var idx = inputs.index(this);
                if (idx == inputs.length - 1) {
                    inputs[0].select()
                } else {
                    inputs[idx + 1].focus(); //  handles submit buttons
                    inputs[idx + 1].select();
                }
                return false;
            }
        }
    });
    
    $('#date').datepicker();
    $('#date').datepicker('setDate', new Date());
    
    
});