var Pluploader = Pluploader || {};
$(function() {
    var redirectToGeolocate = function() {
        $('#publicPoliticsSubmit').attr('disabled', 'disabled');
        $('#geoloc').attr('disabled', 'disabled');
        // politicas-publicas-step-three
        window.location = '/admin/' + Pluploader.action + '/' + Pluploader.folder;
    };
    
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
                            $.get(
                                '/admin/set-flash-message',
                                {type: 'success', message: 'Imagenes agregadas con éxito', title: 'Noticias'},
                                function(response) {
                                    _setFlashMessages(
                                        'Noticias',
                                        'Imagenes agregadas con éxito',
                                        'success',
                                        function() {
                                            redirectToGeolocate();
                                        }
                                    );
                                },
                                'json'
                            );
                        }
                    },
                    'json'
                );
            }
        }
    });
    var _setFlashMessages = function(title, message, type, callback) {
        $.get(
            '/admin/set-flash-message',
            {type: type, message: message, title: title},
            callback,
            'json'
        );
    }
    var _deleteImages = function(images) {
        $('[data-imagestodelete="imagesToDelete"]').each(function() {
            var name = $(this).val();
            var id = $(this).attr('data-news-id');
            $.post(
                '/admin/delete-image',
                {
                    id: id,
                    name: name
                }
            );
        });
    };
    
    var raiseError = function() {
        $('#uploader-element').closest('.control-group').addClass('error');
        if ($('#uploader-element').find('ul').length == 1) {
            $('#uploader-element').append('<ul style="margin-top: 10px" class="errors help-inline label label-important"><li>Debe indicar al menos 1 imagen</li></ul>');
        }
        $.scrollTo($($('.control-group .errors')[0]).parent(), 500, {offset: {top: -70}});
    };
    
    $('input[name="publicPoliticsSubmit"]').bind(
        'click',
        function(e) {
            var uploader = $('#uploader').pluploadQueue(),
                imagesToDelete = $('.imagesToDelete [type="hidden"]').length,
                totalImages = $('.imagesToDelete img').length
            ;
            e.preventDefault();
            // handle delete
            if (imagesToDelete > 0) {
                var canRedirect = false;
                if (uploader.files.length > 0 || totalImages > imagesToDelete ) {
                    canRedirect = true;
                    _deleteImages($('[data-imagestodelete="imagesToDelete"]'));
                }
                if (uploader.files.length == 0 && canRedirect) {
                    redirectToGeolocate();
                }
            }
            // handle upload
            if (uploader.files.length > 0) {
                uploader.start();
            } else {
                if ($('.imagesToDelete [type="hidden"]').length == totalImages) {
                    raiseError();
                }
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
    $('#geoloc').on(
        'click',
        function() {
        	_setFlashMessages(
                'Noticias',
                'No se han realizado cambios en las imagenes',
                'alert',
                function() {
                    redirectToGeolocate();
                }
            );
        }
    );
});
