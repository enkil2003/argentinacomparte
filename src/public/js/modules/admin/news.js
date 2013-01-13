var AFTER_UPLOAD_LOCATION = '/admin/listar-noticias';
$(function() {
    $('#preferentialCategory-label, #preferentialCategory-element').hide();
    
    // remove images button
    var imagesToDelete = new Array();
    $('#thumbImages .remove').click(function(){
        var filename = $(this).attr('data-filename');
        if ($.inArray(filename, imagesToDelete) == -1) {
            var hidden = $('<input type="hidden" name="imagesToDelete[]" />').val(filename);
            $('#newsFormTag input[type="submit"]').parent().append(hidden);
            imagesToDelete.push(filename);
            $(this).prev().fadeTo('slow', .2).addClass('toRemove');
        } else {
            var i;
            var _tempImagesToDelete = new Array();
            for (i in imagesToDelete) {
                if (filename != imagesToDelete[i]) {
                    _tempImagesToDelete.push(imagesToDelete[i]);
                }
            }
            $('#productForm input[value="'+filename+'"]').remove();
            imagesToDelete = _tempImagesToDelete;
            $(this).prev().fadeTo('slow', 1).removeClass('toRemove');
        }
        if (($('#productForm #thumbImages .remove').length - $('#productForm #thumbImages .toRemove').length) == 1) {
            $('#uploader_browse').hide();
        } else {
            $('#uploader_browse').show();
        }
    });
    
    if ($('#thumbImages .remove').length == 1) {
        $('#uploader_browse').hide();
    }
    // Submit logic
//    var submitting = false;
//    $('#newsFormTag').on({
//        submit: function(e) {
//            e.preventDefault();
//            if (ZendMax.Form.validate($('#newsFormTag'))) {
//                if (submitting == false) {
//                    submitting = true;
//                    submitForm();
//                }
//            }
//        }
//    });
//    
//    function submitForm() {
//        var newsId = $('#newsId').val() != undefined
//            ? $('#newsId').val()
//            : null;
//        
//        $.post(
//            "/admin/ajax/do/addOrEditNews",
//            {
//                id: newsId,
//                pp: $('#pp').val(),
//                title: $('#title').val(),
//                copy: $('#copy').val(),
//                body: $('#body').val(),
//                date: $('#date').val(),
//                youtube: $('#youtube').val(),
//                mintit: $('#mintit').val(),
//                active: $('#active').attr('checked') == 'checked'? 1:0
//            },
//            function (response) {
//                var uploader = $('#uploader').pluploadQueue();
//                
//                if (uploader.files.length > 0) {
//                    
//                    if (response.edit) {
//                        $.post(
//                            "/admin/ajax/do/removeImagesFromId",
//                            {id: newsId},
//                            function(response2) {
//                                if (response2.deleted) {
//                                    uploader.start();
//                                }
//                            },
//                            'json'
//                        )
//                    } else {
//                        uploader.start();
//                    }
//                } else {
//                    window.location = '/admin/listar-noticias';
//                }
//            },
//            'json'
//        );
//    }
});