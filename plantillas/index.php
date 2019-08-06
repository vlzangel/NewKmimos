<?php
	include dirname(__DIR__)."/wp-load.php";


	$path_functions = dirname(__FILE__)."/";
	$directorio = opendir( $path_functions );
	while ($archivo = readdir($directorio)) {
	    if ( is_dir($archivo) && $archivo != "." && $archivo != ".." ) {
	    	for ($i=1; $i <= 3; $i++) { 
	        	echo "<a href='".$archivo."/".$i.".php' target='_blank'>".$archivo." (".$i.")</a><br>";
	    	}
        	echo "<br>";
	    }
	}
?>	