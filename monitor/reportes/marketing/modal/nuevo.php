
<div class="modal fade" tabindex="-1" role="dialog" id="nuevo">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Nuevo Registro</h4>
			</div>
			<div class="modal-body">
				<form id="frm_nuevo">

					<input type="hidden" class="form-control" name="id" id="id" value='' >
					<div class="form-group">
						<label for="exampleInputEmail1">Plataforma</label>
						<select class="form-control" id="plataforma" name="plataforma">
							<?php foreach ($plataformas as $plataforma) { ?>
								<option value="<?php echo $plataforma['name']; ?>" > 
									<?php echo $plataforma['name']; ?> 
								</option> 
							<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Tipo de Usuario</label>
						<select class="form-control" id="tipo" name="tipo">
							<option value="cliente" >Cliente</option> 
							<option value="cuidador" >Cuidador</option> 
							<option value="cliente_cuidador" >Cliente y Cuidador</option> 
						</select>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Fecha de Campaña</label>
						<input type="date" class="form-control" name="fecha" id="fecha" placeholder="Fecha">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Nombre de Campaña</label>
						<input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre de Campa&natilde;a">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Costo total</label>
						<input type="text" class="form-control" name="costo" id="costo" placeholder="$ 0,00">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Canal de mercadeo</label>
						<input type="text" class="form-control" name="canal" id="canal" placeholder="Facebook">
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
				<button id="guardar" type="button" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
			</div>
		</div>
	</div>
</div>