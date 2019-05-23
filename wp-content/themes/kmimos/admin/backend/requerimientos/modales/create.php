<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

    /*
	$_clientes = $db->get_results("
		SELECT 
			c.ID,
			c.user_email,
			n.meta_value AS nombre,
			a.meta_value AS apellido
		FROM 
			wp_users AS c
		INNER JOIN wp_usermeta AS n ON ( c.ID = n.user_id )
		INNER JOIN wp_usermeta AS a ON ( c.ID = a.user_id )
		INNER JOIN wp_usermeta AS t ON ( c.ID = t.user_id AND t.meta_key = 'wp_capabilities' )
		WHERE
			n.meta_key = 'first_name' AND
			a.meta_key = 'last_name' AND
			t.meta_value LIKE '%subscriber%'
		ORDER BY nombre ASC
	");
	*/

	$_medios = [
		'Zopim',
		'Recibido desde Mx',
		'Llamada',
		'Facebook',
		'Kmibots'
	];
	$medios = '';
	foreach ($_medios as $key => $value) {
		$medios .= '<option value="'.$value.'">'.$value.'</option>';
	}

	$_status = [
		'Pendiente - Por enviar sugerencias',
		'Pendiente - Sugerencias enviadas',
		'Pendiente - Cliente quiere ver más sugerencias',
		'Cancelada - Sin respuesta del cliente',
		'Cancelada - Cliente no ocupara el servicio',
		'Cancelada - Cliente dejará la mascota con un familiar',
		'Cancelada - Cliente viajará con la mascota',
		'Cancelada - Cliente no le agradaron las sugerencias',
		'Cancelada por motivo de salud de la mascota',
		'Cancelada por falta de cuidadores en la localidad',
	];
	$status = '';
	foreach ($_status as $key => $value) {
		$status .= '<option value="'.$value.'">'.$value.'</option>';
	}

	$_atendido_por = [
        0 => "Seleccione...",
        1 => "Nikole Merlo",
        2 => "Eyderman Peraza",
        3 => "Leomar Albarrán",
        4 => "Mariana Castello",
        5 => "Yrcel Chaudary",
    ];
	$atendido_por = '';
	foreach ($_atendido_por as $key => $value) {
		$atendido_por .= '<option value="'.$key.'">'.$value.'</option>';
	}

?>
<form id="form_new_requerimiento" style="overflow: hidden;">
	
	<div class="form-group">
		<div class="row">
	    	<div class="col-6">
    			<label for="contacto"> Último Contacto </label>
    			<input type="date" id="contacto" name="contacto" value="<?= date("Y-m-d") ?>" class="form-control" style="margin-bottom: 5px;" disabled />
    		</div>
	    	<div class="col-6">
    			<label for="atendido_por">Atendido por</label>
				<select class="form-control" id="atendido_por" name="atendido_por" required >
					<?= $atendido_por ?>
				</select>
    		</div>
    	</div>
	</div>

	<div class="form-group">
    	<label for="medio">Medio de Contacto</label>
		<select class="form-control" id="medio" name="medio" required >
			<option value="">Seleccione un medio...</option>
			<?= $medios ?>
		</select>
	</div>

	<div class="form-group">
		<div class="row">
	    	<div class="col-4">
    			<label for="cliente"><i class="fa fa-search" aria-hidden="true"></i> Nombre</label>
    			<input type="text" id="cliente_nonbre" class="form-control" style="margin-bottom: 5px;" />
    		</div>
	    	<div class="col-4">
    			<label for="cliente"><i class="fa fa-search" aria-hidden="true"></i> Apellido</label>
    			<input type="text" id="cliente_apellido" class="form-control" style="margin-bottom: 5px;" />
    		</div>
	    	<div class="col-4">
    			<label for="cliente"><i class="fa fa-search" aria-hidden="true"></i> Email</label>
    			<input type="text" id="cliente_email" class="form-control" style="margin-bottom: 5px;" />
    		</div>
    	</div>
	</div>

	<div class="form-group">
		<select class="form-control" id="cliente_id" name="cliente_id" required>
			<option value="">Seleccione un cliente...</option>
		</select>
	</div>

	<div class="form-group">
		<div class="row">
	    	<div class="col-4">
    			<label for="checkin"> Check In </label>
    			<input type="date" id="checkin" name="checkin" class="form-control" style="margin-bottom: 5px;" required />
    		</div>
	    	<div class="col-4">
    			<label for="checkout"> Check Out </label>
    			<input type="date" id="checkout" name="checkout" class="form-control" style="margin-bottom: 5px;" required />
    		</div>
	    	<div class="col-4">
    			<label for="total_noches"> Total Noches</label>
    			<input type="number" id="total_noches" name="total_noches" class="form-control" style="margin-bottom: 5px;" required />
    		</div>
    	</div>
	</div>
	
	<div class="form-group">
    	<label for="status">Status</label>
		<select class="form-control" id="status" name="status" required >
			<option value="">Seleccione el status actual...</option>
			<?= $status ?>
		</select>
	</div>

	<div class="form-group">
		<div class="row">
	    	<div class="col-12">
    			<label for="descripcion">Descripción</label>
    			<textarea class="form-control" id="descripcion" name="descripcion"></textarea>
    		</div>
    	</div>
	</div>

	<div class="form-group">
		<div class="row">
	    	<div class="col-12">
    			<label for="observaciones">Observaciones</label>
    			<textarea class="form-control" id="observaciones" name="observaciones"></textarea>
    		</div>
    	</div>
	</div>


  	<button id="btn_form_create" type="submit" class="btn btn-primary float-right">Crear Requerimiento</button>
