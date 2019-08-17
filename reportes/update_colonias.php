<?php
	include dirname(__DIR__).'/wp-load.php';
	global $wpdb;

	extract($_GET);

	if( isset($_GET["id"]) && isset($_GET["colonia"]) ){

		$cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id = ".$id);
		$atributos = unserialize( $cuidador->atributos );
		$atributos["colonia"] = $colonia;
		$atributos = serialize($atributos);

		$wpdb->query("UPDATE cuidadores SET atributos = '{$atributos}' WHERE id = ".$id);
	}

	$cuidadores = $wpdb->get_results("SELECT * FROM cuidadores WHERE atributos LIKE '%colonia%' ");

	$cuidadores_options = '';
	$cuidadores_options .= '<option value="">Seleccione un cuidador</option>';
	foreach ($cuidadores as $key => $cuidador) {
		$cuidadores_options .= '<option value="'.$cuidador->id.'">'.$cuidador->nombre.' '.$cuidador->apellido.'</option>';
	}
?>

<form action="?" method="GET">
	<label>Cuidador</label>
	<select id="cuidador"><?= $cuidadores_options ?></select>
	<div id="info"></div>
</form>

<style type="text/css">
	form {
	    padding: 15px;
	}
	#cuidador{
		margin-bottom: 15px;
	}
	#info > div {
		margin: 0px 0px 5px;
	}
</style>

<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){

		jQuery("#cuidador").on('change', function(e){

			jQuery.post(
				'<?= get_home_url() ?>/reportes/ajax/direccion.php',
				{
					id: jQuery(this).val()
				},
				function(data){
					jQuery("#info").html( data );
				}
			);

		});

	});
</script>