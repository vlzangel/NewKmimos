<?php 
    /*
        Template Name: Perfil Kmivet
    */

    $HEADER = 'kmivet';

	$link_registro = '  data-toggle="modal" data-target="#popup-registro-veterinario" ' ;

	wp_enqueue_style( 'datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.css", array(), "1.0.0" );
    wp_enqueue_script('jquery.datepick', getTema()."/lib/datapicker/jquery.datepick.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.plugin', getTema()."/lib/datapicker/jquery.plugin.js", array("jquery"), '1.0.0');

	wp_enqueue_style('registro_cuidador', get_recurso("css")."registro_cuidador.css", array(), '1.0.0');


	wp_enqueue_style('OLD_registro_cuidador', getTema()."/css/registro_cuidador.css", array("kmimos_style"), '1.0.0');
	wp_enqueue_style('OLD_registro_cuidador_responsive', getTema()."/css/responsive/registro_cuidador_responsive.css", array("kmimos_style"), '1.0.0');
	wp_enqueue_style( 'OLD_datepicker.min', getTema()."/css/datepicker.min.css", array(), "1.0.0" );

	wp_enqueue_style('registro_cuidador_responsive', get_recurso("css")."responsive/registro_cuidador.css", array(), '1.0.0');

	wp_enqueue_style('registro_veterinario', get_recurso("css")."registro_veterinario.css?v=".time(), array(), '1.0.0');
	wp_enqueue_style('registro_veterinario_responsive', get_recurso("css")."responsive/registro_veterinario.css?v=".time(), array(), '1.0.0');

    get_header(); ?>

		<div style="
			text-align: center;
		    font-size: 20px;
		    padding: 140px 0px 50px;
		">
			Aquí va el perfil del veterinario 
		</div>
		
<?php 
	wp_enqueue_script('registro_veterinario', getTema()."/lib/kmivet/validacion.js", array("jquery"), '1.0.0');
	wp_enqueue_script('kmivet_validacion_lib', get_recurso('js')."registro_veterinario.js", array("jquery"), '1.0.0');
	get_footer(); 
?>