var Pluploader = Pluploader || {};
$(function() {
    //traduccion para el plupload
    plupload.addI18n({
        'Select files' : 'Elija archivos:',
        'Add files to the upload queue and click the start button.' : 'Agregue archivos a la cola de subida y haga click en el boton de iniciar.',
        'Filename' : 'Nombre de archivo',
        'Status' : 'Estado',
        'Size' : 'Tama&ntilde;o',
        'Add files' : 'Agregue archivo',
        'Stop current upload' : 'Detener subida actual',
        'Start uploading queue' : 'Iniciar subida de cola',
        'Uploaded %d/%d files': 'Subidos %d/%d archivos',
        'N/A' : 'No disponible',
        'Drag files here.' : 'Arrastre archivos aqu&iacute;',
        'File extension error.': 'Error de extensi&oacute;n de archivo.',
        'File size error.': 'Error de tama&ntilde;o de archivo.',
        'Init error.': 'Error de inicializaci&oacute;n.',
        'HTTP Error.': 'Error de HTTP.',
        'Security error.': 'Error de seguridad.',
        'Generic error.': 'Error gen&eacute;rico.',
        'IO error.': 'Error de entrada/salida.',
        'Stop Upload': 'Detener Subida.',
        'Add Files': 'Agregar Archivos',
        'Start Upload': 'Comenzar Subida.',
        '%d files queued': '%d archivos en cola.'
    });
    
    $("#uploader").pluploadQueue({
        runtimes : 'gears,html5,browserplus,flash,silverlight',
        url : '/admin/upload-politicas-publicas-images/folder/' + Pluploader.folder,
        max_file_count: 1,
        max_file_size : '10mb',
        chunk_size : '1mb',
        unique_names : false,
        rename: false,
        sortable: true,
        filters : [
            {title : "Image files", extensions : "jpg,gif,png"}
        ],
        flash_swf_url : '/js/jquery/plupload/js/plupload.flash.swf',
        silverlight_xap_url : '/js/jquery/plupload/js/plupload.silverlight.xap',
        preinit : {
            init : function() {
                $('#images .plupload_button.plupload_start').remove();
            },
            UploadComplete: function(up, files) {
                $.get(
                    '/admin/ajax/do/bindImages',
                    {},
                    function (response) {
                        if (response.binded === true) {
                            window.location = '/admin/geolocalizar/type/publicPolitic/id/' + Pluploader.folder;
                        }
                    },
                    'json'
                );
            }
        }
    });
    var _deleteImages = function(images) {
        for(var i in images) {
            var image = images[i];
            var name = $(image).val();
            var id = $(image).attr('data-news-id');
            $.post(
                '/admin/delete-image',
                {
                    id: id,
                    name: name
                },
                function(response) {
                    if (response.result === true) {
                        console.dir(response.result);
                    }
                }
            );
        }
    }
    $('input[name="publicPoliticsSubmit"]').bind(
        'click',
        function(e) {
            var uploader = $('#uploader').pluploadQueue(),
                imagesToDelete = $('.imagesToDelete [type="hidden"]').length,
                totalImages = $('.imagesToDelete img').length
            ;
            e.preventDefault();
            if (imagesToDelete > 0) {
                if (uploader.files.length > 0 || imagesToDelete < totalImages) {
//                    _deleteImages($('[data-imagestodelete="imagesToDelete"]'));
                } else {
//                    alert('no puedo eliminar todo');
                }
            }
            if (uploader.files.length > 0) {
                uploader.start();
            } else {
                $('#uploader-element').closest('.control-group').addClass('error');
                if ($('#uploader-element').find('ul').length == 1) {
                    $('#uploader-element').append('<ul style="margin-top: 10px" class="errors help-inline label label-important"><li>Debe indicar al menos 1 imagen</li></ul>');
                }
                $.scrollTo($($('.control-group .errors')[0]).parent(), 500, {offset: {top: -70}});
            }
            return false;
        }
    );
    // ******************************************
    // there is no native method to do this below
    // ******************************************
    $('.plupload_button.plupload_start').remove();
    
    $('.imagesToDelete a').on({
        click: function() {
            var id = $(this).attr('data-news-id');
            var name = $(this).attr('data-name');
            if ($('.imagesToDelete').find('input[value="' + name + '"]').length == 0) {
                $('.imagesToDelete').append('<input data-news-id="' + id + '" data-imagesToDelete="imagesToDelete" type="hidden" value="' + name + '" />');
                $(this).parent().find('img').css('opacity', '.5');
            } else {
                $('.imagesToDelete').find('input[value="' + name + '"]').remove();
                $(this).parent().find('img').css('opacity', '1');
            }
        }
    });
});
