<?php global $wpdb;
// Usuarios 
require_once('core/ControllerClientes.php');
// Parametros: Filtro por fecha
$landing = '';
$date = getdate();
$desde = '';
$hasta = '';


$mostrar_total_reserva = (!empty($_POST['mostrar_total_reserva']))? true : false;
if(	!empty($_POST['desde']) && !empty($_POST['hasta']) ){
	$desde = (!empty($_POST['desde']))? $_POST['desde']: "";
	$hasta = (!empty($_POST['hasta']))? $_POST['hasta']: "";
}
// Buscar Reservas
$razas = get_razas();
$users = getUsers($desde, $hasta);

global $current_user;
$user_id = $current_user->ID
?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="x_panel">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_title">
			<h2>Panel de Control <small>Lista de clientes</small></h2>
			<hr> <div class="clearfix"></div>
		</div>
		<!-- Filtros -->
		<div class="row text-left pull-right"> 
			<div class="col-sm-12">
		    	<form class="form-inline" action="<?php echo get_home_url(); ?>/wp-admin/admin.php?page=bp_clientes" method="POST">

					<div class="col-sm-1">
						<label>Filtrar:</label>
					</div>
					<div class="col-sm-10">
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
					</div>
					<div class="col-sm-10 col-sm-offset-1" style="padding-top:10px;">
						<div class="checkbox">
						    <label>
						      <input type="checkbox" name="mostrar_total_reserva" <?php echo ($mostrar_total_reserva)? 'checked' : ''; ?>> Incluir Total de reservas generadas 
						    </label>
						</div>
					</div>
			    </form>
			</div>
		</div>
		<div class="clear"></div>
		<hr>
	</div>
  	<div class="col-sm-12"><?php 
  		if( empty($users) ){
	    	echo '<div class="row alert alert-info"> No existen datos para mostrar</div>';
  		}else{ 
  			$cant_reser_titulo = '';
  			if( $mostrar_total_reserva ){
	      		$cant_reser_titulo = '<th>Cant. Reservas</th>';
	      	}

	      	$permitidos = [
	      		367
	      	];

	      	$acciones_titulo = '';
	      	$acciones_info = false;
	      	if( in_array($user_id, $permitidos) ){
	      		$acciones_titulo = '<th> Acciones </th>';
	      		$acciones_info = true;
	      	}

  			echo '
	    	<div class="row">
	    		<div class="col-sm-12" id="table-container" style="font-size: 10px!important;"> <table id="tblusers" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons" cellspacing="0" width="100%">
			  			<thead>
						    <tr>
						      	<th>#</th>
						      	<th>Fecha Registro</th>
						      	<th>Nombre</th>
						      	<th>Apellido</th>
						      	<th>Email</th>
						      	<th>Teléfono</th>
						      	<th>Donde nos conocio?</th>
						      	<th>Sexo</th>
						      	<th>Edad</th>
						      	'.$cant_reser_titulo.'
						      	<th>Primera Sol. Conocer </th>
						      	<th>Primera Reserva </th>
						      	'.$acciones_titulo.'
						    </tr>
			  			</thead>
			  			<tbody>';
			  				
			  				foreach( $users['rows'] as $key => $row ){ 
			  					$usermeta = getmetaUser( $row['ID'] );

					  			if( $usermeta['user_age'] == "" ){
					  				$usermeta['user_age'] = "25-35 A&ntilde;os";
					  			}else{
					  				$usermeta['user_age'] .= " A&ntilde;os";
					  			}
					  			if( $usermeta['phone'] == "" ){
					  				if( $usermeta['user_referred'] != "Petco-CPF" ){
					  					$usermeta['user_referred'] = "CPF";
					  				}
					  			}

			  					$link_login = get_home_url()."/?i=".md5($row['ID']);

					  			$name = "{$usermeta['first_name']}";
					  			$lastname = "{$usermeta['last_name']}";
					  			if(empty( trim($name)) ){
					  			 	$name = $usermeta['nickname'];
					  			}

					  			$cant_reservas = 0;
						        if( $mostrar_total_reserva ){ 
						  			$cant_reservas = getCountReservas( $row['ID'] );
						  		}

								$reserva_15 = '';
								$_reserva_15 = '';
								$p_reserva = get_primera_reservas(  $row['ID'] );
								$dif = null;
								if( isset($p_reserva['rows'][0]['post_date_gmt']) ){
									$dif = diferenciaDias($row['user_registered'], $p_reserva['rows'][0]['post_date_gmt']);
									if( $dif['dia'] >= 0 && $dif['dia'] <= 15 ){
										$reserva_15 = '15 Dias';
									}else if( $dif['dia'] >= 16 && $dif['dia'] <= 30 ){
										$reserva_15 = '30 Dias';
									}else if( $dif['dia'] >= 16 && $dif['dia'] <= 45 ){
										$reserva_15 = '45 Dias';
									}else if( $dif['dia'] >= 16 && $dif['dia'] <= 60 ){
										$reserva_15 = '60 Dias';
									}else {
										$reserva_15 = '+60 Dias';
									}
									$_reserva_15 = $dif['dia'];
								}

								$conocer_15 = '';
								$_conocer_15 = '';
								$p_conocer = get_primera_conocer(  $row['ID'] );
								$dif_conocer = null;
								if( isset($p_conocer['rows'][0]['post_date_gmt']) ){

									$dif_conocer = diferenciaDias($row['user_registered'], $p_conocer['rows'][0]['post_date_gmt']);
									if( $dif_conocer['dia'] >= 0 && $dif_conocer['dia'] <= 15 ){
										$conocer_15 = '15 Dias';
									}else if( $dif_conocer['dia'] >= 16 && $dif_conocer['dia'] <= 30 ){
										$conocer_15 = '30 Dias';
									}else if( $dif_conocer['dia'] >= 16 && $dif_conocer['dia'] <= 45 ){
										$conocer_15 = '45 Dias';
									}else if( $dif_conocer['dia'] >= 16 && $dif_conocer['dia'] <= 60 ){
										$conocer_15 = '60 Dias';
									}else {
										$conocer_15 = '+60 Dias';
									}
									$_conocer_15 = $dif_conocer['dia'];

								}

								$_cant_reservas = '';
								if( $mostrar_total_reserva ){
									$_cant_reservas = '<th>'.$_cant_reservas['rows'][0]['cant'].'</th>';
							    }

							    $_status = '';
							    if( $acciones_info ){
								    $_status = '<span id="user_'.$row['ID'].'" class="enlace" onclick="change_status( jQuery(this) )" data-id="'.$row['ID'].'" data-status="inactivo">Desactivar</span>';
								    if( $usermeta['status_user'] == 'inactivo' ){
								    	$_status = '<span id="user_'.$row['ID'].'" class="enlace" onclick="change_status( jQuery(this) )" data-id="'.$row['ID'].'" data-status="activo">Activar</span>';
								    }
								    $_status = '<th>'.$_status.'</th>';
							    }

								$referido_por = (!empty($usermeta['user_referred'])) ? $usermeta['user_referred'] : 'Otros' ;

								echo '
								    <tr>
								    	<th class="text-center">'.$row['ID'].'</th>
										<th>'.date_convert($row['user_registered'], 'Y-m-d').'</th>
										<th>'.$name.'</th>
										<th>'.$lastname.'</th>
										<th>
									  		<a href="'.$link_login.'">
												'.$row['user_email'].'
											</a>
										</th>
										<th>'.$usermeta['phone'].'</th>
										<th>'.$referido_por.'</th>
								        '.$_cant_reservas.'
										<th style="text-transform: capitalize;">'.$usermeta['user_gender'].'</th>
										<th>'.$usermeta['user_age'].'</th>
										<th>'.$conocer_15.'</th>
										<th>'.$reserva_15.'</th>
										'.$_status.'
								    </tr>';
			   				} echo '
			  			</tbody>
					</table>
				</div>
			</div>';
		} echo '
  		</div>
	</div>
</div>
<div class="clearfix"></div>'; ?>
<style type="text/css">
	.enlace{
		cursor: pointer;
		color: #337ab7;
	}
	.enlace:hover{
		color: #23527c;
	}
</style>
<script type="text/javascript">
	function change_status(_this) {
		jQuery.post(
			"<?= plugins_url('kmimos/dashboard/core/ajax/change_status_user.php') ?>",
			{
				user_id: _this.attr('data-id'),
				status: _this.attr('data-status')
			},
			function(data){
				console.log( data );
				if( data.status == 'activo' ){
					jQuery("#user_"+_this.attr('data-id')).attr('data-status', 'inactivo');
					jQuery("#user_"+_this.attr('data-id')).html('Desactivar');
				}else{
					jQuery("#user_"+_this.attr('data-id')).attr('data-status', 'activo');
					jQuery("#user_"+_this.attr('data-id')).html('Activar');
				}
			},
			'json'
		);
	}
</script>
