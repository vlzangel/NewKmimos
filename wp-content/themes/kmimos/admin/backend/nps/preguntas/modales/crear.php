<?php

	extract($_POST);
	$nombre = '';
	$pregunta = '';
	$fecha_inicio = '00/00/0000';
	if( isset($ID) && $ID > 0 ){
		include_once('../lib/nps.php');
		$encuesta = $nps->get_pregunta_byId( $ID );

		$nombre = $encuesta->titulo;
		$pregunta = $encuesta->pregunta;
		$fecha_inicio = $encuesta->fecha_inicio;
	}else{
		$ID = 0;
	}

?> 

<article class="input_container">

	<form id="crear_campana">
		<input type="hidden" name="campana_id" value="<?php echo ($ID > 0)? $ID : 0; ?>">
		<div class="col-md-12">
			<label>Nombre:</label>
			<input class="form-control" type="text" name="nombre" placeholder="Titulo de la campa&ntilde;a" required value="<?php echo $nombre; ?>">
		</div>
		<div class="col-md-12">
			<label>Encuesta:</label>
			<input class="form-control" type="text" name="pregunta" placeholder="¿Qué probabilidades hay de que recomiendes a un amigo o colega?" required value="<?php echo $pregunta; ?>">
		</div>
		<div class="col-md-12">
			<label>Campañas:</label>
			<div class="input-group">
				<select class="form-control" type="text" name="remitentes" >
					<?php echo $_POST['list_campana']; ?>
				</select>
 				<span class="input-group-btn hidden">
					<a href="javascript:;" data-campaing="update" class="btn btn-info" style="height: calc(2.25rem + 2px);">Actualizar</a>
				</span>
			</div>
		</div>
		<div class="col-md-12">
			<label>Fecha de Inicio:</label>
			<input class="form-control disabled" type="date" name="fecha_ini" required style="margin:0px!important" value="<?php echo $fecha_inicio; ?>">
		</div>

		<div class="botones_container text-right">
			<button type="submit" id="crear_campana_submit" class="btn btn-success">  Guardar</button>
		</div>
	</form>

</article>