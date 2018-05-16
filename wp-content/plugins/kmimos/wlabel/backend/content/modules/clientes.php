<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}

	global $wpdb;

	$PAGE = $_GET["page"]+0;

	$PAGE *= 10;

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
		LIMIT {$PAGE}, 10";

	$usuarios = $wpdb->get_results($SQL);

	$foundRows = $wpdb->get_var("SELECT FOUND_ROWS() as foundRows");

/*	echo "<pre>";
		print_r($_GET);
		print_r($foundRows);
	echo "</pre>";*/

	$registros = "";
	foreach ($usuarios as $usuario) {
		$registros .= "
			<tr>
				<td>{$usuario->ID}</td>
				<td>{$usuario->display_name}</td>
				<td>{$usuario->user_email}</td>
			</tr>
		";
	}

	$paginas = ""; if( $_GET["page"] == 0){ $_GET["page"] = 1;}
	for ($i=1; $i < ( $foundRows/10 ); $i++) { 
		$activo = ($_GET["page"] == $i) ? "activo" : "";
		$paginas .= "<span onClick='getPaginacion({$i})' class='{$activo}'>{$i}</span>";
	}
?>

<div class="module_title" onclick="getPaginacion(1)">
    Clientes
</div>

<table cellspacing="0" cellpadding="0" width="100%">
    <thead>
        <tr>
            <th width="40">ID</th>
            <th width="40%">Nombre</th>
            <th>Email</th>
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