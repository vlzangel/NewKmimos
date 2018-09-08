<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}

	global $wpdb;

	$PAGE = $_GET["page"]+0;

	$PAGE *= 50;

	$SQL = "
		SELECT 
			SQL_CALC_FOUND_ROWS *
		FROM 
			{$wpdb->prefix}users AS usuarios
		INNER JOIN {$wpdb->prefix}usermeta AS m ON ( m.user_id = usuarios.ID )
		WHERE
			(
				m.meta_key = 'user_referred' OR
				m.meta_key = '_wlabel' 
			) AND
			m.meta_value = '{$_SESSION["label"]->wlabel}' AND
			usuarios.user_registered >= '2018-09-01 00:00:00'
		GROUP BY usuarios.ID DESC
		LIMIT {$PAGE}, 50";

	$usuarios = $wpdb->get_results($SQL);

	$foundRows = $wpdb->get_var("SELECT FOUND_ROWS() as foundRows");

	$registros = "";
	foreach ($usuarios as $usuario) {
		$metas = get_user_meta($usuario->ID);

		$conocio = "WL Petco";
		$color = "#6194e6";
		if( strtolower($metas["user_referred"][0]) == "cc-petco" ){
			$conocio =  "CC Petco";
			$color = "#67e661";
		}
		if( strtolower($metas["user_referred"][0]) == "petco" ){
			$conocio = 'Kmimos Petco';
			$color = "#e455a8";
		}
		$registros .= "
			<tr>
				<td>{$usuario->ID}</td>
				<td>".( date("d/m/Y", strtotime( $usuario->user_registered ) ) )."</td>
				<td>".$metas["first_name"][0]." ".$metas["last_name"][0]."</td>
				<td>{$usuario->user_email}</td>
				<td>".$metas["user_mobile"][0]."</td>
				<td style='background:".$color."; color: #FFF; font-weight: 600;'>".$conocio."</td>
				<td>".ucfirst($metas["user_gender"][0])."</td>
				<td>".$metas["user_age"][0]."</td>
			</tr>
		";
	}

	$paginas = ""; if( $_GET["page"] == 0){ $_GET["page"] = 1;}
	for ($i=1; $i < ( $foundRows/50 ); $i++) { 
		$activo = ($_GET["page"] == $i) ? "activo" : "";
		$paginas .= "<span onClick='getPaginacion({$i})' class='{$activo}'>{$i}</span>";
	}
?>

<div class="module_title">
    Clientes
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
    <tbody>
        <?php echo $registros; ?>
    </tbody>
</table>
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
<div class="paginacion">
	<?php echo $paginas; ?>
</div>

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