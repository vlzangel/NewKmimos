<?php global $wpdb;
// Usuarios 
require_once('core/ControllerCuidadoresDetalle.php');

// error_reporting(0);

// Parametros: Filtro por fecha
$landing = '';
$date = getdate();
$desde = '';//date("Y-m-01", $date[0] );
$hasta = '';//date("Y-m-d", $date[0]);
if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
	$desde = (!empty($_POST['desde']))? $_POST['desde']: "";
	$hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
}

$param = [];

// Buscar Reservas
$users = getUsers($param, $desde, $hasta);
?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="x_panel">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_title">
			<h2>Panel de Control <small>Lista de cuidadores</small></h2>
			<hr>
			<div class="clearfix"></div>
		</div>
		<!-- Filtros -->
		<div class="row text-right"> 
			<div class="col-sm-12">
		    	<form class="form-inline" action="<?php echo get_home_url(); ?>/wp-admin/admin.php?page="<?php echo $_GET['page']; ?> method="POST">
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
			      <th>ID</th>
			      <th>Flash</th>
			      <th>Nombre</th>
			      <th>Apellido</th>
			      <th>Cuidador</th>
			      <th>Email</th>
			      <th>Estado</th>
			      <th>Municipio</th>
			      <th>Direcci&oacute;n</th>
			      <th>Teléfono</th>

			      <th>Nro. Mascotas</th>
			      <th>Mascotas Permitidas</th>
			      <th>Check IN</th>
			      <th>Check OUT</th>
			      <th>Hospedaje Desde</th>
			      <th>Mascotas Cuidador</th>
			      <th>Tamaños Aceptados</th>
			      <th>Edades Aceptadas</th>
			      <th>Comportamientos Aceptados</th>

				  <th>Hospedaje</th>
				  <th>Guarderia</th>
				  <th>Paseos</th>
				  <th>Entrenamiento Básico</th>
				  <th>Entrenamiento Intermedio</th>
				  <th>Entrenamiento Avanzado</th>
				  <th>Servicios Adicionales</th>

				  <th>Transporte Sencillo</th>
				  <th>Transporte Redondo</th>

			      <th>Estatus</th>
			    </tr>
			  </thead>
			  <tbody>
			  	<?php $count=0; ?>
			  	<?php foreach( $users['rows'] as $row ){ ?>
			  		<?php
			  			// Metadata usuarios
			  			$usermeta = getmetaUser( $row['ID'] );
			  			$link_login = "/?i=".md5($row['ID']);

			  			$name = $usermeta['first_name'];
			  			$lastname = $usermeta['last_name'];
			  			if(empty($name)){
			  				$name = $usermeta['nickname'];
			  			}

			  			$ubicacion = getEstadoMunicipio($row['estados'], $row['municipios']);

  					    $mascotas_cuidador = '';
  					    $mascotas_cuidador_t = unserialize( $row['mascotas_cuidador']);
  					    foreach($mascotas_cuidador_t as $key => $val){
  					    	if( $val > 0 ){
	  					    	$mascotas_cuidador .= ( $mascotas_cuidador != '' )? ', ': '' ;
	  					    	$mascotas_cuidador .= $key;
	  						}
  					    }

  					    $tamanos_aceptados = '';
  					    $tamanos_aceptados_t = unserialize( $row['tamanos_aceptados']);
  					    foreach($tamanos_aceptados_t as $key => $val){
  					    	if( $val > 0 ){
	  					    	$tamanos_aceptados .= ( $tamanos_aceptados != '' )? ', ': '' ;
	  					    	$tamanos_aceptados .= $key;
	  						}
  					    }

  					    $edades_aceptadas = '';
  					    $edades_aceptadas_t = unserialize( $row['edades_aceptadas']);
  					    foreach($edades_aceptadas_t as $key => $val){
  					    	if( $val > 0 ){
	  					    	$edades_aceptadas .= ( $edades_aceptadas != '' )? ', ': '' ;
	  					    	$edades_aceptadas .= $key;
	  						}
  					    }

  					    $comportamientos_aceptados = '';
  					    $comportamientos_aceptados_t = unserialize( $row['comportamientos_aceptados']);
  					    foreach($comportamientos_aceptados_t as $key => $val){
  					    	if( $val > 0 ){
	  					    	$comportamientos_aceptados .= ( $comportamientos_aceptados != '' )? ', ': '' ;
	  					    	$comportamientos_aceptados .= $key;
	  						}
  					    }

  					    $adicionales_t = unserialize( $row['adicionales']);
  					    $Hospedaje_t =  unserialize( $row['hospedaje']);
  					    $servicios = getServicios( $row );

					  	$atributos = $wpdb->get_var("SELECT atributos FROM cuidadores WHERE user_id = ".$row['ID']);
					  	$atributos = unserialize($atributos);

					  	$flash = "";
						if( $atributos['flash'] == 1 ){
							$flash = '
								<i 
									class="fa fa-bolt" 
									aria-hidden="true"
									style="
										padding: 2px 4px;
									    border-radius: 50%;
									    background: #00c500;
									    color: #FFF;
									    margin-right: 2px;
									"
								></i> Flash
							';
						}
			  		?>
				    <tr>
				    	<th class="text-center"><?php echo $row['ID']; ?></th>
						<th><?php echo $flash; ?></th>
						<th><?php echo $name; ?></th>
						<th><?php echo $lastname; ?></th>
						<th>
					  		<a href="<?php echo get_home_url().'/wp-admin/post.php?action=edit&post='.$row['cuidador_post']; ?>">
								<?php echo $row["cuidador_title"]; ?>	
							</a>
						</th>
						<th>
					  		<a href="<?php echo $link_login; ?>">
								<?php echo $row['user_email']; ?>
							</a>
						</th>
						<th><?php echo $ubicacion['estado']; ?></th>
						<th><?php echo $ubicacion['municipio']; ?></th>						
						<th><?php echo utf8_encode($row['direccion']); ?></th>						
						<th><?php echo $usermeta['phone']; ?></th>
						<th><?php echo $row['num_mascotas']; ?></th>
						<th><?php echo $row['mascotas_permitidas']; ?></th>
						<th><?php echo $row['check_in']; ?></th>
						<th><?php echo $row['check_out']; ?></th>
						<th>$ <?php echo currency_format($row['hospedaje_desde'], "", "","."); ?></th>
					    <th><?php echo $mascotas_cuidador; ?></th>
						<th><?php echo $tamanos_aceptados; ?></th>
				        <th><?php echo $edades_aceptadas; ?></th>
						<th><?php echo $comportamientos_aceptados; ?></th>
						<th>
							<?php echo ( isset($Hospedaje_t['pequenos']) )? 'Pequeño: <span style="font-size:14px!important">$ '.$Hospedaje_t['pequenos'].'</span>,' : ''; ?>
							<?php echo ( isset($Hospedaje_t['medianos']) )? 'Mediano: <span style="font-size:14px!important">$ '.$Hospedaje_t['medianos'].'</span>,' : ''; ?>
							<?php echo ( isset($Hospedaje_t['grandes']) )? 'Grande: <span style="font-size:14px!important">$ '.$Hospedaje_t['grandes'].'</span>,' : ''; ?>
							<?php echo ( isset($Hospedaje_t['gigantes']) )? 'Gigante: <span style="font-size:14px!important">$ '.$Hospedaje_t['gigantes'].'</span>' : ''; ?>
						</th>
						<th>
							<?php echo ( isset($servicios['guarderia']['pequenos']) )? 'Pequeño: <span style="font-size:14px!important">$ '.$servicios['guarderia']['pequenos'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['guarderia']['medianos']) )? 'Mediano: <span style="font-size:14px!important">$ '.$servicios['guarderia']['medianos'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['guarderia']['grandes']) )? 'Grande: <span style="font-size:14px!important">$ '.$servicios['guarderia']['grandes'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['guarderia']['gigantes']) )? 'Gigante: <span style="font-size:14px!important">$ '.$servicios['guarderia']['gigantes'].'</span>' : ''; ?>
						</th>
						<th>
							<?php echo ( isset($servicios['paseos']['pequenos']) )? 'Pequeño: <span style="font-size:14px!important">$ '.$servicios['paseos']['pequenos'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['paseos']['medianos']) )? 'Mediano: <span style="font-size:14px!important">$ '.$servicios['paseos']['medianos'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['paseos']['grandes']) )? 'Grande: <span style="font-size:14px!important">$ '.$servicios['paseos']['grandes'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['paseos']['gigantes']) )? 'Gigante: <span style="font-size:14px!important">$ '.$servicios['paseos']['gigantes'].'</span>' : ''; ?>
						</th>
						<th>
							<?php echo ( isset($servicios['adiestramiento_basico']['pequenos']) )? 'Pequeño: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_basico']['pequenos'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['adiestramiento_basico']['medianos']) )? 'Mediano: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_basico']['medianos'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['adiestramiento_basico']['grandes']) )? 'Grande: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_basico']['grandes'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['adiestramiento_basico']['gigantes']) )? 'Gigante: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_basico']['gigantes'].'</span>' : ''; ?>
						</th>
						<th>
							<?php echo ( isset($servicios['adiestramiento_intermedio']['pequenos']) )? 'Pequeño: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_intermedio']['pequenos'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['adiestramiento_intermedio']['medianos']) )? 'Mediano: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_intermedio']['medianos'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['adiestramiento_intermedio']['grandes']) )? 'Grande: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_intermedio']['grandes'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['adiestramiento_intermedio']['gigantes']) )? 'Gigante: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_intermedio']['gigantes'].'</span>' : ''; ?>
						</th>
						<th>
							<?php echo ( isset($servicios['adiestramiento_avanzado']['pequenos']) )? 'Pequeño: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_avanzado']['pequenos'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['adiestramiento_avanzado']['medianos']) )? 'Mediano: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_avanzado']['medianos'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['adiestramiento_avanzado']['grandes']) )? 'Grande: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_avanzado']['grandes'].'</span>,' : ''; ?>
							<?php echo ( isset($servicios['adiestramiento_avanzado']['gigantes']) )? 'Gigante: <span style="font-size:14px!important">$ '.$servicios['adiestramiento_avanzado']['gigantes'].'</span>' : ''; ?>
						</th>

						<th>
							<?php
							$separador = '';
							foreach( $servicios['adicionales'] as $key => $val ){
								if( $val['costo'] > 0 ){
									echo $separador.$val['descripcion'].': <span style="font-size:14px!important">$ '.$val['costo'].'</span>';	
									$separador = ", ";
								}
							}
							?>
						</th>

						<th>
							<?php
								$separador = '';
								if( isset($servicios['transporte'][ 'transportacion_sencilla' ]) ){
									foreach( $servicios['transporte'][ 'transportacion_sencilla' ] as $key_2 => $val_2 ){
										if( $val_2['costo'] > 0 ){
											echo $separador.$val_2['descripcion'].': <span style="font-size:14px!important">$ '.$val_2['costo'].'</span>';	
											$separador = ", ";
										}
									}
								}
							?>
						</th>

						<th>
							<?php
								$separador = '';
								if( isset($servicios['transporte'][ 'transportacion_redonda' ]) ){
									foreach( $servicios['transporte'][ 'transportacion_redonda' ] as $key_2 => $val_2 ){
										if( $val_2['costo'] > 0 ){
											echo $separador.$val_2['descripcion'].': <span style="font-size:14px!important">$ '.$val_2['costo'].'</span>';	
											$separador = ", ";
										}
									}
								}
							?>
						</th>

						<th><?php echo ($row['estatus']==1)? 'Activo' : 'Inactivo' ; ?></th>
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
