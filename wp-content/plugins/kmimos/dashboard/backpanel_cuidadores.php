<?php 
	session_start(); 
	$modulo = "cuidador";
?>
<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_title">
				<h2>Panel de Control <small>Lista de cuidadores</small></h2>
				<hr> <div class="clearfix"></div>
			</div>
			<div class="row"> 
				<div class="col-sm-12">
					<form id="filtros" class="form-inline" action="/wp-admin/admin.php?page=bp_cuidadores" method="POST">
						<div class="col-sm-1">
							<label>Filtrar:</label>
						</div>
						<div class="col-sm-10">
							<div class="form-group">
								<div class="input-group">
								  <div class="input-group-addon">Desde</div>
								  <input type="date" class="form-control" id="desde" name="desde" value="<?php echo $_SESSION[ "desde_".$modulo]; ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="input-group">
								  <div class="input-group-addon">Hasta</div>
								  <input type="date" class="form-control" id="hasta" name="hasta" value="<?php echo $_SESSION[ "hasta_".$modulo] ?>">
								</div>
							</div>
							<button id="submit" type="submit" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
						</div>
					</form>
					<hr>  
				</div>
			</div>
			<hr> <div class="clearfix"></div>
		</div>

		<div class="col-sm-12">  
			<div class="row">
				<div class="col-sm-12" id="table-container" style="font-size: 10px!important;">
					<table id="_table" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Flash</th>
								<th>Fecha Registro</th>
								<th>Registrado desde</th>
								<th>Fecha de Nacimiento</th>
								<th>Nombre y Apellido</th>
								<th>Nombre</th>
								<th>Apellido</th>
								<th>Cuidador</th>
								<th>Email</th>
								<th>Estado</th>
								<th>Municipio</th>
								<th>Direcci&oacute;n</th>
								<th>Teléfono</th>
								<th>Donde nos conocio?</th>
								<th>Estatus</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>	

<style type="text/css">
	.enlace{
		cursor: pointer;
		color: #337ab7;
	}
	.enlace:hover{
		color: #23527c;
	}
	#_table_wrapper .row:nth-child(2) {
		overflow: auto;
	}
	td span {
	    color: #2e2eff;
	    font-weight: 600;
	    cursor: pointer;
	}
	.form-inline .radio input[type="radio"], .form-inline .checkbox input[type="checkbox"] {
	    margin: 0px 5px 0px 0px;
	}
	div.dataTables_wrapper div.dataTables_filter {
	    display: inline-block;
	    width: 300px;
	    float: right;
	}
	div.dataTables_wrapper div.dataTables_info {
	    float: left;
	}
	.dataTables_scrollBody {
	    position: relative;
	    overflow: auto;
	    width: 100%;
	}
</style>

<script type="text/javascript">
    var table = "";
    jQuery(document).ready(function() {

        table = jQuery('#_table').DataTable({
            "language": {
                "emptyTable":           "No hay datos disponibles en la tabla.",
                "info":                 "Del _START_ al _END_ de _TOTAL_ ",
                "infoEmpty":            "Mostrando 0 registros de un total de 0.",
                "infoFiltered":         "(filtrados de un total de _MAX_ registros)",
                "infoPostFix":          " (actualizados)",
                "lengthMenu":           "Mostrar _MENU_ registros",
                "loadingRecords":       'Cargando, por favor espere... &nbsp;&nbsp; <i class="fa fa-spinner fa-spin"></i>',
                "processing":           'Procesando, por favor espere... &nbsp;&nbsp; <i class="fa fa-spinner fa-spin"></i>',
                "search":               "Buscar:",
                "searchPlaceholder":    "Dato para buscar",
                "zeroRecords":          "No se han encontrado coincidencias.",
                "paginate": {
                    "first":            "Primera",
                    "last":             "Última",
                    "next":             "Siguiente",
                    "previous":         "Anterior"
                },
                "aria": {
                    "sortAscending":    "Ordenación ascendente",
                    "sortDescending":   "Ordenación descendente"
                }
            },
            "ajax": {
                "url": "<?= plugins_url('kmimos/dashboard/ajax/cuidadores.php').'?home='.get_home_url() ?>",
                "type": "POST"
            },
            dom: 'Bfrtip',
			buttons: [
				{
				  extend: "csv",
				  className: "btn-sm"
				},
				{
				  extend: "excelHtml5",
				  className: "btn-sm"
				},
			],
			responsive: false,
			scrollX: true
        });


        jQuery("#filtros").submit(function(e){
        	e.preventDefault();
        	var val = jQuery(this).val();

        	
        	jQuery("#submit").html('<i class="fa fa-spinner fa-spin"></i> Buscando...');
        	jQuery("#submit").prop("disabled", true);

        	var cont = ( jQuery("#mostrar_total_reserva").prop("checked") ) ? "YES" : "NO";

        	jQuery.post(
        		"<?= plugins_url('kmimos/dashboard/ajax/set_sesion_data.php') ?>",
        		{ 
        			total_reservas_cliente: cont, 
        			desde_cuidador: jQuery("#desde").val(), 
        			hasta_cuidador: jQuery("#hasta").val() 
        		},
        		function(data){
        			table.ajax.reload(function(r){
        				jQuery("#submit").html('<i class="fa fa-search"></i> Buscar');
        				jQuery("#submit").prop("disabled", false);
        			}, true);
        		}
        	);

        });
    } );

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
