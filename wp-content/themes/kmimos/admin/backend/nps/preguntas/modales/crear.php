 
<article class="input_container">

	<form id="crear_campana">
		<div class="col-md-12">
			<label>Nombre</label>
			<input class="form-control" type="text" name="nombre" placeholder="Titulo de la campa&ntilde;a" required>
		</div>
		<div class="col-md-12">
			<label>Encuesta</label>
			<input class="form-control" type="text" name="pregunta" placeholder="¿Qué probabilidades hay de que recomiendes a un amigo o colega?" required>
		</div>
		<div class="col-md-12">
			<label>ID Campa&ntilde;a;</label>
			<input class="form-control" type="text" name="remitentes" placeholder="83843bb16df558d3dd25d2d3374a4ff7" required>
		</div>
		<div class="col-md-12">
			<label>Fecha de Inicio</label>
			<input class="form-control disabled" type="date" name="fecha_ini" value="<?php date('Y-m-d'); ?>" required style="margin:0px!important">
		</div>

		<div class="botones_container text-right">
			<button type="submit" id="crear_campana_submit" class="btn btn-success">  Guardar</button>
		</div>
	</form>

</article>