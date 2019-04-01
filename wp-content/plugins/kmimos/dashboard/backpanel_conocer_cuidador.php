<?php 
require_once('core/ControllerConocerCuidador.php');
$date = getdate();
$desde = date("Y-m-01", $date[0] );
$hasta = date("Y-m-d", $date[0]);
if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
	$desde = (!empty($_POST['desde']))? $_POST['desde']: "";
	$hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
}


$solicitudes = getSolicitud($desde, $hasta);
?>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Información</h4>
			</div>
			<div class="modal-body">
				
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>ID</th>
							<th>Fecha</th>
							<th>Solicitudes disponibles</th>
						</tr>
					</thead>
					<tbody id="contenido"></tbody>
				</table>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="x_panel">
  <div class="x_title">
    <h2>Panel de Control <small>Solicitud de Conocer a Cuidador</small></h2>
    <hr>
    <div class="clearfix"></div>
  </div>
  <div class="col-sm-12">  	
	<!-- Filtros -->
    <div class="row text-right"> 
    	<div class="col-sm-12">
	    	<form class="form-inline" action="<?php echo get_home_url(); ?>/wp-admin/admin.php?page=bp_conocer_cuidador" method="POST">
			  <label>Filtrar:</label>
			  <div class="form-group">
			    <div class="input-group">
			      <div class="input-group-addon">Desde</div>
			      <input type="date" class="form-control" name="desde" value="<?php echo $desde; ?>">
			    </div>
			  </div>
			  <div class="form-group">
			    <div class="input-group">
			      <div class="input-group-addon">Hasta</div>
			      <input type="date" class="form-control" name="hasta" value="<?php echo $hasta ?>">
			    </div>
			  </div>
				<button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>			  
		    </form>
			<hr>  
		</div>
    </div>

  	<?php if( empty($solicitudes) ){ ?>
  		<!-- Mensaje Sin Datos -->
	    <div class="row alert alert-info"> No existen registros </div>
  	<?php }else{ ?>  		
	    <div class="row"> 
	    	<div class="col-sm-12" id="table-container" 
	    		style="font-size: 10px!important;">
	  		<!-- Listado de Conocer Cuidador-->
			<table id="tblConocerCuidador" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons" 
					cellspacing="0" width="100%">
			  <thead>
			    <tr>
					<th>#</th>
					<th># Solicitud</th>
					<th>Fecha</th>
					<th># Noches</th>
					<th>Desde</th>
					<th>Hasta</th>
					<th>Lugar</th>
					<th>Cuando</th>
					<!-- nombre de la(s) mascota -->
					<!-- tamaño -->
					<th>Nombre del cliente</th>
					<th>Apellido del cliente</th>
					<th>Teléfono del cliente</th>
					<th>Correo del cliente</th>
					<th>Pagos Conocer</th>

					<th>Nombre del cuidador</th>
					<th>Apellido del cuidador</th>
					<th>Teléfono del cuidador</th>
					<th>Correo del cuidador</th>


					<th>Donde nos conocio?</th>
					<th>Servicio</th>
					<th># Noches</th>

					<th>Estatus</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $count=0; ?>
			  	<?php foreach($solicitudes['rows'] as $solicitud ){ ?>
 
				  	<?php 
				  		// *************************************
				  		// Buscar Reservas
				  		// *************************************
				  		// $_reserva = get_primera_reserva( $solicitud['Cliente_id'] );
				  		// $reserva = $_reserva['rows'][0];
				  		// $detalle = kmimos_desglose_reserva_data( $reserva['post_parent'], true);
				  		// if( empty($detalle['servicio']['id_orden'] ) ){
						// 	$detalle['servicio']['duracion'] = '';
						// 	$detalle['servicio']['tipo'] = '';
						// }



						$arr_ini = explode('/',	$solicitud['Servicio_desde']);
						$arr_fin = explode('/',	$solicitud['Servicio_hasta']);
						$_ini = $arr_ini[2].'-'.$arr_ini[1].'-'.$arr_ini[0];
						$_fin = $arr_fin[2].'-'.$arr_fin[1].'-'.$arr_fin[0];
						$dias	= (strtotime( $_fin )-strtotime( $_ini ))/86400;
						$dias 	= abs($dias); 
						$diff = floor($dias);		

						$duracion = $diff . ' Noches';



				  		// *************************************
				  		// Cargar Metadatos
				  		// *************************************
						$separador = ' / ';
				  		$cuidador = get_metaCuidador($solicitud['Cuidador_id']);
				  		$cliente = get_metaCliente($solicitud['Cliente_id']);

				  		$cuidador = merge_phone($cuidador);
				  		$cliente = merge_phone($cliente);

				  		switch ( strtolower( $solicitud['Estatus'] ) ) {

				  			case 'pending':
				  				$solicitud['Estatus'] = 'pendiente';
				  				break;

				  			case 'confirmed':
				  			case 'publish':
				  				$solicitud['Estatus'] = 'confirmadas';
				  				break;

				  			case 'cancelled':
				  			case 'draft':
				  				$solicitud['Estatus'] = 'canceladas';
				  				break;
				  		}
				  	?> 
				    <tr>
				    	<th class="text-center"><?php echo ++$count; ?></th>

						<th><?php echo $solicitud['Nro_solicitud']; ?></th>
						<th><?php echo $solicitud['Fecha_solicitud']; ?></th>
						<th><?php echo $duracion; ?></th>

						<th><?php echo $solicitud['Servicio_desde']; ?></th>
						<th><?php echo $solicitud['Servicio_hasta']; ?></th>
						<th><?php echo utf8_encode($solicitud['Donde']); ?></th>
						<th><?php echo $solicitud['Cuando'].' '.$solicitud['Hora']; ?></th>

						<!-- nombre de la(s) mascota -->
						<!-- tamaño -->

						<th><?php echo $cliente['first_name']; ?></th> 
						<th><?php echo $cliente['last_name']; ?></th>
						<th><?php echo $cliente['phone'];?></th>
						<th><?php echo $cliente['email'];?></th>

						<th><a href="#" onclick="ver_info( jQuery(this) )" data-id="<?= $solicitud['Cliente_id'] ?>"> VER INFO </a> </th>

						<th><?php echo $cuidador['first_name']; ?></th>
						<th><?php echo $cuidador['last_name']; ?></th>
						<th><?php echo $cuidador['phone'];?></th>
						<th><?php echo $cuidador['email'];?></th>

						<th><?php echo $cuidador['user_referred'];?></th>
						<!-- <th><?php #echo $detalle['servicio']['tipo'];?></th>
						<th><?php #echo $detalle['servicio']['duracion'];?></th> -->

						<th><?php echo $solicitud['Estatus']; ?></th>
				    </tr>
			   	<?php } ?>
			  </tbody>
			</table>
			</div>
		</div>
	<?php } ?>	
  </div>
</div>
</div>
<div class="clearfix"></div>	

<script type="text/javascript">
	jQuery(document).ready(function() {

	});

	function ver_info(_this){
		var USER_ID = _this.attr("data-id");
		jQuery.post(
			"<?= get_home_url()."/wp-content/plugins/kmimos/dashboard/core/ajax/get_info_conocer.php" ?>",
			{
				user_id: USER_ID
			},
			function(d){
				console.log( d );

				var HTML = '';
				jQuery.each(d, function(i, v){
					HTML += '<tr><td>'+v.transaccion_id+'</td><td>'+v.fecha+'</td><td style="text-align: center;">'+v.disponible+'</td></tr>';
				});

				jQuery("#contenido").html( HTML );

				jQuery('#myModal').modal('show');
			}, 'json'
		);
	}
</script>