$(document).ready(function() {
	$('#formatotabla').dataTable( {
		"sScrollY": "300px",
		"sDom": '<"clear"><"top"fi<"toolbardt">>rt<"bottom"S',
		"bStateSave": true,

		"aaSorting": [[ 3, "desc" ]],
		"aoColumnDefs": [
		     { "bSortable": false, "bSearchable":false, "aTargets": [ 0 ]}
		     ],

	//	"sDom": '<"top"fi>rt<"bottom"lp<"clear">',
		"bPaginate": false,
	//	"sPaginationType": "full_numbers",
		"oLanguage": {
			"sLengthMenu": "Ver _MENU_ registros por página",
			"sZeroRecords": "Nada por aquí",
			"sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
			"sInfoEmtpy": "Showing 0 to 0 of 0 records",
			"sInfoFiltered": "(filtrando de _MAX_ registros totales)",
			"sSearch": "Buscar:",
			"oPaginate": {
				"sFirst":    "Primero",
				"sPrevious": "Anterior",
				"sNext":     "Siguiente",
				"sLast":     "Último"
				},
			
			}
	


	} );
	
	$("div.toolbardt").html('<a href="/admin/agregar-encuesta">crear nueva encuesta</a>');
} );
