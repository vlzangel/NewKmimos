<pre>
<?php

    date_default_timezone_set('America/Mexico_City');
    global $wpdb;
 
    $datos = $wpdb->get_results( "SELECT * FROM facturas_configuracion" );

    $data = [];
    foreach ($datos as $key => $item) {
    	if( $item->codigo == 'cfdi_parametros' ){
    		$data[ $item->codigo ] = unserialize($item->value);
    	}else{
	    	$data[ $item->codigo ] = $item->value;
    	}
    }

?>
</pre>