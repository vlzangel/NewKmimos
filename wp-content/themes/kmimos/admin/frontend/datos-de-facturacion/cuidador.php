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

// Regimen fiscal
	$regimen_fiscal = get_user_meta($user->ID, 'billing_regimen_fiscal', true);
	$listado_regimen_fiscal = [
		'PFCAE' => 'Persona física / con actividad empresarial',
		'IFIC' => 'Régimen de Incorporación Fiscal (RIF)',
		'RGLPM' => 'Persona Moral',
	];
	$select_regimen_fiscal = '<option value="">Seleccione su Régimen Fiscal</option>';
	foreach( $listado_regimen_fiscal as $key => $item ){
		$selected  = ($regimen_fiscal == $key)? 'selected' : '';
		$select_regimen_fiscal .= '<option value="'.$key.'" '.$selected.'>'.$item.'</option>';
	}
	$state_Moral = 'hidden';
	$state_noMoral = '';
	if( $regimen_fiscal == 'RGLPM' ){
		$state_Moral = '';
		$state_noMoral = 'hidden';
	}

// Uso CFDI
	$is_moral = ($state_Moral == '')? ' WHERE moral = 1 ' : '' ;
	$uso_cfdi = $wpdb->get_results("SELECT * FROM facturas_uso_cfdi {$is_moral} ORDER BY codigo ASC");
	$str_uso_cfdi = "";
	$cod_uso_cfdi = get_user_meta($user->ID, 'billing_uso_cfdi', true);
	# ********************************
	# Forzar Uso a: Gastos en General 
	$cod_uso_cfdi = 'G03';
	# ********************************
	$uso_selected = '<option value="">Seleccione el uso del CFDI</option>';
	foreach($uso_cfdi as $row) { 
		if( $cod_uso_cfdi == $row->codigo ){
		    $uso_selected .= "<option value='".$row->codigo."' selected>".$row->descripcion."</option>";
		}else{
		    $str_uso_cfdi .= "<option value='".$row->codigo."'>".$row->descripcion."</option>";
		}
	} 
	$str_uso_cfdi = $uso_selected. $str_uso_cfdi;



