<?php 
	/*
		Template Name: Restaurar Contraseña
	*/

	$id_user = get_current_user_id();
	if( $id_user != "" ){
		header("location: ".get_home_url()."/perfil-usuario/?ua=profile");
	}

	if( isset( $_GET['r'] ) ){
        $xuser = $wpdb->get_row("SELECT * FROM wp_users WHERE md5(ID) = '{$_GET['r']}'");

        $pos = strpos($xuser->user_pass, "$");
        $tipo = "viejo";
		$tipo = "nuevo";
		if ($pos === false) {
		    $tipo = "nuevo";
		}

        $_SESSION['kmimos_recuperar'] = array( $xuser->ID, $xuser->user_email, $tipo);
        header("location: ".get_home_url()."/restablecer/");
    }

    if( $_SESSION['kmimos_recuperar'] == "" ){
        header("location: ".get_home_url());
    }

	get_header();

		if(function_exists('PFGetHeaderBar')){PFGetHeaderBar();} ?>

		<div class="pf-blogpage-spacing pfb-top"></div>
		<section role="main" class="blog-full-width" style="overflow: hidden;">
			<div class="pf-container">
				<div class="pf-row">
					<div class="col-lg-12">

						

							<form id="vlz_form_recuperar" class="km-box-form" enctype="multipart/form-data" method="POST" onsubmit="return false;">

								<?php
									$datos = $_SESSION['kmimos_recuperar'];
						            echo "<input type='hidden' name='user_id' value='{$datos[0]}' />";
						            echo "<input type='hidden' name='user_email' value='{$datos[0]}' />";
								?>
								<div class="vlz_modal" id="terminos_y_condiciones" style="display: none;">
									<div class="vlz_modal_interno">
										<div id="vlz_modal_cerrar_registrar" class='vlz_modal_fondo' onclick="jQuery('.vlz_modal').css('display', 'none');"></div>
										<div class="vlz_modal_ventana">
											<div id="vlz_titulo_registro" class="popup-tit">Recuperación</div>
											<div id="vlz_cargando" class="vlz_modal_contenido" style="display: none;">
											</div>
											<div id="vlz_terminos" class="vlz_modal_contenido" style="height: auto;">
												<h1 style="font-size: 15px;">Recuperando, por favor espere...</h1>
											</div>
										</div>
									</div>
								</div>

								<article style="max-width: 600px; display: block; margin: 0px auto;">
									<div class="vlz_parte">
										<div class="popup-tit">Recuperar Contraseña</div>

										<div class="vlz_seccion">

											<h2 class="vlz_titulo_interno" style="font-size: 20px;">Email: <?php echo $datos[1]; ?></h2>

											<div class="label-placeholder">
												<div class="vlz_cell50">
													<input data-title="<strong>Las contraseñas son requeridas y deben ser iguales</strong>" type='password' id='clave_1' name='clave_1' class='' placeholder='Ingrese su nueva contraseña' pattern=".{3,}"  maxlength="20" required>
													<div class='no_error' id='error_clave_1'></div>
												</div>

												<div class="vlz_cell50" style="margin: 20px 0;">
													<input data-title="<strong>Las contraseñas son requeridas y deben ser iguales</strong>" type='password' id='clave_2' name='clave_2' class='' placeholder='Reingrese su nueva contraseña' pattern=".{3,}"  maxlength="20" required>
													<div class='no_error' id='error_clave_2'></div>
												</div>
											</div>

										</div>

										<div class="vlz_contenedor_botones_footer">
											<div class="vlz_bloqueador"></div>
											<input type='button' id="vlz_boton_recuperar" class="km-btn-basic" style=" outline: none; border: none; width: 100%;" value='Recuperar' />
										</div>

									</div>
								</article>

								<script>

									var form = document.getElementById('vlz_form_recuperar');

									function mostrar_error(id){
										jQuery("#error_"+id).html( jQuery("#"+id).attr("data-title") );

										jQuery("#error_"+id).removeClass("no_error");
										jQuery("#error_"+id).addClass("error");
										jQuery("#"+id).addClass("vlz_input_error");
									}

									function quitar_error(id){
										jQuery("#error_"+id).html( "" );

										jQuery("#error_"+id).removeClass("error");
										jQuery("#error_"+id).addClass("no_error");
										jQuery("#"+id).removeClass("vlz_input_error");
									}

									function vlz_validar(){
										var error = 0;
										var clv1 = jQuery("#clave_1").attr("value");
										var clv2 = jQuery("#clave_2").attr("value");

										if( clv1 == "" ){ mostrar_error("clave_1"); error++; }else{ quitar_error("clave_1"); }
										if( clv2 == "" ){ mostrar_error("clave_2"); error++; }else{ quitar_error("clave_2"); }

										if( clv1 != clv2 ){
											jQuery("#error_clave_2").html( jQuery("#clave_2").attr("data-title") );
											jQuery("#error_clave_2").removeClass("no_error");
											jQuery("#error_clave_2").addClass("error");
											jQuery("#clave_2").addClass("vlz_input_error");
											error++;
										}else{
											if( clv1 != "" ){
												quitar_error("clave_2");
											}
										}
										return ( error == 0);
									}

									jQuery("#vlz_boton_recuperar").on("click", function(){
										if( vlz_validar() ){
											jQuery("#vlz_form_recuperar").submit();
										}else{

										}
									});

									jQuery("#vlz_form_recuperar").submit(function(e){
										e.preventDefault();
										jQuery("#terminos_y_condiciones").css("display", "table");
										jQuery("#boton_registrar_modal").css("display", "inline-block");
										var a = "<?php echo get_home_url()."/wp-content/themes/kmimos"."/procesos/login/recuperar_pass.php"; ?>";
										jQuery.post( a, jQuery("#vlz_form_recuperar").serialize(), function( data ) {
											location.href = "<?php echo get_home_url()."/perfil-usuario/?ua=profile"; ?>";
										});
									});
								</script>

							</form>

						</article>
						
					</div>
					
				</div>
			</div>
		</section>
		<div class="pf-blogpage-spacing pfb-bottom"></div> <?php 

	get_footer(); 
?>