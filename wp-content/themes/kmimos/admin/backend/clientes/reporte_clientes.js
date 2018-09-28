var table; 
jQuery(document).ready(function() {

	loadTabla();

    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });
 
    jQuery("#form-search").on("submit", function(e){
		e.preventDefault();
    });

    jQuery("#btn-search").on("click", function(e){
		loadTabla();
	});

});

function sumarDias(fecha, dias){
	fecha.setDate(fecha.getDate() + dias);
	var d = '0'+fecha.getDate();
		d = d.substring(d.length-2, d.length);
	var m = fecha.getMonth();
		m += 1;
		m = '0'+m;
		m = m.substring(m.length-2, m.length);
	var y = fecha.getFullYear();
 
	return y+'-'+m+'-'+d;
}

function loadTabla(){
 	 
	table = jQuery('#example').DataTable();
	table.destroy();

    table = jQuery('#example').DataTable({
    	"language": {
			"emptyTable":			"No hay datos disponibles en la tabla.",
			"info":		   			"Del _START_ al _END_ de _TOTAL_ ",
			"infoEmpty":			"Mostrando 0 registros de un total de 0.",
			"infoFiltered":			"(filtrados de un total de _MAX_ registros)",
			"infoPostFix":			" (actualizados)",
			"lengthMenu":			"Mostrar _MENU_ registros",
			"loadingRecords":		"Cargando...",
			"processing":			"Procesando...",
			"search":				"Buscar:",
			"searchPlaceholder":	"Dato para buscar",
			"zeroRecords":			"No se han encontrado coincidencias.",
			"paginate": {
				"first":			"Primera",
				"last":				"Última",
				"next":				"Siguiente",
				"previous":			"Anterior"
			},
			"aria": {
				"sortAscending":	"Ordenación ascendente",
				"sortDescending":	"Ordenación descendente"
			}
		},
		"dom": '<B><f><t>ip',
		"buttons": [
			{
			  extend: "csv",
			  className: "btn-sm"
			},
			{
			  extend: "excelHtml5",
			  className: "btn-sm"
			},
        ],
		 
        "scrollX": true,
        "ajax": {
            "url": TEMA+'/admin/backend/clientes/ajax/clientes.php',
            "data": { 'desde': jQuery('[name="ini"]').val(), "hasta":jQuery('[name="fin"]').val() },
            "type": "POST"
        }
	});
 
}

function abrir_link(e){
	init_modal({
		"titulo": e.attr("data-titulo"),
		"modulo": "clientes",
		"modal": e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id")
		}
	});
}

 








 