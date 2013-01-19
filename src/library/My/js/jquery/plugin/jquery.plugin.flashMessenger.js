(function( $ ){
    var methods = {
            init: function(options) {
                methods.setMessages(options);
                return flashMessengerHtmlElement;
            },
            setMessages: function(options) {
                console.dir(options);
                flashMessengerHtmlElement.find('.alert-heading').text(options.title);
                flashMessengerHtmlElement.find('#messageBox')
                    .removeClass('alert-danger')
                    .removeClass('alert-error')
                    .removeClass('alert-success')
                    .addClass('alert-' + options.type)
                ;
                var errorList = flashMessengerHtmlElement.find('ul');
                errorList.empty();
                for(var i in options.messages) {
                    errorList.append($('<li />').text(options.messages[i]));
                }
                return flashMessengerHtmlElement;
            },
            show: function() {
                flashMessengerHtmlElement.show();
                return flashMessengerHtmlElement;
            },
            fadeIn: function(options) {
                if (options != undefined) {
                    if (options.wait != undefined) {
                    	flashMessengerHtmlElement.delay(options.wait);
                    }
                }
                flashMessengerHtmlElement.fadeIn();
                return flashMessengerHtmlElement;
            },
            fadeOut: function(options) {
                if (options.wait) {
                	flashMessengerHtmlElement.delay(options.wait);
                }
                flashMessengerHtmlElement.fadeOut();
                return flashMessengerHtmlElement;
            }
        };
    var flashMessengerHtmlElement = null;
    $.fn.flashMessenger = function(method, options) {
        return this.each(function() {
            flashMessengerHtmlElement = $(this);
            if (typeof method != 'undefined') {
                if ( methods[method] ) {
                    return methods[ method ].apply( this, [options]);
                } else if (typeof method === 'object') {
                	return methods.init.apply( this, [method]);
                }
            }
        });
    };
})( jQuery );