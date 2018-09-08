<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}

	global $wpdb;

	$SQL = "
		SELECT 
			* 
		FROM 
			wp_users
		LEFT JOIN wp_usermeta AS wlabel ON ( wp_users.ID = wlabel.user_id )
		WHERE 
			( wlabel.meta_key = 'user_referred' OR wlabel.meta_key = '_wlabel' ) AND
			( wlabel.meta_value = 'cc-petco' OR wlabel.meta_value = 'petco' ) AND
			wp_users.user_registered >= '2018-09-01 00:00:00'
			";

	$usuarios = $wpdb->get_results($SQL);
	$registros = "";
	if( count($usuarios) > 0 ){
		foreach ($usuarios as $usuario) {
			$metas = get_user_meta($usuario->ID);
			$registros .= "
				<tr>
					<td>".$metas["first_name"][0]." ".$metas["last_name"][0]."</td>
					<td>".$usuario->user_email."</td>
					<td>".( date("d/m/Y", strtotime( $usuario->user_registered ) ) )."</td>
					<td>".$metas["user_mobile"][0]."</td>
					<td>".$metas["user_referred"][0]."</td>
				</tr>
			";
		}
	}
?>

<div class="module_title">
    Reservas por primera vez
</div>

<table id="_example_" class="table table-striped table-bordered nowrap" style="width:100%" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th>Nombre y apellido</th>
            <th>Email</th>
            <th>Fecha Registro</th>
            <th>Teléfono</th>
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