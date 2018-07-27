<?php

global $wpdb;

$user = wp_get_current_user();

// Datos de Prospecto 
	$prospecto = $wpdb->get_row("SELECT * FROM facturas_aliados WHERE user_id = ".$user->ID );
	function extract_prospecto( $key, $prospecto ){
		if( isset($prospecto->$key) && !empty($key) ){
			return $prospecto->$key;
		}
		return '';
	}
// Estados
	$estados = $wpdb->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY name ASC");
	$str_estados = "";
	$cod_estado = get_user_meta($user->ID, 'billing_state', true);
	$estado_selected = '<option value="">Selección de Estado</option>';
	foreach($estados as $estado) { 
		if( $cod_estado == utf8_decode($estado->id) ){
		    $estado_selected = "<option value='".$estado->id."'>".$estado->name."</option>";
		}else{
		    $str_estados .= "<option value='".$estado->id."'>".$estado->name."</option>";
		}
	} 
	$str_estados = $estado_selected. utf8_decode($str_estados);

// Municipios
	$municipio = get_user_meta($user->ID, 'billing_city', true);
	if( !empty($municipio) ){
		$str_municipio = '<option value="'.$municipio.'">'.$municipio.'</option>';
	}else{
		$str_municipio = '<option value="">Selección de Municipio</option>';
	}


