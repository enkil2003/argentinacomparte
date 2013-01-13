var AFTER_UPLOAD_LOCATION = '/admin/listar-politicas-publicas';
$(function() {
    $('#preferentialCategory-label, #preferentialCategory-element').hide();
    
    /**
     * Esta funcion me permite determinar cual es la categoria de preferencia en caso de que haya
     * muchas categorias seleccionadas
     * @ return void
     */
    function populatePreferentialCategory() {
        $('#preferentialCategory-label, #preferentialCategory-element').show();
        // junto las categorias
        var categories = [];
        $('#category').children().each(function() {
            if ($(this).attr('selected') === 'selected') {
                categories.push({value: $(this).attr('value'), text: $(this).text()});
            }
        });
        // me fijo que categoria esta seleccionada en este momento
        var previousElements = [];
        var previousSelectedElement = null;
        $('#preferentialCategory').children().each(function() {
            if ($(this).attr('selected') === 'selected') {
                previousSelectedElement = $(this).attr('value');
            }
        });
        var categoryExists = false;
        // si el elemento previamente seleccionado no esta en las categorias actuales, marcamos el primero
        for (var i in categories) {
            if (categories[i].value == previousSelectedElement) {
                categoryExists = true;
            }
        }
        // agrego las categorias
        $('#preferentialCategory').empty();
        for (var i = 0, count = categories.length; i < count; i++) {
            var selected = '';
            if (categories[i].value == previousSelectedElement) {
                selected = ' selected="selected"';
            }
            $('#preferentialCategory').append('<option value="' + categories[i].value + '"' + selected + '>' + categories[i].text + '</option>');
        }
        if ($('#preferentialCategory').children().length === 1 || !categoryExists) {
            $($('#preferentialCategory option')[0]).attr('selected', 'selected');
        }
    }
    
    // si estoy editando precargo la categoria preferencial
    if ($('#category').val() != null) {
        populatePreferentialCategory();
    }
    
    $('#category').on({
        change: function() {
            var that = $(this);
            if (that.val() != '0') {
                populatePreferentialCategory();
            } else {
                $('#preferentialCategory-label, #preferentialCategory-element').hide();
            }
        }
    });
    
    // Submit logic
    var submitting = false;
    $('#publicPoliticsFormTag').bind(
        'submit.addPublicPolitics',
        function(e) {
            e.preventDefault();
            if (submitting == false) {
                submitting = true;
            }
        }
    );
});
var submitPublicPolitics = function() {
    var publicPoliticId = $('#publicPoliticId').val() != undefined
        ? $('#publicPoliticId').val()
        : null
    ;
    
    $.post(
        "/admin/ajax/do/addOrEditPublicPolitics",
        {
            id: publicPoliticId,
            category: $('#category').val(),
            preferentialCategory: $('#preferentialCategory').val(),
            title: $('#title').val(),
            copy: $('#copy').val(),
            body: $('#body').val(),
            date: $('#date').val(),
            youtube: $('#youtube').val(),
            active: $('#active').attr('checked') == 'checked'? 1:0
        },
        // si tengo id y tengo imagenes, y tengo un nuevo length
        // borro las imagenes que estan ahora, y subo estas nuevas
        function (response) {
            var uploader = $('#uploader').pluploadQueue();
            
            if (uploader.files.length > 0) {
                // si estoy editando y tengo imagenes, borro todas las que ya tenia esta nota antes de agregar
                // las nuevas
                if (response.edit) {
                    $.post(
                        "/admin/ajax/do/removeImagesFromId",
                        {id: response.politicaPublicaId},
                        function(response2) {
                            if (response2.deleted) {
                                uploader.start();
                            }
                        },
                        'json'
                    )
                } else {
                    uploader.start();
                }
            } else {
                window.location = '/admin/listar-politicas-publicas';
            }
        },
        'json'
    );
}