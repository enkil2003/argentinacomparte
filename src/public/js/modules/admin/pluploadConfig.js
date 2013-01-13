$(function() { 
    //traduccion para el plupload
    plupload.addI18n({
        'Select files' : 'Elija archivos:',
        'Add files to the upload queue and click the start button.' : 'Agregue archivos a la cola de subida y haga click en el boton de iniciar.',
        'Filename' : 'Nombre de archivo',
        'Status' : 'Estado',
        'Size' : 'Tama&ntilde;o',
        'Add files' : 'Agregue archivos',
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
        // General settings
        runtimes : 'gears,html5,browserplus,flash,silverlight',
        url : '/admin/upload-image/',
        max_file_count: 1,
        max_file_size : '10mb',
        chunk_size : '1mb',
        unique_names : false,
        rename: false,
        sortable: true,
        // Specify what files to browse for
        filters : [
            {title : "Image files", extensions : "jpg,gif,png"}
        ],
        // Flash settings
        flash_swf_url : '/js/jquery/plupload/js/plupload.flash.swf',
        // Silverlight settings
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
                            window.location = AFTER_UPLOAD_LOCATION;
                        }
                    },
                    'json'
                );
            }
        }
    });
    // ******************************************
    // there is no native method to do this below
    // ******************************************
    $('.plupload_button.plupload_start').remove();
});