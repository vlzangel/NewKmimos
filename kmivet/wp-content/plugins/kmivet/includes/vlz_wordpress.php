<?php
	
	add_action( 'admin_init', function() {
	    global $wp_filter;
	 
	    if (is_network_admin() and isset($wp_filter["network_admin_notices"])) {
	        unset($wp_filter['network_admin_notices']);
	    } elseif(is_user_admin() and isset($wp_filter["user_admin_notices"])) {
	        unset($wp_filter['user_admin_notices']);
	    } else {
	        if(isset($wp_filter["admin_notices"])) {
	            unset($wp_filter['admin_notices']);
	        }
	    }
	 
	    if (isset($wp_filter["all_admin_notices"])) {
	        unset($wp_filter['all_admin_notices']);
	    }
	} );

	add_action( "admin_enqueue_scripts", function ( $hook ) {
		global $vlz; extract($vlz);
		wp_enqueue_style( "vlz_admin_global", $p."/res/css/admin_global.css?v=".time(), array(), "1.0.0" );

	} );

	add_action( 'admin_menu', function() {
		
		$user = wp_get_current_user();
		echo '<style> #adminmenu li.wp-menu-separator { display: none; }</style>';
		// if( $user->ID != 1 ){
			// remove_menu_page("index.php");

			remove_submenu_page("index.php", "update-core.php");
			remove_menu_page("tools.php");
			remove_menu_page("upload.php");
			remove_menu_page("themes.php");

			/*
		    remove_menu_page('edit.php');
		    remove_menu_page('edit-comments.php');
			remove_menu_page("edit.php");
			remove_menu_page("upload.php");
			remove_menu_page("edit.php?post_type=page");
			remove_menu_page("dit-comments.php");
			remove_menu_page("themes.php");
			remove_menu_page("plugins.php");
			// remove_menu_page("users.php");
			remove_menu_page("tools.php");
			remove_menu_page("options-general.php");
			remove_submenu_page( 'index.php', 'update-core.php' );
			remove_menu_page("vc-general");
			remove_menu_page("revslider");
			*/
		// }
		
	}, 999 );

?>