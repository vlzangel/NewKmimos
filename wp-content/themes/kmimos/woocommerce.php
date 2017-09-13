<?php 

    wp_enqueue_style('producto', getTema()."/css/producto.css", array("wc-bookings-styles", "toggle-switch"), '1.0.0');
	wp_enqueue_style('producto_responsive', getTema()."/css/responsive/producto_responsive.css", array(), '1.0.0');

	wp_enqueue_script('producto', getTema()."/js/producto.js", array("jquery"), '1.0.0');

	get_header();
		woocommerce_content();
    get_footer(); 
?>