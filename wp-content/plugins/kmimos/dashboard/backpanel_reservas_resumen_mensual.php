<?php global $wpdb;
// Reservas 
require_once('core/ControllerReservasResumeMensual.php');
// Parametros: Filtro por fecha
$date = getdate(); 
$desde = date("Y-m-01", $date[0] );
$hasta = date("Y-m-d", $date[0]);
$_desde = "";
$_hasta = "";
if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
	$_desde = (!empty($_POST['desde']))? $_POST['desde']: "";
	$_hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
}
$razas = get_razas();
// Buscar Reservas
$reservas = getReservas($_desde, $_hasta);


?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_title">
		<h2>Panel de Control <small>Reservas</small></h2>
		<hr>
		<div class="clearfix"></div>
		</div>
		<!-- Filtros -->
		<div class="row text-right"> 
			<div class="col-sm-12">
		    	<form class="form-inline" action="<?php echo get_home_url(); ?>/wp-admin/admin.php?page=bp_reservas_by_ubicacion" method="POST">
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
		  		<div class="clearfix"></div>
			</div>
		</div>
  	</div>
	<div class="clearfix"></div>
  	<div class="col-sm-12">  	

  	<?php if( empty($reservas) ){ ?>
  		<!-- Mensaje Sin Datos -->
	    <div class="row">
	    	<div class="col-sm-12">
	    		<div class="alert alert-info">
		    		No existen registros
	    		</div>
		    </div>
	    </div> 
  	<?php }else{ ?>  		
	    <div class="row"> 
	    	<div class="col-sm-12" id="table-container" 
	    		style="font-size: 10px!important;">
	  		<!-- Listado de Reservas -->
			<table id="tblReservas" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons" 
					cellspacing="0" width="100%">
			  <thead>
			    <tr>
			      <th>Estado</th>
			      <th>Municipio</th>
<!-- 
			      <th>Enero</th>
			      <th>Febrero</th>
			      <th>Marzo</th>
			      <th>Abril</th>
			      <th>Mayo</th>
			      <th>Junio</th>
			      <th>Julio</th>
			      <th>Agosto</th>
			      <th>Septiembre</th>
			      <th>Octubre</th>
			      <th>Noviembre</th>
			      <th>Diciembre</th> -->
			      <?php foreach( $reservas['year'] as $year ){ ?>
	 			      <th><?php echo $year; ?></th>
			      <?php } ?>

 			      <th>Total</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $registros = $reservas['registros'];
			  		foreach( $registros as $municipio => $rows ){ ?>
				  	<?php foreach( $rows as $localidad => $row ){ ?>
					    <tr>
					      <th><?php echo utf8_decode( $municipio ); ?></th>
					      <th><?php echo utf8_decode( $localidad ); ?></th>

					      <?php $total = 0; ?>
					      <?php foreach( $reservas['year'] as $year ){ ?>
					      <?php $total += $row[ $year ]; ?>
			 			      <th><?php echo ($row[ $year ]>0)? $row[ $year ] : 0 ; ?></th>
					      <?php } ?>						      

					      <th><?php echo $total; ?></th>
					    </tr>
				   	<?php } ?>
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
