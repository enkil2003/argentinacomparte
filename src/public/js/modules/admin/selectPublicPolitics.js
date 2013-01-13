$(function() {
    var publicPoliticsTag = $('#publicPolitic');
    $('#category').on({
        change: function() {
            var $this = $(this);
            var val = $this.val();
            if (val != -1) {
                $.get(
                    '/admin/ajax/do/getPublicPolitics',
                    {id: val},
                    function(response) {
                        publicPoliticsTag.empty().append('<option value="-1">Seleccione una política pública</option>');
                        if (response.content.length != 0 ) {
                            for(var i in response.content) {
                                var publicPolitics = response.content[i];
                                var title = publicPolitics.title;
                                var id = publicPolitics.id;
                                publicPoliticsTag.append('<option value="' + id + '">' + title + '</option>');
                            }
                            publicPoliticsTag.removeAttr('disabled');
                        } else {
                            publicPoliticsTag.attr('disabled', 'disabled');
                        }
                    },
                    'json'
                );
            }
        }
    });
});