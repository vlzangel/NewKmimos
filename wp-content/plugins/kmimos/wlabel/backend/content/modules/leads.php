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
			m.meta_value = '{$_SESSION["label"]->wlabel}'
		GROUP BY usuarios.ID DESC
		LIMIT {$PAGE}, 50";

	$usuarios = $wpdb->get_results($SQL);

	$foundRows = $wpdb->get_var("SELECT FOUND_ROWS() as foundRows");

	$registros = "";
	foreach ($usuarios as $usuario) {
		/*
			mobile.met_key = 'user_mobile' AND
			edad.met_key = 'user_age' AND
			sexo.met_key = 'user_gender' AND
			conocio.met_key = 'user_referred'
		*/
		$metas = get_user_meta($usuario->ID);

		$conocio = "WL Petco";
		if( strtolower($metas["user_referred"][0]) == "cc-petco" || strtolower($metas["user_referred"][0]) == "petco" ){
			$conocio = $metas["user_referred"][0];
		}
		$registros .= "
			<tr>
				<td>{$usuario->ID}</td>
				<td>".( date("d/m/Y", strtotime( $usuario->user_registered ) ) )."</td>
				<td>".$metas["first_name"][0]." ".$metas["last_name"][0]."</td>
				<td>{$usuario->user_email}</td>
				<td>".$metas["user_mobile"][0]."</td>
				<td>".$conocio."</td>
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

<table cellspacing="0" cellpadding="0" width="100%">
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