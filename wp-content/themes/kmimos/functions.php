<?php

	function get_publicidad($seccion){
				
		$publicidad = rand(1, 2);

		$campaing = "intriga";
		if( $publicidad == "2" ){
			$campaing = "informacion";
		}

		switch ($seccion) {
			case 'solicitud':
				return '
					<div style="margin-top: 20px;">

						<a class="" href="https://nutriheroes.com.mx/?utm_source=kmimos_conocer&utm_medium=email&utm_campaign='.$campaing.'&utm_term=alimento_mascotas_nutricion" target="_blank">
							<img style="width: 100%; border-radius: 0px 0px 8px 8px; padding: 0px;" src="'.getTema().'/images/banners_nutriheroes/solicitudes/movil.jpg" />
						</a>

					</div>
				';
			break;
			case 'reserva':
				return '
					<div style="margin-top: 5px;">
						<a class="" href="https://nutriheroes.com.mx/?utm_source=kmimos_reserva&utm_medium=email&utm_campaign='.$campaing.'&utm_term=alimento_mascotas_nutricion" target="_blank">
							<img style="width: 100%;" src="'.getTema().'/images/banners_nutriheroes/reservas/movil.jpg" />
						</a>
					</div>
				';
			break;
			case 'correo':
				return '
					<div style="margin: 25px 0px;">
                        <a href="https://nutriheroes.com.mx/?utm_source=page&utm_medium=email&utm_campaign=intriga&utm_term=alimento_mascotas_nutricion" target="_blank">
                            <img style="width: 100%;" src="'.getTema().'/images/banners_nutriheroes/correos/movil.jpg" />
                        </a>
                    </div>
				';
			break;
		}

/*		
		switch ($seccion) {
			case 'solicitud':
				return '
					<div style="margin-top: 20px;">

						<a class="publicidad_solo_pc" href="https://nutriheroes.com.mx/?utm_source=kmimos_conocer&utm_medium=email&utm_campaign='.$campaing.'&utm_term=alimento_mascotas_nutricion" target="_blank">
							<img style="width: 100%; border-radius: 0px 0px 8px 8px; padding: 0px;" src="'.getTema().'/images/banners_nutriheroes/solicitudes/'.$publicidad.'.jpg" />
						</a>

						<a class="publicidad_solo_movil" href="https://nutriheroes.com.mx/?utm_source=kmimos_conocer&utm_medium=email&utm_campaign='.$campaing.'&utm_term=alimento_mascotas_nutricion" target="_blank">
							<img style="width: 100%; border-radius: 0px 0px 8px 8px; padding: 0px;" src="'.getTema().'/images/banners_nutriheroes/solicitudes/movil.jpg" />
						</a>

					</div>
				';
			break;
			case 'reserva':
				return '
					<div style="margin-top: 5px;">
						<a class="publicidad_solo_pc" href="https://nutriheroes.com.mx/?utm_source=kmimos_reserva&utm_medium=email&utm_campaign='.$campaing.'&utm_term=alimento_mascotas_nutricion" target="_blank">
							<img style="width: 100%;" src="'.getTema().'/images/banners_nutriheroes/reservas/'.$publicidad.'.jpg" />
						</a>
						<a class="publicidad_solo_movil" href="https://nutriheroes.com.mx/?utm_source=kmimos_reserva&utm_medium=email&utm_campaign='.$campaing.'&utm_term=alimento_mascotas_nutricion" target="_blank">
							<img style="width: 100%;" src="'.getTema().'/images/banners_nutriheroes/reservas/movil.jpg" />
						</a>
					</div>
				';
			break;
			case 'correo':
				return '
					<div style="margin: 25px 0px;">
                        <a href="https://nutriheroes.com.mx/?utm_source=page&utm_medium=email&utm_campaign=intriga&utm_term=alimento_mascotas_nutricion" target="_blank">
                            <img style="width: 100%;" src="'.getTema().'/images/banners_nutriheroes/correos/movil.jpg" />
                        </a>
                    </div>
				';
			break;
		}*/

	}

	function getComision(){
    	return 1.25;
	}
	
	function calcular_edad($fecha){
		$fecha = str_replace("/","-",$fecha);
		$hoy = date('Y/m/d');

		$diff = abs(strtotime($hoy) - strtotime($fecha) );
		$years = floor($diff / (365*60*60*24)); 
		$desc = " Años";
		$edad = $years;
		if($edad==0){
			$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
			$edad = $months;
			$desc = ($edad > 1) ? " Meses" : " Mes";
		}
		if($edad==0){
			$days  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			$edad = $days;
			$desc = " Días";
		}

		return $edad . $desc;
	} 
	function tiene_fotos_por_subir($user_id, $login = false){
		global $wpdb;

  		if( !isset($_SESSION) ){ session_start(); }

  		$sql = "
			SELECT 
				inicio.meta_value inicio
			FROM 
				wp_posts AS posts
			LEFT JOIN wp_postmeta AS metas_reserva ON ( posts.ID = metas_reserva.post_id AND metas_reserva.meta_key='_booking_product_id' )
			LEFT JOIN wp_postmeta AS inicio ON ( posts.ID = inicio.post_id AND inicio.meta_key='_booking_start' )
			LEFT JOIN wp_postmeta AS fin ON ( posts.ID = fin.post_id AND fin.meta_key='_booking_end' )
			LEFT JOIN wp_posts AS producto ON ( producto.ID = metas_reserva.meta_value )
			LEFT JOIN wp_posts AS orden ON ( orden.ID = posts.post_parent )
			WHERE 
				posts.post_type      = 'wc_booking' AND 
				posts.post_status    = 'confirmed'  AND
				(
					fin.meta_value       > NOW()  AND
					inicio.meta_value    <= NOW()
				) AND
				producto.post_author = '{$user_id}'
			ORDER BY posts.ID DESC
		";

		$reservas_activas = $wpdb->get_results($sql);

		$contador = 0;
		foreach ($reservas_activas as $key => $value) {
			if( strtotime( $value->inicio ) >= strtotime("2017-12-23 00:00:00") ){
				$contador++;
			}
		}

		if( $contado+0 > 0 ){
			if($login){
				$_SESSION["recordar_subir_fotos"] = true;
			}
			return true;
		}
		return false;
	}
    
    function kmimos_registros_fotos($id_reserva){
        global $wpdb;
        $metas = get_post_meta($id_reserva);

        $inicio = strtotime( $metas["_booking_start"][0] );
        $fin = strtotime( $metas["_booking_end"][0] );

        $cuidador = $wpdb->get_var("SELECT post_author FROM wp_posts WHERE ID = ".$metas["_booking_product_id"][0]);

        for ($i=$inicio; $i < $fin; $i+=86400) { 
            $fecha = date("Y-m-d", $i);
            $existe = $wpdb->get_var("SELECT id FROM fotos WHERE reserva = {$id_reserva} AND fecha = '{$fecha}' ");
            if( $existe == null ){
            	$wpdb->query("INSERT INTO fotos VALUES ( NULL, {$id_reserva}, {$cuidador}, '{$fecha}', '0', '0', 'a:0:{}', '0');");
            }
        }
    }

	function getTema(){
    	return get_template_directory_uri();
	}
    
	add_filter( 'show_admin_bar', '__return_false' );

	add_action( 'admin_init', 'disable_autosave' );
	function disable_autosave() {
		wp_deregister_script( 'autosave' );
	}

	add_filter( 'woocommerce_checkout_fields' , 'set_input_attrs' );
	function set_input_attrs( $fields ) {
		$fields['billing']['billing_address_2']['maxlength'] = 50;
		$fields['billing']['billing_postcode']['maxlength'] = 12;
		$fields['billing']['billing_country']['class'][] = "hide";
	   	return $fields;
	}

	remove_action ('wp_head', 'rsd_link');
	remove_action( 'wp_head', 'wlwmanifest_link');
	remove_action( 'wp_head', 'wp_shortlink_wp_head');
	remove_action( 'wp_head', 'wp_generator');
	remove_action( 'wp_head','rest_output_link_wp_head');
	remove_action( 'wp_head','wp_oembed_add_discovery_links');
	remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
	remove_action('wp_head', 'rel_canonical');
	remove_action('wp_head', 'rel_canonical', 47);

	remove_action ('wp_head', 'wp_site_icon', 99);

	// add_action('wp_enqueue_scripts', 'no_more_jquery');
	// function no_more_jquery(){
	//     wp_deregister_script('jquery');
	// }

	function move_scripts_from_head_to_footer() {
	    remove_action( 'wp_head', 'wp_print_scripts' );
	    remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
	    remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
	    add_action( 'wp_footer', 'wp_print_scripts', 5);
	    add_action( 'wp_footer', 'wp_enqueue_scripts', 5);
	    add_action( 'wp_footer', 'wp_print_head_scripts', 5);
	}
	add_action('wp_enqueue_scripts', 'move_scripts_from_head_to_footer');

	function _remove_script_version( $src ){
	    $parts = explode( '?ver', $src );
        return $parts[0]."?ver=".time();
        // return $parts[0];
	}
	add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
	add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );

	add_filter( 'woocommerce_product_tabs', 'sb_woo_remove_reviews_tab', 98);
	function sb_woo_remove_reviews_tab($tabs) {
		unset($tabs['reviews']);
		return $tabs;
	}

	add_action( 'woocommerce_after_shop_loop_item', 'mycode_remove_add_to_cart_buttons', 1 );
	function mycode_remove_add_to_cart_buttons() {
	    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	}

	add_action( 'woocommerce_after_shop_loop_item', 'mycode_add_more_info_buttons', 1 );
	function mycode_add_more_info_buttons() {
	    add_action( 'woocommerce_after_shop_loop_item', 'mycode_more_info_button' );
	}
	function mycode_more_info_button() {
		global $product;
		echo '<a href="' . get_permalink( $product->id ) . '" class="button add_to_cart_button product_type_external">Reservar</a>';
	}

	// Woocommerce only 1 product in the cart
	add_filter( 'woocommerce_add_cart_item_data', '_empty_cart' );
	function _empty_cart( $cart_item_data ){
		WC()->cart->empty_cart();
		return $cart_item_data;
	}

	function is_cuidador(){
		$user = wp_get_current_user();
		if( $user->roles[0] == '' ){ return -1; }
		if( $user->roles[0] == 'vendor' ){ return 1; }else{ return 0; }
	}

	if ( ! function_exists( 'pointfinder_setup' ) ){
		function pointfinder_setup() {
			add_theme_support('menus');
		    add_theme_support('post-thumbnails');
		    add_theme_support( 'woocommerce' );
			add_theme_support( 'html5', array(
				'search-form', 'comment-form', 'comment-list',
			) );
			register_nav_menus(array( 
				'pointfinder-main-menu' => esc_html__('Point Finder Main Menu', 'pointfindert2d')
		    ));
		}
	};
	add_action('after_setup_theme', 'pointfinder_setup');

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	add_filter('widget_text', 'do_shortcode'); 
	add_filter('the_excerpt', 'do_shortcode'); 

	function disable_all_feeds() {
		
	}

	add_action('do_feed', 'wpb_disable_feed', 1);
	add_action('do_feed_rdf', 'wpb_disable_feed', 1);
	add_action('do_feed_rss', 'wpb_disable_feed', 1);
	add_action('do_feed_rss2', 'wpb_disable_feed', 1);
	add_action('do_feed_atom', 'wpb_disable_feed', 1);
	add_action('do_feed_rss2_comments', 'wpb_disable_feed', 1);
	add_action('do_feed_atom_comments', 'wpb_disable_feed', 1);

	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'feed_links', 2 );

	function head_cleanup() {
	    remove_action( 'wp_head', 'rel_canonical' ); //quita el rel canonical
	    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );//quita rel next y rel prev
	    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	}
	add_action( 'init', 'head_cleanup' ); 

	add_filter( 'wc_add_to_cart_message', '__return_null()' );

	function modify_jquery() {
//		wp_deregister_script('jquery');
//		wp_register_script('jquery', get_template_directory_uri().'/js/jquery.js', false, '1.0.0');
//		wp_enqueue_script('jquery');
	}
	add_action('wp_enqueue_scripts', 'modify_jquery');


	// /**
	// * Optimiza los scripts de WooCommerce
	// * Quita la tag Generator de WooCommerce, estilos y scripts de páginas no WooCommerce.
	// */
	// add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );

	// function child_manage_woocommerce_styles() {
	// //quitamos la tag generator meta
	// remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

	// //Primero comprobamos si está instalado WooCommerce para evitar errores fatales
	// if ( function_exists( 'is_woocommerce' ) ) {
	// //y aplicamos el dequeue a scripts y estilos
	// if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
	// wp_dequeue_style( 'woocommerce_frontend_styles' );
	// wp_dequeue_style( 'woocommerce_fancybox_styles' );
	// wp_dequeue_style( 'woocommerce_chosen_styles' );
	// wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
	// wp_dequeue_script( 'wc_price_slider' );
	// wp_dequeue_script( 'wc-single-product' );
	// wp_dequeue_script( 'wc-add-to-cart' );
	// wp_dequeue_script( 'wc-cart-fragments' );
	// wp_dequeue_script( 'wc-checkout' );
	// wp_dequeue_script( 'wc-add-to-cart-variation' );
	// wp_dequeue_script( 'wc-single-product' );
	// wp_dequeue_script( 'wc-cart' );
	// wp_dequeue_script( 'wc-chosen' );
	// wp_dequeue_script( 'woocommerce' );
	// wp_dequeue_script( 'prettyPhoto' );
	// wp_dequeue_script( 'prettyPhoto-init' );
	// wp_dequeue_script( 'jquery-blockui' );
	// wp_dequeue_script( 'jquery-placeholder' );
	// wp_dequeue_script( 'fancybox' );
	// wp_dequeue_script( 'jqueryui' );
	// }
	// }

	// }


	// function wpdm_filter_siteurl($content) {
	// 	$current_server = $_SERVER['SERVER_NAME'];
	//    	return "http://".$current_server."/";
	// }

	// function wpdm_filter_home($content) {
	// 	$current_server = $_SERVER['SERVER_NAME'];
	//    	return "http://".$current_server."/";
	// }

	// function wpdm_conv_tag($content) {
	// 	$search = "/\[dmWpAddr\]/";
	// 	if (preg_match($search, $content)){
	// 		$replace = get_option('siteurl');
	// 		$content = preg_replace ($search, $replace, $content);
	// 	}
	// 	$search = "/\[dmBlogAddr\]/";
	// 	if (preg_match($search, $content)){
	// 		$replace = get_option('home');
	// 		$content = preg_replace ($search, $replace, $content);
	// 	}
	// 	$search = "/\[dmBlogTitle\]/";
	// 	if (preg_match($search, $content)){
	// 		$replace = get_option('blogname');
	// 		$content = preg_replace ($search, $replace, $content);
	// 	}
	// 	$search = "/\[dmTagLine\]/";
	// 	if (preg_match($search, $content)){
	// 		$replace = get_option('blogdescription');
	// 		$content = preg_replace ($search, $replace, $content);
	// 	}
	// 	return $content;
	// }

	// // Add the hooks:
	// add_filter('option_siteurl', 'wpdm_filter_siteurl', 1);
	// add_filter('option_home', 'wpdm_filter_home', 1);


	// function vlz_plugins_url($path = '', $plugin = '') {
	// 	$new_path = explode("/", $path);
	// 	$new_path[2] = $_SERVER['SERVER_NAME'];
	// 	return implode("/", $new_path);
	// }
	// add_filter('plugins_url', 'vlz_plugins_url', -10);

	// add_filter('the_content', 'wpdm_conv_tag'); 
	// add_filter('the_excerpt', 'wpdm_conv_tag'); 

	
	include(__DIR__."/admin/generales/funciones.php");


?>
