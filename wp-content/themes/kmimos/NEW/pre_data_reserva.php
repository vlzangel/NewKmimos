<?php
	
    $precios = "";
    
	$adicionales = unserialize($cuidador->adicionales);
	$precargas = array();
	$id_seccion = 'MR_'.get_the_ID()."_".md5($USER_ID);
    if( isset($_SESSION[$id_seccion] ) ){
    	$cupos_menos = $_SESSION[$id_seccion]["variaciones"]["cupos"];
    	$ini = strtotime( $_SESSION[$id_seccion]["fechas"]["inicio"] );
    	$fin = strtotime( $_SESSION[$id_seccion]["fechas"]["fin"] );
    	foreach ($cupos as $value) {
    		$xfecha = strtotime( $value->fecha );
    		if( $ini >= $xfecha && $xfecha <= $fin ){
    			$value->cupos -= $cupos_menos;
    			$value->full = 0;
    			$value->no_disponible = 0;
    		}
    	}
        $HTML .= "
            <a href='".getTema()."/procesos/perfil/update_reserva.php?b=".get_the_ID()."_".md5($USER_ID)."' class='theme_button btn_modificar'>
                Salir de modificar reserva
            </a>
        ";
        $busqueda["checkin"] = date("d/m/Y", strtotime($_SESSION[$id_seccion]["fechas"]["inicio"]) );
        $busqueda["checkout"] = date("d/m/Y", strtotime($_SESSION[$id_seccion]["fechas"]["fin"]) );

        $precargas["tamanos"] = $_SESSION[$id_seccion]["variaciones"];
        if( isset($_SESSION[$id_seccion]["transporte"][0])){
        	$precargas["transp"] = $_SESSION[$id_seccion]["transporte"][0];
        }
        $precargas["adicionales"] = $_SESSION[$id_seccion]["adicionales"];
    }
    if( $tipo == "hospedaje" ){
    	$precios = getPrecios( unserialize($cuidador->hospedaje), $precargas["tamanos"], unserialize($cuidador->tamanos_aceptados) );
    }else{
    	$precios = getPrecios( $adicionales[ $tipo ], $precargas["tamanos"], unserialize($cuidador->tamanos_aceptados) );
    } 
	$transporte = getTransporte($adicionales, $precargas["transp"]);
	if( $transporte != "" ){
		$transporte = '
			<div class="km-service-title"> TRANSPORTACI&Oacute;N </div>
			<div class="km-services">
				<select id="transporte" name="transporte" class="km-input-custom"><option value="">SELECCIONE UNA OPCI&Oacute;N</option>'.$transporte.'</select>
			</div>
		';
	}
	$adicionales = getAdicionales($adicionales, $precargas["adicionales"]);
	if( $adicionales != "" ){
		$adicionales = '
			<div class="km-service-title"> SERVICIOS ADICIONALES </div>
			<div id="adicionales" class="km-services">
				'.$adicionales.'
			</div>
		';
	}

	$bloquear_adicionales = false;
	$infoGatos = '';
	include 'mensajes_reserva.php';

	$paquetes = [
		"1 semena",
		"1 mes",
		"2 meses",
		"3 meses"
	];

	$bloq_checkout = '';
	if( $tipo == "paseos" ){
		$PAQUETE = "var PAQUETE = '".$busqueda["paquete"]."';";
		$bloq_checkout = 'disabled';
	}else{
		$PAQUETE = "var PAQUETE = '';";
	}

	$super_admin = (  $_SESSION['admin_sub_login'] != 'YES' ) ? 'No': 'Si';
		
	$descripcion = $wpdb->get_var("SELECT post_excerpt FROM wp_posts WHERE ID = {$servicio_id}");

	preg_match_all("#-(.*?)\n#i", "-".$descripcion, $matches_1);
	preg_match_all("#<small>(.*?)</small>#", $descripcion, $matches_2);
	$descripcion_1 = $matches_1[1][0];
	$descripcion_2 = $matches_2[1][0];

	$_adicionales = '<div id="contenedor-adicionales" class="contenedor-adicionales">'.$adicionales.'</div>';
	if( $bloquear_adicionales ){
		$_adicionales = '<div style="display: none;" id="contenedor-adicionales" class="contenedor-adicionales">'.$adicionales.'</div>';
	}

	$dias_str = '';
	if( $tipo == "paseos" ){
	    $dias = [
	    	"lunes" => "Lunes",
	    	"martes" => "Martes",
	    	"miercoles" => "Miercoles",
	    	"jueves" => "Jueves",
	    	"viernes" => "Viernes",
	    	"sabado" => "Sábado",
	    	"domingo" => "Domingo"
	    ];
	    foreach ($dias as $key => $value) {
	    	$letra = substr( $value, 0, 1);
	    	$checked = ( in_array($key, $_SESSION['busqueda']['dias']) ) ? "checked": "";
	    	$dias_str .= 
	    	'	<label class="input_check_box" title="'.$value.'" for="'.$key.'">'.
			'		<input type="checkbox" id="'.$key.'" name="dias[]" value="'.$key.'" '.$checked.' />'.
			'		<span>'.$letra.'</span>'.
			'		<div class="top_check"></div>'.
			'	</label>'
	    	;
	    }
	    $dias_str = '<div class="dias_container">'.$dias_str.'</div>';
	}	

	$bloq_checkout_str = '';

	if( $bloq_checkout != "" ){
		$bloq_checkout_str = '
			<div style="margin-bottom: 15px; font-size: 15px;" class="msg_bloqueador_no_valido">
				Estimado usuario el <strong>checkout</strong> será establecido de manera automatica para coincidir con
				el tiempo del paquete seleccinado de <strong>'.$paquetes[ $busqueda["paquete"]-1 ].'</strong>.
			</div>
		';
	}

	$precios = $bloq_checkout_str.'
		<div class="km-dates-step" style="margin-bottom: 5px;">
			<div class="km-ficha-fechas">
				<input type="text" id="checkin" name="checkin" placeholder="DESDE" value="'.$busqueda["checkin"].'" class="date_from" readonly />
				<input type="text" id="checkout" name="checkout" placeholder="HASTA" value="'.$busqueda["checkout"].'" readonly '.$bloq_checkout.' />
			</div>
		</div>

		'.$dias_str.'
		'.$msg_mismo_dia.'
		'.$msg_bloqueador.'
		'.$msg_bloqueador_madrugada.'
		'.$infoGatos.'

		<div id="bloque_info_servicio" class="km-content-step '.$bloquear.' '.$bloquear_madrugada.'">
			<div class="km-content-new-pet">
				'.$precios.'
				<div class="km-services-content">
					<div class="contenedor-adicionales">'.$transporte.'</div>
					'.$_adicionales.'
				</div>

				<div class="km-services-total km-total-calculo">
					<div class="valido">
						<span class="km-text-total">TOTAL</span>
						<span class="km-price-total">$0.00</span>
					</div>
					<div class="invalido">
						
					</div>
				</div>

			</div>
		</div>

		<a href="#" id="reserva_btn_next_1" class="km-end-btn-form km-end-btn-form-disabled disabled vlz_btn_reservar">
			<span>SIGUIENTE</span>
		</a>
	';
	if( $msg_bloqueador_no_valido != "" ){
		$precios = $msg_bloqueador_no_valido;
	}

	if( $_SESSION["wlabel"] == "petco" ){
		$HTML .= "
			<script type='text/javascript'>
			    window._adftrack.push({
			        pm: 1453019,
			        divider: encodeURIComponent('|'),
			        pagename: encodeURIComponent('MX_Kmimos_Reservar_180907')
			    });
			</script>
			<noscript>
			    <p style='margin:0;padding:0;border:0;'>
			        <img src='https://a2.adform.net/Serving/TrackPoint/?pm=1453019&ADFPageName=MX_Kmimos_Reservar_180907&ADFdivider=|' width='1' height='1' alt='' />
			    </p>
			</noscript>
		";
	}
?>