<?php 
    /*
        Template Name: Reclamos
    */

 	// wp_enqueue_style('finalizar', getTema()."/css/finalizar.css", array(), '1.0.0');
	// wp_enqueue_style('finalizar_responsive', getTema()."/css/responsive/finalizar_responsive.css", array(), '1.0.0');

	wp_enqueue_script('finalizar', getTema()."/js/reclamos.js", array("jquery"), '1.0.0');

	get_header();

?>
<div class="km-ficha-bg" style="background-image:url(<?php echo getTema(); ?>/images/new/km-ficha/km-bg-ficha.jpg);">
	<div class="overlay"></div>
</div>
<div class="container">
	<section id="reclamos" style="padding-top:0px!important;">
	<form id="frm_reclamo" method="post" action="#">
		<div class="km-box-form">
			<header class="text-center">
				<h1>Libro de Reclamaciones</h1>
				<p>Formulario disponible sólo para reclamos/quejas relacionados a los servicios.</p>
				<p>Atenci&oacute;n al cliente <?php echo get_region('telefono'); ?>.</p>
				<hr>
			</header>
			<article class="row content-placeholder text-left">
				<div class="col-sm-12">
					<h4><i class="fa fa-user"></i>  Datos del Cliente</h4>
				</div>
				<div class="col-sm-6">
					<div class="label-placeholder">
						<label>Nombre</label>
						<input type="text" data-charset="xlf" name="nombre" value="" class="input-label-placeholder social_firstname solo_letras" maxlength="20">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="label-placeholder">
						<label>Apellido</label>
						<input type="text" data-charset="xlf" name="apellido" value="" class="input-label-placeholder social_lastname solo_letras"  maxlength="20">
					</div>
				</div>

				<div class="col-sm-4">
					<div class="label-placeholder" style="width: 49%;display:inline-block;">
						<label class="hidden">Doc. de Identidad</label>
						<select class="km-datos-estado-opcion km-select-custom" name="dni_tipo" style="border-right: 0px;">
							<option value="DNI">DNI</option>
							<option value="PASAPORTE">Pasaporte</option>
						</select>
					</div>
					<div class="label-placeholder" style="width: 49%;display:inline-block;" >
						<label>N&uacute;mero</label>
						<input type="text" data-charset="num" name="dni_numero" value="" class="input-label-placeholder" maxlength="20">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="label-placeholder">
						<label>Tel&eacute;fono Fijo / Celular</label>
						<input type="text" data-charset="num" name="telefono" value="" class="input-label-placeholder" maxlength="20">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="label-placeholder">
						<label>Correo electrónico</label>
						<input type="email" name="email"  maxlength="250" data-charset="cormlfnum" autocomplete="off" type='text' value="" class="social_email input-label-placeholder">
					</div>
				</div>

				<div class="col-sm-12">
					<div class="label-placeholder">
						<label><?php echo get_region('direccion'); ?></label>
						<input type="text" name="direccion"  maxlength="250" data-charset="cormlfnum" autocomplete="off" type='text' value="" class="social_email input-label-placeholder">
					</div>
				</div>
				 
				<div class="col-sm-4">
					<div class="label-placeholder">
						<label>Departamento</label>
						<select class="km-datos-estado-opcion km-select-custom" name="departamento" style="border-right: 0px;">
							<option value="DNI">Departamento</option>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="label-placeholder">
						<label><?php echo get_region('estado'); ?></label>
						<select class="km-datos-estado-opcion km-select-custom" name="estado" style="border-right: 0px;">
							<option value="">Selección de <?php echo get_region('estado'); ?></option>
							<?php
								global $wpdb;
							    $estados = $wpdb->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY name ASC");
							    $str_estados = "";
							    foreach($estados as $estado) { 
							        $str_estados .= "<option value='".$estado->id."'>".$estado->name."</option>";
							    } 
							    echo $str_estados = utf8_decode($str_estados);
							?>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="label-placeholder">
						<label><?php echo get_region("Localidad")." / ".get_region("Barrio"); ?></label>
						<select class="km-datos-municipio-opcion km-select-custom" name="municipio" style="border-right: 0px;">
							<option value="">Selección de <?php echo get_region("Barrio"); ?></option>
						</select>
					</div>
				</div>
				 
				<div class="km-registro-checkbox col-sm-8 col-md-5">
					<label class="pull-left">¿Eres menor de edad?</label>
					<div class="col-sm-3">					
						<div class="radio">
						  <label>
						    <input type="radio" name="menor_edad" value="SI" > SI
						  </label>
						</div>
					</div>
					<div class="col-sm-3">					
						<div class="radio">
						  <label>
						    <input type="radio" name="menor_edad" value="NO" > NO
						  </label>
						</div>
					</div>
					
				</div>
			</article>
			<article class="row content-placeholder">
				<hr>
				<div class="col-sm-12  text-left">
					<h4><i class="fa fa-user"></i>  Datos de la Reclamación</h4>
				</div>

				<div class="col-sm-4">
					<div class="label-placeholder">
						<select class="km-datos-municipio-opcion km-select-custom" name="tipo" style="border-right: 0px;">
							<option value="">Selecciona un tipo</option>
							<option value="reclamo">Reclamo</option>
							<option value="queja">Queja</option>
						</select>
					</div>				
				</div>				
				<div class="col-sm-4">
					<div class="label-placeholder">
						<select class="km-datos-municipio-opcion km-select-custom" name="relacionado_a" style="border-right: 0px;">
							<option value="">Relacionado a</option>
							<option value="producto">Producto</option>
							<option value="servicio">Servicio</option>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="label-placeholder">
						<label>Nº Pedido</label>
						<input type="text" name="pedido"  maxlength="250" data-charset="cormlfnum" autocomplete="off" type='text' value="" class="social_email input-label-placeholder">
					</div>
				</div>
				<div class="col-sm-8">
					<div class="label-placeholder">
						<label><small>Descripción del producto o servicio</small></label>
						<input type="text" name="descripcion"  maxlength="250" data-charset="cormlfnum" autocomplete="off" type='text' value="" class="social_email input-label-placeholder">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="label-placeholder">
						<label>Proveedor</label>
						<input type="text" name="proveedor"  maxlength="250" data-charset="cormlfnum" autocomplete="off" type='text' value="" class="social_email input-label-placeholder">
					</div>
				</div>
				
				<div class="col-sm-8" style="padding:0px">
					<div class="col-sm-4" >
						<div class="km-fecha-nacimiento">
							<input type="text" name="date_compra" id="date_compra" placeholder="Fecha de Compra" class="date_birth" style="padding:25px 0 16px 25px" readonly>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="km-fecha-nacimiento">
							<input type="text" name="date_consumo" id="date_consumo" placeholder="Fecha de Comsumo" class="date_birth"  style="padding:25px 0 16px 25px" readonly>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="km-fecha-nacimiento">
							<input type="text" name="date_vence" id="date_vence" placeholder="Fecha de Vencimiento" class="date_birth"  style="padding:25px 0 16px 25px" readonly>
						</div>
					</div>
				</div>

				<div class="col-sm-4">
					<div class="label-placeholder">
						<label>Nº de Lote</label>
						<input type="text" name="lote"  maxlength="250" data-charset="cormlfnum" autocomplete="off" type='text' value="" class="social_email input-label-placeholder">
					</div>
				</div>
				<div class="col-sm-8">
					<div class="label-placeholder">
						<label>Detalle del Reclamo / Queja, según indica el cliente</label>
						<textarea name="detalle" style="width: 100%;height: 200px;margin: 40px 0;resize: none;"
						placeholder="Detalle del Reclamo / Queja, según indica el cliente">
							
						</textarea>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="label-placeholder">
						<label>Pedido del cliente</label>
						<textarea name="pedido_cliente" style="width: 100%;height: 200px;margin: 40px 0;resize: none;"
						placeholder="Pedido del cliente">
							
						</textarea>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="label-placeholder">
						<label>Monto Reclamado ( <?php echo get_region('mon_der'); ?> )</label>
						<input type="text" name="monto"  maxlength="250" data-charset="num" autocomplete="off" type='text' value="" class="social_email input-label-placeholder">
					</div>
				</div>
			</article>
			<article class="row text-left" style="margin-top:20px;">
				<div class="col-sm-12">
					<dir class="alert alert-info">					
						<p> <strong>Reclamo:</strong> Disconformidad relacionada a los productos y/o servicios. </p>
						<p> <strong>Queja:</strong> Disconformidad no relacionada a los productos y/o servicios; o, malestar o descontento a la atención al público.</p>
					</dir>
					<hr>
				</div>
				<div class="col-sm-8">				
					<div class="checkbox">
					    <label>
					      <input type="checkbox" name='terminos'> Declaro ser el titular del servicio y acepto el contenido del presente formulario manifestando bajo Declaración Jurada la veracidad de los hechos descritos.
					    </label>
					</div>
				</div>
				<div class="col-sm-4" style="margin-bottom:20px!important;">	
					<button class="km-btn" id="reclamo_enviar">ENVIAR</button>
				</div>

				<div class="col-md-12 alert hidden" id="noti"></div>

			</article>
		</div>

		</form>
	</section>
</div>

<?php get_footer(); ?>