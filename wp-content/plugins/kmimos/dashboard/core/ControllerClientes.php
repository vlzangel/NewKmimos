<?php global $wpdb;
// Usuarios 
require_once('core/ControllerClientes.php');
// Parametros: Filtro por fecha
$landing = '';
$date = getdate();
$desde = "";
$hasta = "";
if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
	$desde = (!empty($_POST['desde']))? $_POST['desde']: "";
	$hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
}
// Buscar Reservas
$razas = get_razas();
$users = getUsers($desde, $hasta);
?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="x_panel">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_title">
			<h2>Panel de Control <small>Lista de clientes</small></h2>
			<hr>
			<div class="clearfix"></div>
		</div>
		<!-- Filtros -->
		<div class="row text-right"> 
			<div class="col-sm-12">
		    	<form class="form-inline" action="<?php echo get_home_url(); ?>/wp-admin/admin.php?page=bp_clientes" method="POST">
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
	</div>
  	<div class="col-sm-12">  	

  	<?php if( empty($users) ){ ?>
  		<!-- Mensaje Sin Datos -->
	    <div class="row alert alert-info"> No existen datos para mostrar</div>
  	<?php }else{ ?>
	    <div class="row">
	    	<div class="col-sm-12" id="table-container" 
	    		style="font-size: 10px!important;">
	  		<!-- Listado de users -->
			<table id="tblusers" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons" 
					cellspacing="0" width="100%">
			  <thead>
			    <tr>
			      <th>#</th>
			      <th>Fecha Registro</th>
			      <th>Nombre y Apellido</th>
			      <th>Email</th>
			      <th>Teléfono</th>
			      <th>Donde nos conocio?</th>
			      <th>Mascota(s)</th>
			      <th>Raza(s)</th>
			      <th>Edad(es)</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $count=0; ?>
			  	<?php foreach( $users['rows'] as $row ){ ?>
			  		<?php
			  			// Metadata usuarios
			  			$usermeta = getmetaUser( $row['ID'] );
			  			$link_login = get_home_url()."/?i=".md5($row['ID']);

			  			$name = "{$usermeta['first_name']} {$usermeta['last_name']}";
			  			if(empty( trim($name)) ){
			  			 	$name = $usermeta['nickname'];
			  			}
			  		?>
				    <tr>
				    	<th class="text-center"><?php echo $row['ID']; ?></th>
						<th><?php echo date_convert($row['user_registered'], 'd-m-Y') ; ?></th>
						<th><?php echo $name; ?></th>
						<th>
					  		<a href="<?php echo $link_login; ?>">
								<?php echo $row['user_email']; ?>
							</a>
						</th>
						<th><?php echo $usermeta['phone']; ?></th>
						<th><?php echo (!empty($usermeta['user_referred']))? $usermeta['user_referred'] : 'Otros' ; ?></th>
						<?php 
					  		# Mascotas del Cliente
					  		$mypets = getMascotas($row['ID']); 
					  		$pets_nombre = array();
					  		$pets_razas  = array();
					  		$pets_edad	 = array();
							foreach( $mypets as $pet_id => $pet) { 
								$pets_nombre[] = $pet['nombre'];
								$pets_razas[] = $pet['raza'];
								$pets_edad[] = $pet['edad'];
							} 

							if( count($pets_nombre) > 0 ){

						  		$raza = "Bien";
						  		foreach ($pets_razas as $key => $value) {
						  			if( $value == "" || $value == 0 ){
						  				$raza = "Malos";
						  				break;
						  			}
						  		}
						  		$pets_razas = $raza;

						  		$edad = $pets_edad[0];
						  		foreach ($pets_edad as $value) {
						  			if( $edad < $value ){
						  				$edad = $value;
						  			}
						  		}
						  		$pets_edad = $edad;


						  		$pets_nombre = implode(", ", $pets_nombre);
							}else{
						  		$pets_nombre = "_";
						  		$pets_razas  = "_";
						  		$pets_edad	 = "_";
							}
						?>
						<th><?php echo $pets_nombre; ?></th>
						<th><?php echo $pets_razas; ?></th>
						<th><?php echo $pets_edad; ?></th>
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
