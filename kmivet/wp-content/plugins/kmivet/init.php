<?php
	/*
		Plugin Name: Kmivet Plugin
		Description: Plugin Kmivet
		Author: Ing. Ángel Veloz
		Version: 1.0.0
	*/

	ini_set('display_errors', 'On');
	error_reporting( E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING );
	// error_reporting( E_ALL );

	global $wpdb;

	$h = get_home_url();
	$t = get_template_directory_uri();
	$p = plugin_dir_url(__FILE__);

    $vlz = [
        "h" => $h,
        "t" => $t,
        "p" => $p,
        "i" => $p.'res/img',
        "c" => $p.'res/css',
        "j" => $p.'res/js',
        "pf" => $wpdb->prefix.'kmivet_',
    ];

	include __DIR__.'/includes/vlz_wordpress.php';
	include __DIR__.'/includes/vlz_helpers.php';
	include __DIR__.'/includes/vlz_functions.php';
	include __DIR__.'/extras/init.php';

	add_action ('admin_menu', function () {
		initMenu( [
			[
				'tit' => 'Reportes', 
				'mod' => 'reservas', 
				'ico' => 'dashicons-menu',
				'sub' => [
					[
						'tit' => 'Reservas', 
						'mod' => 'reservas'
					],
					[
						'tit' => 'Pacientes', 
						'mod' => 'pacientes'
					],
					[
						'tit' => 'Veterinarios', 
						'mod' => 'veterinarios'
					],
				]
			],
		] );
	});	

?>