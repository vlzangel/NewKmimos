<?php
    include_once(dirname(dirname(dirname(__DIR__)))."/recursos/conexion.php");

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
		'No interesado',
		'Sin Respuesta',
		'Solicito Informacion',
		'Atendido por otro medio',
		'Solicito perfiles de cuidadores (Requerimiento)'
	];
	$status = '';
	foreach ($_status as $key => $value) {
		$status .= '<option value="'.$value.'">'.$value.'</option>';
	}

	$_atendido_por = [
        '' => "Seleccione...",
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
<form id="form_kmibot" style="overflow: hidden;">
	
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
		<div class="row">
	    	<div class="col-12">
    			<label for="cliente">Email</label>
    			<input type="email" id="cliente_email" name="email" class="form-control" style="margin-bottom: 5px;" required />
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
    			<label for="observaciones">Observaciones</label>
    			<textarea class="form-control" id="observaciones" name="observaciones"></textarea>
    		</div>
    	</div>
	</div>


  	<button id="btn_form_create" type="submit" class="btn btn-primary float-right">Crear</button>
</form>

<script type="text/javascript">

	jQuery(document).ready(function() {

		jQuery("#form_kmibot").unbind("submit").bind("submit", function(e){
			e.preventDefault();
			console.log("Enviar form");

			jQuery("#btn_form_create").html("Procesando...");
			jQuery("#btn_form_create").prop("disabled", true);

			jQuery.post(
		        TEMA+'/admin/backend/kmibot/ajax/get_customers.php',
		        {
		        	email: jQuery("#cliente_email").val()
		        },
		        function(data){
		        	console.log( data );

		        	if( data.respuesta == 'si' ){
						jQuery.post(
					        TEMA+'/admin/backend/kmibot/ajax/create.php',
					        jQuery("#form_kmibot").serialize(),
					        function(data){
					        	console.log( data );

								table.ajax.reload(function(r){
									jQuery("#close_modal").click();  
								}, true);

					        }, 'json'
					    );
		        	}else{
		        		alert("El correo no pertenece a un usuario kmimos");
		        	}

					jQuery("#btn_form_create").html("Crear");
					jQuery("#btn_form_create").prop("disabled", false);

		        }, 'json'
		    );

		    
		});

		jQuery("#checkin").on("change", function(e){
			calcular();
		});

		jQuery("#checkout").on("change", function(e){
			calcular();
		});


   	});

   	function calcular(){
   		var ini = jQuery("#checkin").val();
   		var fin = jQuery("#checkout").val();
   		if( ini != "" && fin != "" ){
   			var fechaInicio = new Date(ini).getTime();
			var fechaFin    = new Date(fin).getTime();
			var diff = fechaFin - fechaInicio;
			dias = parseInt( diff/(1000*60*60*24) );
   			jQuery("#total_noches").val(dias);
   		}
   	}

   	/*
	jQuery("#cliente_email").unbind("change").bind("change", function(e){
		jQuery.post(
	        TEMA+'/admin/backend/requerimientos/ajax/get_customers.php',
	        {
	        	email: jQuery("#cliente_email").val()
	        },
	        function(data){
	        	console.log( data );
	        	// update_list( data );
	        }, 'json'
	    );
	});
	*/

	function update_list(data){
		var options = '';
		jQuery.each(data.clientes, function(i, v){
			options += "<option value='"+v[0]+"' >"+v[1]+" "+v[2]+" ("+v[3]+")</option>";
		});
		jQuery("#cliente_id").html( options );
	}
</script>

