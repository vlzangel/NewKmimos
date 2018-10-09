 
<?php
	$raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
	include_once($raiz.'/wp-load.php');
	global $wpdb;

	extract($_POST);

	$pedido_id = $wpdb->get_var("SELECT post_parent FROM wp_posts where ID = {$ID} and post_status in ( 'confirmed', 'complete' )");

	$reserva = [];
	$show_nc = false;
	if( $pedido_id > 0 ){
		$show_nc = true;

		$reserva = kmimos_desglose_reserva_data( $pedido_id, true );

		$hoy = date('Y-m-d');
		$ini = date('Y-m-d', $reserva['servicio']['inicio']);
		$fin = date('Y-m-d', $reserva['servicio']['fin']);

		$rango_inicio = ( $hoy >= $ini )? $hoy : $ini;
	}

?>
<script>
	var tipo_servicio = "<?php echo strtolower($reserva['servicio']['tipo']) ?>";
</script> 

<?php if( !$show_nc ){ ?>
	<div class="text-center" style="display: <?php echo $show_msg; ?>">
		<p style=" font-weight: bold; padding: 20px 0px 0px 0px;">
			La reserva debe estar completada o confirmada para realizar la nota de cr&eacute;dito
		</p>
	</div>
<?php }else{ ?>

	<div style="display: <?php echo $show_nc; ?>">
		<form name="form-nc" action="#" method="post">

			<input type="hidden" name="reserva_id" value="<?php echo $ID; ?>">
			<input type="hidden" name="pedido_id" value="<?php echo $pedido_id; ?>">

			<section id="mas_info" style="display: none;">
				<article class="cliente contenedor">
					<h1><strong>Cliente</strong></h1>
					<div>Nombre: <?php echo $reserva['cliente']['nombre']; ?></div>
					<div>Email:  <?php echo $reserva['cliente']['email']; ?></div>
					<div>Tel&eacute;fono: <?php echo $reserva['cliente']['telefono']; ?></div>
				</article>
				<article class="cuidador contenedor">
					<h1><strong>Cuidador</strong></h1>
					<div>Nombre: <?php echo $reserva['cuidador']['nombre']; ?></div>
					<div>Email:  <?php echo $reserva['cuidador']['email']; ?></div>
					<div>Tel&eacute;fono: <?php echo $reserva['cuidador']['telefono']; ?></div>
				</article>
			</section>
			<div class="mas_info">
				<span>Info cliente y cuidador</span>
			</div>

			<section class="total-top">
				<div class="contenedor">
					Reserva #: 
					<div><?php echo $reserva['servicio']["id_reserva"]?></div>
				</div>
				<div class="contenedor">
					Total Nota de Cr&eacute;dito: 
					<div data-target="total">0,00</div>
				</div>
				<div>Desde: <?php echo date('d/m/Y', $reserva['servicio']['inicio']); ?> Hasta: <?php echo date('d/m/Y', $reserva['servicio']['fin']); ?></div>
			</section>

			<?php if( !empty($reserva['servicio']['variaciones']) ){ ?>
			<section class="servicios">
				<h1 class="popup-titulo">SERVICIO: <?php echo strtoupper($reserva['servicio']['tipo']); ?></h1>
				<article>
				<?php foreach( $reserva['servicio']['variaciones'] as $key => $s_principal ){ 
					$code = md5($s_principal[1]);
				?>
					<div class="row" style="margin-bottom:20px; ">
						<div class="col-md-8">
							<label>
								<input type="checkbox" name="s_principal[]" value="<?php echo $code; ?>" data-group="prorrateo_<?php echo $code; ?>"> 
								<?php echo "{$s_principal[0]} {$s_principal[1]} x {$s_principal[2]} x {$s_principal[3]}"; ?>
							</label>
						</div>
						<div class="col-md-4 monto" >$ <?php echo $s_principal[4]; ?></div>
				
						<div data-target="prorrateo_<?php echo $code; ?>" class="col-sm-4">
							<label>Hasta: </label> 
							<input type="date" data-name="hasta" name="hasta_<?php echo $code; ?>" 
							 data-code="<?php echo $code; ?>" 
							 data-monto="<?php echo str_replace(',','.', str_replace('.', '', $s_principal[3]) ); ?>" 
							 value=""
							 min="<?php echo $ini; ?>"
							 max="<?php echo $fin; ?>">
						</div>
						<div data-target="prorrateo_<?php echo $code; ?>" class="col-sm-4">
							<label>Noches/D&iacute;as Restantes: </label>
							<input type="text" name="noches_<?php echo $code; ?>" class="form-control" readonly value="0.00">
						</div>
						<div data-target="prorrateo_<?php echo $code; ?>" class="col-sm-4">	
							<label>Monto: </label>
							<input type="text" name="prorrateo_<?php echo $code; ?>" class="form-control" readonly value="0.00">
						</div>
					</div>			
				<?php } ?>
				</article>
			</section>
			<?php } ?>

			<?php if( !empty($reserva['servicio']['adicionales']) ){ ?>
			<section class="servicios">
				<h1 class="popup-titulo">SERVICIOS ADICIONALES</h1>
				
				<?php foreach( $reserva['servicio']['adicionales'] as $item ){ ?>
				<article>
					<div class="row">		
						<div class="col-md-8">
							<label>
								<input 
									type="checkbox" name="servicios[]" 
									value="<?php echo md5($item[0]); ?>"  
									data-monto="<?php echo str_replace(',','.', str_replace('.', '', $item[3]) ); ?>">
								<?php echo "{$item[0]} - {$item[1]} x {$item[2]}"; ?>
							</label>
						</div>
						<div class="col-md-4 monto">$ <?php echo $item[3]; ?></div>
					</div>
				</article>
				<?php } ?>
			</section>
			<?php } ?>

			<?php if( !empty($reserva['servicio']['transporte']) ){ ?>
			<section class="servicios">
				<h1 class="popup-titulo">TRANSPORTACIÓN</h1>

				<?php foreach( $reserva['servicio']['transporte'] as $item){ ?>
				<article>
					<div class="row">		
						<div class="col-md-8">
							<label>
								<input type="checkbox" 
									name="transporte[]" 
									value="<?php echo md5($item[0]); ?>"
									data-monto="<?php echo  str_replace(',','.', str_replace('.', '', $item[3]) ); ?>"
								> 
								<?php echo $item[0]; ?>
							</label>
						</div>
						<div class="col-md-4 monto">$ <?php echo $item[3]; ?></div>
					</div>
				</article>
				<?php } ?>
			</section>
			<?php } ?>

			<section class="row">
				<article class="col-md-12">
					<hr>
					<label>Observaciones</label>
					<textarea row="4" style="width: 100%;" name="observaciones"></textarea>
				</article>
			</section>
			<section class="totales">
				<div>Total Nota de Cr&eacute;dito:</div> 
				<div data-target="total">$ 0,00</div>
			</section>

		</form>
		<section class="text-right">
			<hr>
			<button class="btn btn-success" id="nc_save">Guardar</button>
		</section>
	</div>

<?php } ?>
