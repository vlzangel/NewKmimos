<?php
	session_start();

	$mostrar = false;
	if( is_user_logged_in() && !isset($_SESSION['popup_datos_banco']) ){

		global $wpdb;

		$cuidador = is_petsitters();
		if( isset($cuidador->user_id) && $cuidador->user_id > 0 ){

			// $user_id = get_current_user_id();
			// $cuidador = $wpdb->get_row( "SELECT nombre, apellido, banco FROM cuidadores where user_id = ". $user_id );

			$mostrar = true;
			$_SESSION['popup_datos_banco'] = true;

			if( isset($cuidador->banco) ){
				$banco = [];
				if( !empty($cuidador->banco) ){
					$banco = unserialize($cuidador->banco);
					if( isset($banco['cuenta']) && strlen($banco['cuenta']) == 18 ){
						$mostrar = false;
					}
				}
			}
		}
	}


?>

<?php if( $mostrar ){ ?>
	<div class="modal fade " id="notificacion-bancaria" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg modal-dialog-especial" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 style="" class="text-left">¡Hola <strong><?php echo $cuidador->nombre." ".$cuidador->apellido; ?>!</strong></h3>
				</div>
				<div class="modal-body text-justify">
					<div class="row">					
						<div class="col-md-7 col-sm-7 col-xm-12">
							<p class="p_2">
								No olvides actualizar tus datos bancarios lo antes posible dentro de tu perfil, con el objetivo de <strong>poder recibir todos tus pagos de servicios kmimos.</strong>
							</p>
							<p class="p_2">
								El proceso de pago automatizado empezar&aacute; a efectuarse <strong>a partir del 22 de noviembre</strong>, en caso de no actualizar los datos, tus pagos no podr&aacute;n ser efectuados. <br>
								Si tienes dudas, puedes ponerte en contacto con nosotros a trav&eacute;s:
							</p>

							<div class="banco-movil">							
								<p class="p_2">
									Mail: <span style="color: #00d2c6;">a.vera@kmimos.la</span><br>
									Tel&eacute;fono: <span style="color: #00d2c6;">55 3137 4829</span>
								</p>
								<div class="text-center">
									<a href="<?php echo get_home_url(); ?>/perfil-usuario" class="btn btn-default btn-popup-bancos" style="
									    "> IR A MI PERFIL <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></a>
								</div>
							</div>
							<div class="banco-movil-imagen">
								<img src="<?php echo get_home_url(); ?>/wp-content/themes/kmimos/images/popup-datos-bancarios/dog-movil.png" class="img-responsive">
							</div>
						</div>
						<div class="col-md-4 col-sm-4 col-xm-12 col-imagen pull-right">
							<img src="<?php echo get_home_url(); ?>/wp-content/themes/kmimos/images/popup-datos-bancarios/dog.png" class="img-responsive">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#notificacion-bancaria').modal('show');
		});
	</script>
<?php } ?>
