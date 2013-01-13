$(document).ready(function() {
	$('#formatotabla').dataTable( {
		"sScrollY": "300px",
		"sDom": 'C<"clear"><"top"fi<"toolbardt">>rt<"bottom"S',
		"bStateSave": true,
		"oColVis": {
			"aiExclude": [ 0,5 ],
			"buttonText": "Mostrar / esconder columnas"
			},
		"aaSorting": [[ 3, "desc" ]],
		"aoColumnDefs": [
		     { "bVisible": false, "aTargets": [ 6 ] },
		     { "bSortable": false, "bSearchable":false, "aTargets": [ 0,5 ]}
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
	
	$("div.toolbardt").html('<a href="/admin/agregar-noticia">crear nueva noticia</a>');
} );
