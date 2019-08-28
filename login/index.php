<?php
	include dirname(__DIR__).'/wp-load.php';

	if( is_user_logged_in()  ){
		header("location: ".get_home_url()."/wp-admin/admin.php?page=reporte_cupones" );
	}
?>
<script type="text/javascript" src="<?= getTema().'/js/jquery.min.js' ?>"></script>
<script type="text/javascript">
	var HOME = '<?= getTema().'/' ?>';
	var ADMIN = '<?= get_home_url().'/wp-admin/admin.php?page=reporte_cupones' ?>';
	jQuery( document ).ready(function() {

		jQuery("#form_login").on("submit", function(e){
			e.preventDefault();
			
			var btn = jQuery('#login_submit');
	        	btn.html('PROCESANDO...');

			jQuery.post( 
		        HOME+"/procesos/login/login.php", 
		        {
		            usu: jQuery("#form_login #usuario").val(),
		            clv: jQuery("#form_login #clave").val(),
		            proceso: jQuery("#form_login #proceso").val()
		        },
		        function( data ) {

		            console.log( data );

		            location.href = ADMIN;

		            btn.html('INICIAR SESIÓN AHORA');

		        },
		        "json"
		    );	
		    

		});

	});
</script>

<form id="form_login" autocomplete="off">
	
	<input type="hidden" id="proceso" name="proceso" value="" />

	<div class="km-box-form">
		<div class="content-placeholder">
			<div class="label-placeholder">
				<label>Correo electrónico</label>
				<input type="text" id="usuario" placeholder="Usuario &oacute; Correo El&eacute;ctronico" class="input-label-placeholder">
			</div>
			<div class="label-placeholder">
				<label>Contraseña</label>
				<input type="password" id="clave" placeholder="Contraseña" class="input-label-placeholder" autocomplete="off">
			</div>
		</div>
	</div>
	<input id="enviar" type="submit" value="INICIAR SESIÓN AHORA" />

</form>

<style type="text/css">
	form#form_login {
	    margin: 50px auto;
	    display: block;
	    width: 500px;
	}
	label {
	    display: block;
	    padding: 10px 0px;
	    font-weight: 600;
	}

	input {
	    width: 100%;
	    border: solid 1px #CCC;
	    padding: 10px;
	}

	#enviar {
	    width: 100%;
	    padding: 10px;
	    margin-top: 15px;
	}
</style>