</form>

<script type="text/javascript">

	jQuery(document).ready(function() {

		jQuery("#form_new_requerimiento").unbind("submit").bind("submit", function(e){
			e.preventDefault();
			console.log("Enviar form");

			jQuery("#btn_form_create").html("Procesando...");
			jQuery("#btn_form_create").prop("disabled", true);

			jQuery.post(
		        TEMA+'/admin/backend/requerimientos/ajax/create.php',
		        jQuery(this).serialize(),
		        function(data){
		        	console.log( data );

					jQuery("#btn_form_create").html("Crear Requerimiento");
					jQuery("#btn_form_create").prop("disabled", false);
		        }, 'json'
		    );
		    
		});

   	});

	jQuery("#cliente_nonbre").unbind("keyup").bind("keyup", function(e){
		if( jQuery(this).val().length >= 3 ){
			console.log("Buscar");
			jQuery.post(
		        TEMA+'/admin/backend/requerimientos/ajax/get_customers.php',
		        {
		        	nombre: jQuery("#cliente_nonbre").val(),
		        	apellido: jQuery("#cliente_apellido").val(),
		        	email: jQuery("#cliente_email").val()
		        },
		        function(data){
		        	console.log( data );
		        	update_list( data );
		        }, 'json'
		    );
		}
	});

	jQuery("#cliente_apellido").unbind("keyup").bind("keyup", function(e){
		if( jQuery(this).val().length >= 3 ){
			console.log("Buscar");
			jQuery.post(
		        TEMA+'/admin/backend/requerimientos/ajax/get_customers.php',
		        {
		        	nombre: jQuery("#cliente_nonbre").val(),
		        	apellido: jQuery("#cliente_apellido").val(),
		        	email: jQuery("#cliente_email").val()
		        },
		        function(data){
		        	console.log( data );
		        	update_list( data );
		        }, 'json'
		    );
		}
	});
	jQuery("#cliente_email").unbind("keyup").bind("keyup", function(e){
		if( jQuery(this).val().length >= 3 ){
			console.log("Buscar");
			jQuery.post(
		        TEMA+'/admin/backend/requerimientos/ajax/get_customers.php',
		        {
		        	nombre: jQuery("#cliente_nonbre").val(),
		        	apellido: jQuery("#cliente_apellido").val(),
		        	email: jQuery("#cliente_email").val()
		        },
		        function(data){
		        	console.log( data );
		        	update_list( data );
		        }, 'json'
		    );
		}
	});

	function update_list(data){
		var options = '';
		jQuery.each(data.clientes, function(i, v){
			options += "<option value='"+v[0]+"' >"+v[1]+" "+v[2]+" ("+v[3]+")</option>";
		});
		jQuery("#cliente_id").html( options );
	}
</script>

