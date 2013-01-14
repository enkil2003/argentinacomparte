var AFTER_UPLOAD_LOCATION = '/admin/agregar-politica-publica';
$(function() {
    var hasSelectedFiles = false;
    var CHOOSE_AN_IMAGE = 'Debe seleccionar una imagen';
    var IMAGE_ADD_STATUS_MESSAGE = 'se han añadido imagenes para el producto';
    var UPLOAD_SUCCESS_MESSAGE = 'archivos han sido subidos con exito';
    
    $('#body').tinymce({
        entity_encoding : "raw",
        theme : "advanced",
        theme_advanced_buttons1 : "bold,italic,underline,|,undo,redo,|,cleanup,|,bullist,numlist,|,link,code",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_toolbar_location : "top"
    }).css({'width': '100%','height': '250px'});
    
    $('#preferentialCategory-label, #preferentialCategory-element').hide();
    
    $('#category').on({
        change: function() {
            var that = $(this);
            if (that.val() != '0') {
                $('#preferentialCategory-label, #preferentialCategory-element').show();
                // junto las categorias
                var categories = [];
                that.children().each(function() {
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
            } else {
                $('#preferentialCategory-label, #preferentialCategory-element').hide();
            }
        }
    });
    
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
    
    // remove images button
    var imagesToDelete = [];
    $('#publicPoliticsFormTag #thumbImages .remove').click(function(){
        var filename = $(this).attr('data-filename');
        if ($.inArray(filename, imagesToDelete) == -1) {
            var hidden = $('<input type="hidden" name="imagesToDelete[]" />').val(filename);
            $('#publicPoliticsFormTag input[type="submit"]').parent().append(hidden);
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
            $('#publicPoliticsFormTag input[value="'+filename+'"]').remove();
            imagesToDelete = _tempImagesToDelete;
            $(this).prev().fadeTo('slow', 1).removeClass('toRemove');
        }
        if (($(' #thumbImages .remove').length - $('#thumbImages .toRemove').length) == 4) {
            $('#uploader_browse').hide();
        } else {
            $('#uploader_browse').show();
        }
    });
    
    if ($('#thumbImages .remove').length == 4) {
        $('#uploader_browse').hide();
    }
    // Submit logic
    var submitting = false;
    $('#publicPoliticsFormTag').on({
        submit: function(e) {
            e.preventDefault();
            if (submitting == false) {
                submitting = true;
                submitForm();
            }
        }
    });
    
    function submitForm() {
        var publicPoliticId = $('#id').val() != undefined
            ? $('#id').val()
            : null;
        
        $.post(
            "/admin/ajax/do/agregarPoliticaPublica",
            {
                id: publicPoliticId,
                category: $('#category').val(),
                preferentialCategory: $('#preferentialCategory').val(),
                title: $('#title').val(),
                copy: $('#copy').val(),
                body: $('#body').val(),
                date: $('#date').val(),
                youtube: $('#youtube').val()
            },
            function (response) {
                var uploader = $('#uploader').pluploadQueue();
                
                if (uploader.files.length > 0) {
                    uploader.start();
                } else {
                	window.location = '/admin/agregar-politica-publica';
                }
            },
            'json'
        );
    }
});