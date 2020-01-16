<?php
	
	/* Functions old */
	include __DIR__.'/NEW/funciones.php';
	include __DIR__.'/NEW/funciones_plugin.php';
	include __DIR__.'/KMIVET/funciones.php';

	/* Functions new */
	include __DIR__.'/procesos/funciones/mediqo.php';
	include __DIR__.'/procesos/funciones/kmivet.php';

	add_shortcode( "kv", function( $atts ) {
		extract($atts);
		if( $sc != '' ){
			$path = __DIR__.'/shortcodes/'.$sc;
			if( file_exists( $path.'/init.php' ) ){
				global $wpdb;
				if( file_exists( $path.'/css.css' ) ){
					wp_enqueue_style( $sc.'_css', getTema()."/shortcodes/{$sc}/css.css", array(), "1.0.0" );
				}
				echo "<div class='container_shortcode'>";
					include $path.'/init.php';
				echo "</div>";
				if( file_exists( $path.'/js.js' ) ){
					wp_enqueue_script($sc.'_js', getTema()."/shortcodes/{$sc}/js.js", array("jquery"), '1.0.0');
				}
			}
		}
	} );

	add_action( "wp_ajax_kv", function(){ ajax_kv(); } );
	add_action( "wp_ajax_nopriv_kv", function(){ ajax_kv(); } );
	function ajax_kv(){
		extract($_GET);
		extract($_POST);
		if( $m != '' && $a != '' ){
			$path = __DIR__.'/ajax/'.$m.'/'.$a.'.php';
			if( file_exists( $path ) ){ global $wpdb; include $path; }
		}
		die();
	}

	/* Comentarios */

		function parseNameClient($clent_id){
			$metas = get_user_meta($clent_id);
			$nombre = explode(" ", $metas["first_name"][0]);
			$apellido = mb_substr($metas["last_name"][0], 0, 1, "utf-8");
			return ucfirst($nombre[0])." ".$apellido.".";
		}

	/* Email */

		function getUrlImgs(){
			$url = get_home_url()."/wp-content/themes/kmimos/images/emails";
			return $url;
		}

		function getTemplate($plantilla){
			$template = __DIR__.'/template/mail/'.$plantilla.'.php';
			return file_get_contents($template);
		}

		function buildEmailTemplate($plantilla, $params){
			$HTML = getTemplate($plantilla);
			foreach ($params as $key => $value) {
				if( is_array($value) ){
					$temp_html = '';
					foreach ($value as $key_2 => $value_2) {
						$pre_plantilla = getTemplate($key_2);
						foreach ($value_2 as $key_3 => $value_3) {
				            $pre_plantilla = str_replace('['.strtolower($key_3).']', $value_3, $pre_plantilla);
				            $pre_plantilla = str_replace('['.strtoupper($key_3).']', $value_3, $pre_plantilla);
						}
			            $temp_html .= $pre_plantilla;
					}
		            $HTML = str_replace('['.strtolower($key).']', $temp_html, $HTML);
		            $HTML = str_replace('['.strtoupper($key).']', $temp_html, $HTML);
				}else{
		            $HTML = str_replace('['.strtolower($key).']', $value, $HTML);
		            $HTML = str_replace('['.strtoupper($key).']', $value, $HTML);
				}
	        }
	        $HTML = str_replace('[URL_IMGS]', getUrlImgs($test), $HTML);
	        return $HTML;
		}

		function sendEmailTest($asunto, $mensaje){
			if( $_GET["code"] != "" && $_GET["code"] != $_SESSION["code"] ){
				wp_mail( "a.veloz@kmimos.la", $asunto." - [ TEST ]", $mensaje);
			}
		}

		function setSessionCode($exit = false){
			$_SESSION["code"] = $_GET["code"];
			if( $exit ){ exit(); }
		}

		function showEmail($html){
			echo $html;
		}

	/* End Email */
	
	include dirname(__FILE__).'/widgets/admin.php';

	add_action('transition_comment_status', 'my_approve_comment_callback', 10, 3);
	function my_approve_comment_callback($new_status, $old_status, $comment) {
	    if($old_status != $new_status) {
	        if($new_status == 'approved') {
	            vlz_actualizar_ratings($comment->comment_post_ID);
	        }
	    }
	}
	
	function getComision(){
    	return 1.25;
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
	
	include(__DIR__."/admin/generales/funciones.php");
	include(__DIR__."/admin/generales/vlz_cpt_functions.php");

	$MODULOS_ADMIN_2 = [];
	
	function vlz_incluir($carpeta){
		$path_functions = dirname(__FILE__)."/".$carpeta."/";
		$directorio = opendir( $path_functions );
		while ($archivo = readdir($directorio)) {
		    if( file_exists( $path_functions.'/'.$archivo."/modulo.php" ) ){
		    	include $path_functions.'/'.$archivo."/modulo.php";
		    }
		}
	}

	$vlz_globals = [
		"base" => get_template_directory_uri()."/admin_2/"
	];
	vlz_incluir( "admin_2" );
	
	include( dirname(__FILE__)."/admin_2/init_modulos.php");

	include(dirname(dirname(dirname(__DIR__)))."/monitor/conf/menu.php");

?>
