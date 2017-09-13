<?php 
    /*
        Template Name: Carrito
    */

    wp_enqueue_style('carro', $home."/wp-content/themes/pointfinder/css/carro.css", array("woocommerce-general", "wc-bookings-styles", "toggle-switch"), '1.0.0');
	wp_enqueue_style('carro_responsive', $home."/wp-content/themes/pointfinder/css/responsive/carro_responsive.css", array(), '1.0.0');

	wp_enqueue_script('carro', $home."/wp-content/themes/pointfinder/js/carro.js", array("jquery"), '1.0.0');

	get_header(); ?>
		<div class="body">
	        <?php echo do_shortcode("[woocommerce_cart]"); ?>
	    </div>
<?php get_footer(); ?>