$CONTENIDO = '	
<div class="text-left">
    
    <h1 style="margin: 10px 0px 5px 0px; padding: 0px;">Datos de Comprobante Fiscal Digital</h1>
	
	<label class="lbl-text" style="font-style:italic;">
		Los siguientes datos ser&aacute;n utilizados para emitir su CFDI
	</label>
    <hr style="margin: 5px 0px 15px;">
 
    <input type="hidden" name="accion" value="update-datos-facturacion" />
    <input type="hidden" name="core" value="SI" />
    <input type="hidden" name="folioSat" value="1" />
		
	<div class="inputs_containers">
		<div>
			<h4>Datos generales:</h4>
		</div>

		<input type="hidden" value="RGLPM" name="regimen_fiscal"/>
		<section class="lbl-ui">
			<label for="regimen_fiscal" class="lbl-text">* Régimen Fiscal:</label>
			<select name="regimen_fiscal">
				'.$select_regimen_fiscal.'
			</select>
 		</section>
		<section data-regimen-fiscal="razon_social" class="'.$state_Moral.'">
			<label for="razon_social" class="lbl-text">* Razon Social: <small class="text-right pull-right"><i class="fa fa-info-circle" aria-hidden="true"></i> Debe colocar el nombre de la empresa</small></label>
			<label class="lbl-ui">
				<input type="text" id="razon_social" name="razon_social" value="'.get_user_meta($user->ID, 'billing_razon_social', true).'" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_razon_social" data-id="razon_social">Completa este campo.</div>
			</label>
 		</section>
		<section data-regimen-fiscal="persona_fisica" class="'.$state_noMoral.'">
			<label for="nombre" class="lbl-text">* Nombre:</label>
			<label class="lbl-ui">
				<input type="text" id="nombre" name="nombre" value="'.get_user_meta($user->ID, 'billing_first_name', true).'" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_nombre" data-id="nombre">Completa este campo.</div>
			</label>
 		</section>
		<section data-regimen-fiscal="persona_fisica" class="'.$state_noMoral.'">
			<label for="apellido_paterno" class="lbl-text">* Apellido Paterno:</label>
			<label class="lbl-ui">
				<input type="text" id="apellido_paterno" name="apellido_paterno" value="'.get_user_meta($user->ID, 'billing_last_name', true).'" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_apellido_paterno" data-id="apellido_paterno">Completa este campo.</div>
			</label>
 		</section>
		<section data-regimen-fiscal="persona_fisica" class="'.$state_noMoral.'">
			<label for="apellido_materno" class="lbl-text">* Apellido Materno:</label>
			<label class="lbl-ui">
				<input type="text" id="apellido_materno" name="apellido_materno" value="'.get_user_meta($user->ID, 'billing_second_last_name', true).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<div class="no_error" id="error_apellido_materno" data-id="apellido_materno">Completa este campo.</div>
			</label>
 		</section>

		<section>
			<label for="rfc" class="lbl-text">* RFC: </label>
			<label class="lbl-ui">
				<input type="text" id="rfc" name="rfc" value="'.extract_prospecto( 'rfc', $prospecto ).'" placeholder="AAA010101AAA" data-valid="requerid" autocomplete="off" minlength="12" maxlength="13">
				<div class="no_error" id="error_rfc" data-id="rfc">Completa este campo.</div>
			</label>
 		</section>	

		<section class="lbl-ui" style="display:none;">
			<label for="uso_cfdi" class="lbl-text">* Uso CFDI:</label>
			<select class="" name="uso_cfdi">
				'.$str_uso_cfdi.'
			</select>
 		</section>


		<section>
			<label for="estatus" class="lbl-text">Estatus:</label>
			<label class="lbl-ui">
				<input class="disabled" type="text" id="estatus" name="estatus" value="'.extract_prospecto( 'estatus', $prospecto ).'" autocomplete="off" readonly>
				<div class="no_error" id="error_estatus" data-id="estatus">Completa este campo.</div>
			</label>
		</section>		

		<div>
			<h4>Firma electrónica SAT:</h4>
		</div>

		<section>
			<label for="fielCer" class="lbl-text">* Certificado (cer):</label>
			<div class="lbl-ui group-input">
				<input readonly type="text" id="fielCer" name="fielCer" value="'.extract_prospecto( 'fielCer', $prospecto).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<button class="btn btn-default" data-selected="file_fielCer" type="button" id="btn-select-cer">Examinar</button>
				
				<input type="file" style="display:none;" id="file_fielCer" data-content="fielCer" name="file_fielCer" accept=".cer" />
				<div class="no_error" id="error_fielCer" data-id="fielCer">Completa este campo.</div>
			</div>
 		</section>

		<section>
			<label for="fielKey" class="lbl-text">* Clave privada (key):</label>
			<label class="lbl-ui group-input">
				<input readonly type="text" id="fielKey" name="fielKey" value="'.extract_prospecto( 'fielKey', $prospecto).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<button class="btn btn-default" data-selected="file_fielKey" type="button" id="btn-select-key">Examinar</button>
				
				<input type="file" style="display:none;" id="file_fielKey" data-content="fielKey" name="file_fielKey" accept=".key"/>
				<div class="no_error" id="error_fielKey" data-id="fielKey">Completa este campo.</div>
			</label>
 		</section>




		<section>
			<label for="fielPass" class="lbl-text">* Contraseña de clave privada (.key):</label>
			<label class="lbl-ui">
				<input type="password" id="fielPass" name="fielPass" value="'.extract_prospecto( 'fielPass', $prospecto).'" data-valid="requerid" autocomplete="off" placeholder="Ejemplo: Pedro Jose">
				<small class="text-left">* La contraseña es confidencial y solo se usará para emitir facturas y de ninguna manera ingresaremos a su perfil del SAT.</small>
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

