<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}

	global $wpdb;

	$PAGE = $_GET["page"]+0;

	$PAGE *= 50;

/*	$SQL = "
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
*/
	$SQL = "SELECT * FROM `wp_kmimos_subscribe` WHERE source = '{$_SESSION["label"]->wlabel}'";
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
		$conocio = "WL";
		$color = "#6194e6";
		if( strtolower($usuario->source) == "cc-petco" ){
			$conocio =  "CC Petco";
			$color = "#67e661";
		}
		if( strtolower($usuario->source) == "petco" ){
			$conocio = 'Kmimos Petco';
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
            <th>Fecha Registro</th>
            <th>Email</th>
            <th>Donde nos conocio?</th>
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