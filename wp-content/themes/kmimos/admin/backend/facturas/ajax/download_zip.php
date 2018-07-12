<?php
	
	$fact_selected = $_POST['fact_selected'];
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
	
	$name = time().".zip";

	$filename = "{$raiz}/wp-content/uploads/temp/".$name;

	$f = [];
	foreach ($fact_selected as $val) {			
 		$r =  "{$raiz}/wp-content/uploads/facturas/{$val}.pdf";
 		if( file_exists($r) ){
			$f["{$val}.pdf"] = $r;
		}
 		$xml =  "{$raiz}/wp-content/uploads/facturas/{$val}.xml";
 		if( file_exists($xml) ){
			$f["{$val}.xml"] = $xml;
		}
	}

	$sts = create_zip( $f, $filename );
	if( $sts ){
		$r = json_encode(['estatus'=>'listo', 'url'=> "/wp-content/uploads/temp/".time().".zip" ]);
		print_r($r);
 	}
 	else {
		$r = json_encode(['estatus'=>'error', 'url'=>'']);
		print_r($r);
 	}

	function create_zip($files = array(),$destination = '',$overwrite = false) {
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
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
				return false;
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
			return file_exists($destination);
		}
		else
		{
			return false;
		}
	}