<?php
	
	$dir = str_replace("\\", "\\\\", $dir);
	$url_ajax = admin_url("admin-ajax.php");
	$s = DIRECTORY_SEPARATOR;

	eval('function vlz_'.$mod.'_page(){
		global $wpdb;  global $vlz; 
		extract($_POST);  extract($vlz);

		$m = [ "gen" => "'.$gen.'", "mod" => "'.$mod.'", "sub" => "'.$sub.'", "plu" => "'.$plu.'", "sin" => "'.$sin.'" ];
		extract($m);

		$tabla_name = "{$wpdb->prefix}vlz_{$m["modulo"]}"; 
		echo "<script src=\'https://kit.fontawesome.com/9e9cd60cbd.js\' crossorigin=\'anonymous\'></script>";
		echo "<link rel=\'stylesheet\' href=\'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css\'>";
		echo "<link rel=\'stylesheet\' href=\'https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css\'>";
		echo "<link rel=\'stylesheet\' href=\'https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css\'>";
		echo "<link rel=\'stylesheet\' type=\'text/css\' href=\'{$vlz["p"]}/res/css/admin.css?v='.time().'\' >";
		echo "<link rel=\'stylesheet\' type=\'text/css\' href=\'{$vlz["p"]}/res/css/responsive.css?v='.time().'\' >";

		if( file_exists( "'.$dir.$s.'css.css" ) ){
			echo "<link rel=\'stylesheet\' type=\'text/css\' href=\'{$vlz["p"]}/modulos/'.$mod.'/css.css?v='.time().'\' >";
		}

		echo "<script> var AJAX = \''.$url_ajax.'?action=vlz_'.$mod.'\';  var MOD = \''.$mod.'\'; var PLU = \''.$plu.'\';  var SIN = \''.$sin.'\';  var GEN = \''.$gen.'\';  </script> ";
		$config = [];
		if( file_exists( "'.$dir.$s.'page.php" ) ){ include "'.$dir.$s.'page.php"; }

		if( (isset($config["shw"]) && $config["shw"] != false) || !isset($config["shw"]) ){
			init_page($config, [ 
				"plu" => $plu, 
				"m" => $m 
			]);
		}

		// echo "<script type=\'text/javascript\' src=\'{$vlz["p"]}/lib/ckeditor/ckeditor.js\' ></script>";

		echo "<script src=\'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js\' integrity=\'sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q\' crossorigin=\'anonymous\'></script>";
		echo "<script src=\'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\' integrity=\'sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl\' crossorigin=\'anonymous\'></script>";
		echo "<script src=\'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js\' ></script>";
		echo "<script src=\'https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js\' ></script>";
		echo "<script src=\'https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js\' ></script>";
		echo "<script src=\'https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js\' ></script>";
		echo "<script src=\'https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js\' ></script>";
		echo "<script src=\'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js\' ></script>";
		echo "<script src=\'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js\' ></script>";
		echo "<script src=\'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js\' ></script>";
		echo "<script src=\'https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js\' ></script>";

		echo "<script type=\'text/javascript\' src=\'{$vlz["p"]}/res/js/admin.js?v='.time().'\' ></script>";

		if( file_exists( "'.$dir.$s.'js.js" ) ){
			echo "<script type=\'text/javascript\' src=\'{$vlz["p"]}/modulos/'.$mod.'/js.js?v='.time().'\' ></script>";
		}
		init_modal();
	}');

	eval('add_action( "wp_ajax_vlz_'.$mod.'", function(){
		extract($_POST);
		global $wpdb; global $vlz;
		$MODULO = "'.$mod.'"; 
		extract($vlz);
		$m = [ "gen" => "'.$gen.'", "mod" => "'.$mod.'", "sub" => "'.$sub.'", "plu" => "'.$plu.'", "sin" => "'.$sin.'" ];
		extract($m);
		$tabla_name = "{$pf}{$mod}"; 
		$campos_db = get_campos_db( $tabla_name );
		$path = ( $_GET["t"] == "form" ) ? $_GET["t"] : $_GET["t"]."/back";
		if( file_exists(  "'.$dir.'{$path}/{$_GET["a"]}.php" ) ){
			if( $_GET["t"] == "form" ){
				if( $id != "" ){
					$info = (array) $wpdb->get_row("SELECT * FROM {$pf}{$mod} WHERE id = \'{$id}\' ");
					extract( $info );
					$data = preg_replace("/[\r\n|\n|\r]+/", "<br>", $data);
					$_data = (array) json_decode($data); extract($_data);
					echo \'<input type="hidden" name="id" value="\'.$id.\'" />\';
				}
			}else{
				foreach ($_POST as $key => $value) {
					if( strpos("_".$key, "img") !== false ){
						$_POST[ $key ] = upload( $_POST[ $key ], md5( time() ).".png");
					}
				}
			}
			extract($_POST);
			include "'.$dir.'{$path}/{$_GET["a"]}.php";
			if( $_GET["t"] == "ajax" && $_GET["a"] != "list" ){
				if( $res !== false ) {
					listo([ "msg" => "Procesado Exitosamente!", "status" => true, "res" => $res, "sql" => $sql, "post" => $_POST ]);
				}else{
					listo([ "msg" => "Error procesando", "status" => false, "res" => $res, "sql" => $sql, "post" => $_POST ]);
				}
			}
		}else{
			if( $_GET["a"] == "list" ){
				echo json_encode(["data"=>[]]);
			}else{
				if( $_GET["t"] == "form" ){
					echo "El archivo [ {$_GET["a"]}.php ] no se encuentra en la carpeta [ modulos/'.$mod.'/form/ ] ";
				}else{
					echo "El archivo [ {$_GET["a"]}.php ] no se encuentra en la carpeta [ modulos/'.$mod.'/ajax/back/ ] ";					
				}
			}
		}
		die();
	} );');

	eval('add_action( "wp_ajax_vlz_'.$mod.'_ajax", function(){
		session_start();
		extract($_POST); 
		global $wpdb; global $vlz;
		$MODULO = "'.$mod.'"; 
		extract($vlz);
		$m = [ "gen" => "'.$gen.'", "mod" => "'.$mod.'", "sub" => "'.$sub.'", "plu" => "'.$plu.'", "sin" => "'.$sin.'" ];
		extract($m);
		$tabla_name = "{$pf}{$mod}"; 
		$campos_db = get_campos_db( $tabla_name );
		$path = "/ajax/front";
		if( file_exists(  "'.$dir.'{$path}/{$_GET["a"]}.php" ) ){
			include "'.$dir.'{$path}/{$_GET["a"]}.php";
		}else{
			echo "El archivo [ {$_GET["a"]}.php ] no se encuentra en la carpeta [ modulos/'.$mod.'/ajax/front/ ] "."'.$dir.'{$path}/{$_GET["a"]}.php";
		}
		die();
	} );');

	eval('add_action( "wp_ajax_nopriv_vlz_'.$mod.'_ajax", function(){
		session_start();
		extract($_POST); 
		global $wpdb; global $vlz;
		$MODULO = "'.$mod.'"; 
		extract($vlz);
		$m = [ "gen" => "'.$gen.'", "mod" => "'.$mod.'", "sub" => "'.$sub.'", "plu" => "'.$plu.'", "sin" => "'.$sin.'" ];
		extract($m);
		$tabla_name = "{$pf}{$mod}"; 
		$campos_db = get_campos_db( $tabla_name );
		$path = "/ajax/front";
		if( file_exists(  "'.$dir.'{$path}/{$_GET["a"]}.php" ) ){
			include "'.$dir.'{$path}/{$_GET["a"]}.php";
		}else{
			echo "El archivo [ {$_GET["a"]}.php ] no se encuentra en la carpeta [ modulos/'.$mod.'/ajax/front/ ] "."'.$dir.'{$path}/{$_GET["a"]}.php";
		}
		die();
	} );');


	eval('add_shortcode( "vlz_'.$mod.'", function( $atts ) {
		session_start();
		extract($_POST); 
		global $wpdb; global $vlz;
		$m = [ "gen" => "'.$gen.'", "mod" => "'.$mod.'", "sub" => "'.$sub.'", "plu" => "'.$plu.'", "sin" => "'.$sin.'" ];
		extract($m);
		extract($vlz);
		extract($atts);
		echo "<script> var AJAX = \''.$url_ajax.'?action=vlz_'.$mod.'_ajax\';  </script>";
		$tabla_name = "{$pf}{$mod}"; 
		$campos_db = get_campos_db( $tabla_name );
		if( file_exists(  "'.$dir.'/shortcode/{$sc}/init.php" ) ){
		    	ob_start();
		    		echo "<script> var HOME = \"{$vlz["h"]}\"; </script>";
		    		echo "<link rel=\'stylesheet\' type=\'text/css\' href=\'{$vlz["p"]}/lib/fontawesome/css/all.css\' >";
					include "'.$dir.'/shortcode/{$sc}/init.php";
		    		if( file_exists( "'.$dir.'/shortcode/{$sc}/css.css" ) ){
		    			echo "<link rel=\'stylesheet\' type=\'text/css\' href=\'{$vlz["p"]}/res/css/shortcode.css?v='.time().'\' >";
		    			echo "<link rel=\'stylesheet\' type=\'text/css\' href=\'{$vlz["p"]}/modulos/'.$mod.'/shortcode/{$sc}/css.css?v='.time().'\' >";
		    		}
		    		if( file_exists( "'.$dir.'/shortcode/{$sc}/js.js" ) ){
						echo "<script type=\'text/javascript\' src=\'{$vlz["p"]}/modulos/'.$mod.'/shortcode/{$sc}/js.js?v='.time().'\' ></script>";
		    		}
				$HTML = ob_get_clean();
			}else{
				$HTML = "El archivo [ {$sc}/init.php ] no se encuentra en la carpeta [ modulos/'.$mod.'/shortcode/ ] ";
			}
        return $HTML;
    } );');

?>