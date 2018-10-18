<?php
	require_once( dirname(dirname(dirname(__DIR__)))."/lib/pagos/pagos_cuidador.php" );
	$user_id = get_current_user_id();

	$hoy = date("Y-m-d H:i:s");
	$desde = date("Y-m-01", strtotime($hoy." -30 days"));
	$hasta = date("Y-m-d");

	$pay = $pagos->balance( $user_id );

	$cuidador = $pagos->db->get_row(" SELECT pago_periodo FROM cuidadores WHERE user_id = {$user_id}");

	$periodo_retiro = [
		'semanal',
		'quincenal',
		'mensual',
	];

?>

<h1 class="titulo">Balance</h1>

<section class="row text-right" style="margin-bottom: 10px;">
	<dir class="col-md-4 text-left">

		<!-- Total generados -->
		<label>TOTAL GENERADOS: $ <?php echo number_format($pay->total_generado, 2, ',', '.'); ?> </label> 

		<!-- Periodo de pago -->
		<div class="input-group">
		  <span class="input-group-addon" id="basic-addon1">Periodo de retiro: </span>
		  <select class="form-control" name="periodo">
		  	<?php 
			  	foreach( $periodo_retiro as $periodo ){ 
			  		$select = ( $periodo == $cuidador->pago_periodo )? 'selected':'';
					echo "<option value='{$periodo}' {$select}>".ucfirst($periodo)."</option>";
				} 
			?>
		  </select>
		</div>
	</dir>
	<dir class="col-md-4 col-md-offset-4">

		<!-- Ultimo retiro -->
		<label>ULTIMO RETIRO: <?php echo (!empty($pay->retiro->ultimo_retiro)) ? $pay->retiro->ultimo_retiro : 'NO POSEE' ; ?></label><br>

		<!-- Tiempo restante -->
		<label id="tiempo_restante_parent" class="btn btn-default <?php echo (!$pay->retiro->habilitado)? '':'hidden'; ?>">
			Tiempo restante: <span id="tiempo_restante"></span> 
		</label> 

		<!-- Boton de retiro -->
		<a id="<?php echo ($pay->disponible>0)? '':'disabled_'; ?>boton-retiro" class="<?php echo ($pay->disponible>0)? '':'disabled'; ?>btn btn-primary  <?php echo ($pay->retiro->habilitado)? '':'hidden'; ?>" data-target="modal-retiros">
			<i class="fa fa-money"></i> Retirar ahora
		</a>
	</dir>
</section>

<section class="row">

	<!-- Disponible -->
	<article class="col-md-3">
		<div class="alert bg-kmimos">
			<i class="fa balance-help fa-question-circle" aria-hidden="true"></i>
			<span>DISPONIBLE</span> 
			<div  style="font-size: 18px;">$ <?php echo number_format($pay->disponible, 2, ',','.'); ?></div>
		</div>
	</article>

	<!-- Proximo pago -->
	<article class="col-md-3">
		<div class="alert bg-kmimos">
			<i class="fa balance-help fa-question-circle" aria-hidden="true"></i>
			<span>PROXIMO PAGO</span> 
			<div  style="font-size: 18px;">$ <?php echo number_format($pay->proximo_pago, 2, ',','.'); ?></div>
		</div>
	</article>

	<!-- En progreso -->
	<article class="col-md-3">
		<div class="alert bg-kmimos">
			<i class="fa balance-help fa-question-circle" aria-hidden="true"></i>
			<span>EN PROGRESO</span> 
			<div  style="font-size: 18px;">$ <?php echo number_format($pay->en_progreso, 2, ',','.'); ?></div>
		</div>
	</article>

	<!-- Retenido -->
	<article class="col-md-3">
		<div class="alert bg-kmimos">
			<i class="fa balance-help fa-question-circle" aria-hidden="true"></i>
			<span>RETENIDO</span> 
			<div  style="font-size: 18px;">$ <?php echo number_format($pay->retenido, 2, ',','.'); ?></div>
		</div>
	</article>

	<!-- Mensaje de ayuda -->
	<article class="col-md-12 text-left">
		<div class="alert alert-info hidden" role="alert"><strong>DISPONIBLE:</strong> Saldo en cuenta disponible para retiros.</div>
	</article>

	<!-- Transacciones -->
	<article class="col-md-12">	
		<hr>
		<div class="row ">		

			<dir class="col-md-4 subtitulo">
				Transacciones
			</dir>
		
			<!-- Filtros -->
			<dir class="col-md-8 date-container text-right">
				<span>Desde: </span>
				<input type="date" name="ini" value="<?php echo $desde; ?>">
				
				<span>Hasta: </span>
				<input type="date" name="fin" value="<?php echo $hasta; ?>">
				
				<button class="btn btn-default" id="search-transacciones"><i class="fa fa-search"></i> Buscar</button>
			</div>

			<!-- Transacciones -->
			<div id="table">			
				<table id="example" class="table table-striped table-bordered nowrap" cellspacing="0">
	                <thead>
	                    <tr>
	                        <th width="90">Fecha</th>
	                        <th width="90">Referencia</th>
	                        <th>Descripci&oacute;n</th>
	                        <th width="150">Monto</th>
	                    </tr>
	                </thead>
	                <tbody></tbody>
	            </table>
			</div>
		</div>
	</article>
</section>

<div class="modal fade" id="retiros" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="z-index:999999999999999999!important;top:100px;" data-backdrop="false" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Retirar ahora   <label>Saldo disponible: $ <?php echo number_format($pay->disponible, 2, ',','.'); ?></label></h4>
      </div>
      <div class="modal-body">

        <div>    	
        <?php foreach ($pay->detalle as $key => $val) { 
        	if( $val > 0 ){
		        echo "
		        	<label style='background: #eee; padding:10px; border-radius: 5px;' > 
		        		<input type='checkbox' data-name='retiro_disponible' value='{$key}' data-monto='{$val}'>
	        			#{$key} 
	        			<span style='border-radius: 5px; padding: 3px 3px 3px 3px; background: #fff;'>Monto: {$val}</span> 
	        		</label>
		        ";
        	}
        }?>
        </div>

        <div>    	
	        <label>Descripci&oacute;n: </label>
	        <input type="text" name="descripcion" maxlength="100" class="form-control" value=""data-value="<?php echo $pay->disponible; ?>">
        </div>
        <div class="text-right">
	        <h4 style="color:#000;">Monto a retirar: $ <span id="modal-subtotal">0</span></h4>
	        <h4 style="color:#000;">Comisi&oacute;n: $ -10,00</h4>
	        <h4><strong>Total a transferir: $ <span id="modal-total">0</span></strong></h4>
        </div>

      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="retirar">Procesar</button>
      </div>
    </div>
  </div>
</div>

<script>
    var fecha = new Date('<?php echo $pay->retiro->tiempo_restante; ?>');
    var user_id = <?php echo $user_id; ?>;
</script>
