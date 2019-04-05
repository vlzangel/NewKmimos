<?php get_header(); 
    wp_enqueue_style('finalizar', getTema()."/css/finalizar.css", array(), '1.0.0');
	wp_enqueue_style('finalizar_responsive', getTema()."/css/responsive/finalizar_responsive.css", array(), '1.0.0');


	global $wpdb;
	if( !empty($_GET['token'])  ){
		$pedido = $wpdb->get_var("
			SELECT post_id as pedido 
			FROM wp_postmeta as m 
			WHERE m.meta_value = '".$_GET['token']."'
		");
		if( $pedido > 0 ){
			$reserva= $wpdb->get_var("SELECT ID as reserva 
				FROM wp_posts as m 
				WHERE m.post_parent = {$pedido}");
			if( $reserva > 0 ){
				$product_id = $wpdb->get_var("SELECT meta_value as product_id FROM wp_postmeta as m where m.post_id = {$reserva} and meta_key = '_booking_product_id'");
			}
		}
	}

?>
<div class="km-content km-step-end" style="width: 50%; margin: 0 auto; padding: 100px 0px;">
	<div class="text-center">
		<img src="<?php echo get_recurso("img"); ?>/RESERVA/img_superior.png" width="400px" style="margin: auto;"	/>
		<div class="finalizar_titulo" style="text-align: center; ">
			¡Lo sentimos, Paypal no pudo procesar el pago! 
		</div>
		<div class="que_debo_hacer">
			<strong style="font-weight: bold!important; font-size: 15px;">Intenta Nuevamente con otro medio de pago o comunicate con nosotros.</strong>
		</div>
		<div class="finalizar_titulo text-center">
			<a href="<?php echo get_home_url(); ?>/reservar/<?php echo $product_id; ?>/" class="btn boton">Volver a intentar</a>
		</div>
	</div>
</div>
<?php get_footer(); ?>
