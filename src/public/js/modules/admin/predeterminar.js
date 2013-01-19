$(function() {
    $('[title="predeterminar"]').bind(
        'click',
        function() {
            var self = $(this);
            $.post(
                '/admin/predeterminar/',
                {id: $(this).attr('data-id')},
                function(response) {
                    var title, messages, type;
                    $('[title="predeterminar"] i').removeClass('icon-star').addClass('icon-star-empty');
                    self.find('i').removeClass('icon-star-empty').addClass('icon-star');
                    if (response.result) {
                        title = 'Nueva portada';
                        messages = ['Se predeterminado una nueva portada'];
                        type = 'success';
                    } else {
                        title = 'Ha ocurrido un error';
                        messages = ['No se ha podido predeterminar una nueva portada'];
                        type = 'error';
                    }
                    $('#messageBoxHolder')
                        .flashMessenger({title: title, messages: messages, type: type})
                        .flashMessenger('fadeIn')
                        .flashMessenger('fadeOut', {wait: 2000})
                    ;
                },
                'json'
            );
        }
    );
});