$CONTENIDO = '	
<div class="text-left">
    
    <h1 style="margin: 10px 0px 5px 0px; padding: 0px;">Datos de Comprobante Fiscal Digital</h1>
	
	<label class="lbl-text" style="font-style:italic;">
		Los siguientes datos ser&aacute;n utilizados para emitir su CFDI
	</label>
    <hr style="margin: 5px 0px 15px;">
 
    <input type="hidden" name="accion" value="update-datos-facturacion" />
    <input type="hidden" name="core" value="SI" />
		
	<div class="inputs_containers">
		<div>
			<h4>Datos generales:</h4>
		</div>

		<section>
			<label for="rfc" class="lbl-text">* RFC:</label>
			<label class="lbl-ui">
				<input type="text" id="rfc" name="rfc" value="'.get_user_meta($user->ID, 'billing_rfc', true).'" placeholder="AAA010101AAA" data-valid="requerid" autocomplete="off" min-lenght="12" max-lenght="13">
				<div class="no_error" id="error_rfc" data-id="rfc">Completa este campo.</div>
			</label>
 		</section>
		<section>
			<label for="nombre" class="lbl-text">* Nombre:</label>
			<label class="lbl-ui">
				<input type="text" id="nombre" name="nombre" value="'.get_user_meta($user->ID, 'billing_fullname', true).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_nombre" data-id="nombre">Completa este campo.</div>
			</label>
 		</section>
		<section>
			<label for="regimenFiscal" class="lbl-text">* Regimen Fiscal:</label>
			<label class="lbl-ui">
				<input type="text" id="regimenFiscal" name="regimenFiscal" value="'.extract_prospecto( 'regimenFiscal', $prospecto).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_regimenFiscal" data-id="regimenFiscal">Completa este campo.</div>
			</label>
 		</section>
		<section>
			<label for="folioSat" class="lbl-text">* Folio SAT:</label>
			<label class="lbl-ui">
				<input type="text" id="folioSat" name="folioSat" value="'.extract_prospecto( 'folioSAT', $prospecto).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_folioSat" data-id="folioSat">Completa este campo.</div>
			</label>
 		</section>
		<section>
			<label for="numeroFolioFiscal" class="lbl-text">* Numero Folio Fiscal:</label>
			<label class="lbl-ui">
				<input type="text" id="numeroFolioFiscal" name="numeroFolioFiscal" value="'.extract_prospecto( 'numFolioFiscal', $prospecto).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_numeroFolioFiscal" data-id="numeroFolioFiscal">Completa este campo.</div>
			</label>
 		</section> 		
		<section>
			<label for="serieSat" class="lbl-text">* Serie SAT:</label>
			<label class="lbl-ui">
				<input type="text" id="serieSat" name="serieSat" value="'.extract_prospecto( 'serie', $prospecto).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_serieSat" data-id="serieSat">Completa este campo.</div>
			</label>
 		</section>
		<section>
			<label for="tipoComprobante" class="lbl-text">* Tipo comprobante:</label>
			<label class="lbl-ui">
				<input type="text" id="tipoComprobante" name="tipoComprobante" value="'.extract_prospecto( 'tipoComprobante', $prospecto).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_tipoComprobante" data-id="tipoComprobante">Completa este campo.</div>
			</label>
 		</section>

		<section>
			<label for="tipoComprobante" class="lbl-text">Estatus:</label>
			<label class="lbl-ui">
				<input type="text" value="'.extract_prospecto( 'estatus', $prospecto).'" data-valid="requerid" autocomplete="off" readonly />
				<div class="no_error" id="error_tipoComprobante" data-id="tipoComprobante">Completa este campo.</div>
			</label>
 		</section>

		<div>
			<h4>Firma electrónica SAT:</h4>
		</div>

		<section>
			<label for="fielCer" class="lbl-text">* Certificado (cer):</label>
			<label class="lbl-ui">
				<input type="text" id="fielCer" name="fielCer" value="'.extract_prospecto( 'fielCer', $prospecto).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_fielCer" data-id="fielCer">Completa este campo.</div>
			</label>
 		</section>
		<section>
			<label for="fielKey" class="lbl-text">* Clave privada (key):</label>
			<label class="lbl-ui">
				<input type="text" id="fielKey" name="fielKey" value="'.extract_prospecto( 'fielKey', $prospecto).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_fielKey" data-id="fielKey">Completa este campo.</div>
			</label>
 		</section>
		<section>
			<label for="fielPass" class="lbl-text">* Contraseña de clave privada:</label>
			<label class="lbl-ui">
				<input type="password" id="fielPass" name="fielPass" value="'.extract_prospecto( 'fielPass', $prospecto).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_fielPass" data-id="fielPass">Completa este campo.</div>
			</label>
 		</section>

		<div>
			<h4>Direcci&oacute;n:</h4>
		</div>

		<section>
			<label for="calle" class="lbl-text">Calle:</label>
			<label class="lbl-ui">
				<input type="text" id="calle" name="calle" value="'.get_user_meta($user->ID, 'billing_calle', true).'" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_calle" data-id="calle">Completa este campo.</div>
			</label>
 		</section>
		<section>
			<label for="cp" class="lbl-text">Código Postal:</label>
			<label class="lbl-ui">
				<input type="text" id="cp" name="cp" value="'.get_user_meta($user->ID, 'billing_postcode', true).'" autocomplete="off" placeholder="Ejemplo: 44580">
				<div class="no_error" id="error_cp" data-id="cp">Completa este campo.</div>
			</label>
 		</section> 		
		<section>
			<label for="noExterior" class="lbl-text"># Exterior:</label>
			<label class="lbl-ui">
				<input type="text" id="noExterior" name="noExterior" value="'.get_user_meta($user->ID, 'billing_noExterior', true).'" autocomplete="off" placeholder="Ejemplo: 2858">
				<div class="no_error" id="error_noExterior" data-id="noExterior">Completa este campo.</div>
			</label>
 		</section>
		<section>
			<label for="noInterior" class="lbl-text"># Interior:</label>
			<label class="lbl-ui">
				<input type="text" id="noInterior" name="noInterior" value="'.get_user_meta($user->ID, 'billing_noInterior', true).'" autocomplete="off" placeholder="Ejemplo: A-1">
				<div class="no_error" id="error_noInterior" data-id="noInterior">Completa este campo.</div>
			</label>
 		</section>
 		<section class="lbl-ui">
			<label for="estado" class="lbl-text">Estado:</label>
			<select class="" name="rc_estado">
				'.$str_estados.'
			</select>
 		</section>
		<section class="lbl-ui">
			<label for="municipio" class="lbl-text">Municipio:</label>
			<select class="" name="rc_municipio">
				'.$str_municipio.'
			</select>
 		</section>
		<section>
			<label for="colonia" class="lbl-text">Colonia:</label>
			<label class="lbl-ui">
				<input type="text" id="colonia" name="colonia" value="'.get_user_meta($user->ID, 'billing_colonia', true).'" autocomplete="off" placeholder="Ejemplo: Jardines del norte">
				<div class="no_error" id="error_colonia" data-id="colonia">Completa este campo.</div>
			</label>
 		</section>
		<section>
			<label for="localidad" class="lbl-text">Localidad:</label>
			<label class="lbl-ui">
				<input type="text" id="localidad" name="localidad" value="'.get_user_meta($user->ID, 'billing_localidad', true).'" autocomplete="off" placeholder="Ejemplo: Guadalajara">
				<div class="no_error" id="error_localidad" data-id="localidad">Completa este campo.</div>
			</label>
 		</section>
		<div>
			<div class="checkbox">
			    <label>
					<input type="checkbox" id="check" data-valid="isChecked">
					<small>Doy fe que los datos suministrados en el presente formulario son correctos y serán utilizados para la facturación del servicio.</small>
					<div class="no_error" id="error_check" data-id="check">Completa este campo.</div>
				</label>
			</div>
		</div>
	</div>

</div>
';

