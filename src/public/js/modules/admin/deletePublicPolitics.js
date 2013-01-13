$(function() {
    var publicPoliticsTag = $('#publicPolitic');
    publicPoliticsTag.on({
        change: function() {
            var publicPoliticsVal = $(this).val();
            if (publicPoliticsVal != -1) {
                $('#deletePublicPoliticsSubmit').removeAttr('disabled');
            } else {
                $('#deletePublicPoliticsSubmit').attr('disabled', 'disabled');
            }
        }
    });
});