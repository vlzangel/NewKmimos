<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}
?>

<div class="module_title">
    Clientes
</div>

<div class="module_botones">
    <table>
        <tr>
            <td><strong>Desde:</strong></td>
            <td><strong>Hasta:</strong></td>
        </tr>
        <tr>
            <td><input type="date" id="desde" name="desde" class="form-control form-control-sm" value="2018-09-01" /></td>
            <td><input type="date" id="hasta" name="hasta" class="form-control form-control-sm" value="<?= date("Y-m-d"); ?>" /></td>
        </tr>
    </table>
</div>

<table id="_example_" class="table table-striped table-bordered nowrap" cellspacing="0" cellpadding="0" width="100%">
    <thead>
        <tr>
            <th width="40">ID</th>
            <th>Fecha Registro</th>
            <th>Nombre y Apellido</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Donde nos conocio?</th>
            <th>Sexo</th>
            <th>Edad</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script type="text/javascript">

    /* Tabla y Filtros de Fechas */

        var table = "";
        jQuery(document).ready(function() {
            table = jQuery('#_example_').DataTable({
                "language": {
                    "emptyTable":           "No hay datos disponibles en la tabla.",
                    "info":                 "Del _START_ al _END_ de _TOTAL_ ",
                    "infoEmpty":            "Mostrando 0 registros de un total de 0.",
                    "infoFiltered":         "(filtrados de un total de _MAX_ registros)",
                    "infoPostFix":          " (actualizados)",
                    "lengthMenu":           "Mostrar _MENU_ registros",
                    "loadingRecords":       "Cargando...",
                    "processing":           "Procesando...",
                    "search":               "Buscar:",
                    "searchPlaceholder":    "Dato para buscar",
                    "zeroRecords":          "No se han encontrado coincidencias.",
                    "paginate": {
                        "first":            "Primera",
                        "last":             "Última",
                        "next":             "Siguiente",
                        "previous":         "Anterior"
                    },
                    "aria": {
                        "sortAscending":    "Ordenación ascendente",
                        "sortDescending":   "Ordenación descendente"
                    }
                },
                "scrollX": true,
                "ajax": {
                    "url": "<?= get_home_url(); ?>/wp-content/plugins/kmimos/wlabel/backend/content/ajax/clientes_data.php",
                    "type": "POST",
                    "dataSrc":  function ( json ) {
                        if(typeof postCargaTable === 'function') {
                            json = postCargaTable(json);
                        }
                        return json.data;
                    } 
                }
            });
        } );

        var DESDE = new Date( "2018-09-01 00:00:00" ).getTime();
        var HASTA = new Date( "<?= date("Y-m-d"); ?> 00:00:00" ).getTime();
        var eliminar = [];
        var data = [];
        function postCargaTable(json){
            eliminar = [];
            data = [];
            DESDE = new Date( jQuery("#desde").val()+" 00:00:00" ).getTime();
            HASTA = new Date( jQuery("#hasta").val()+" 00:00:00" ).getTime();
            jQuery.each(json.data, function( index, value ) {
                var FECHA = new Date( value[1]+" 00:00:00" ).getTime();
                if( DESDE <= FECHA && FECHA <= HASTA ){
                    data.push( value );
                }else{
                    eliminar.push(index);
                }
            });
            json.data = data;
            return json;
        }
        jQuery("#desde").on("change", function(e){ table.ajax.reload(); });
        jQuery("#hasta").on("change", function(e){ table.ajax.reload(); });

    /* Fin Tabla y Filtros de Fechas */

</script>

<style type="text/css">
    .module_botones { overflow: hidden; padding-bottom: 20px; }
    .module_botones input, .module_botones td { font-size: 12px; }
    .module_botones table { float: right; }
</style>