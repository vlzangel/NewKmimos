<?php

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");
    include($raiz."/wp-load.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );



	$fact_selected = $_POST['fact_selected'];

	$name = time().".zip";

	$filename = "{$raiz}/wp-content/uploads/temp/".$name;

	$f = [];
	$debug = [];

	if( $fact_selected == 'all' || $fact_selected == 'pdf' ){

		$datos = $db->get_results('SELECT * FROM facturas');
		foreach ($datos as $key => $value) {
			$val = $value->reserva_id."_".$value->numeroReferencia;

	 		$r =  "{$raiz}/wp-content/uploads/facturas/{$val}.pdf";
	 		if( file_exists($r) ){
				$f["{$val}.pdf"] = $r;
			}
			if( $fact_selected != 'pdf' ){			
		 		$xml =  "{$raiz}/wp-content/uploads/facturas/{$val}.xml";
		 		if( file_exists($xml) ){
					$f["{$val}.xml"] = $xml;
				}
				$debug[] = $xml;
			}

			$debug[] = $r;

		}

	}else{	
		foreach ($fact_selected as $val) {			
	 		$r =  "{$raiz}/wp-content/uploads/facturas/{$val}.pdf";
	 		if( file_exists($r) ){
				$f["{$val}.pdf"] = $r;
			}
	 		$xml =  "{$raiz}/wp-content/uploads/facturas/{$val}.xml";
	 		if( file_exists($xml) ){
				$f["{$val}.xml"] = $xml;
			}

			$debug[] = $r;
			$debug[] = $xml;
		}
	}

	$sts = create_zip( $f, $filename );
	$debug['sts'] = $sts; 
	if( $sts == 1 ){
		$r = json_encode(['estatus'=>'listo', 'url'=> get_home_url()."/wp-content/uploads/temp/".time().".zip" ]);
		print_r($r);
 	}
 	else {
		$r = json_encode(['estatus'=>'error', 'url'=>'', 'test'=> $debug]);
		print_r($r);
 	}




	function create_zip($files = array(),$destination = '',$overwrite = false) {
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return 2; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $key => $file) {
				//make sure the file exists
				if(file_exists($file)) {
					$valid_files[ $key ] = $file;
				}
			}
		}

		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return 3;
			}
			//add the files
			foreach($valid_files as $key => $file) {
				$zip->addFile($file, $key );
			}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
			
			//close the zip -- done!
			$zip->close();
			
			//check to make sure the file exists
			return (file_exists($destination))?1:0;
		}
		else
		{
			return 4;
		}
	}