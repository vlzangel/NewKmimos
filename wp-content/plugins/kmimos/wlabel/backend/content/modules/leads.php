<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}

	global $wpdb;
	$PAGE = $_GET["page"]+0;
	$PAGE *= 50;
	$SQL = "SELECT * FROM `wp_kmimos_subscribe` WHERE source = '{$_SESSION["label"]->wlabel}' AND time >= '2018-09-01 00:00:00' ";
	$usuarios = $wpdb->get_results($SQL);
	$registros = "";
	foreach ($usuarios as $usuario) {
		$conocio = "WL";
		$color = "#6194e6";
		if( strtolower($usuario->source) == "cc-petco" ){
			$conocio =  "CC Petco";
			$color = "#67e661";
		}
		if( strtolower($usuario->source) == "petco" ){
			$conocio = 'WL Petco';
			$color = "#e455a8";
		}
		$registros .= "
			<tr>
				<td>".( date("d/m/Y", strtotime( $usuario->time ) ) )."</td>
				<td>".$usuario->email."</td>
				<td>".$conocio."</td>
			</tr>
		";
	}
?>

<div class="module_title">
    Clientes
</div>

<table id="_example_" class="table table-striped table-bordered nowrap" style="width:100%" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th>Fecha Registro</th>
            <th>Email</th>
            <th>Donde nos conocio?</th>
        </tr>
    </thead>
    <tbody>
        <?php echo $registros; ?>
    </tbody>
</table>


<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#_example_').DataTable({
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
            "scrollX": true
        });
    } );
</script>

<style type="text/css">
	.paginacion{
		overflow: hidden;
	    padding: 10px 0px;
	}
	.paginacion span {
		display: inline-block;
		padding: 10px;
		cursor: pointer;
	}
	.paginacion span.activo {
		background: #000;
		color: #FFF;
	}
</style>