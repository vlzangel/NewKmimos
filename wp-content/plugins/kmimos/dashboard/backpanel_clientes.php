<?php 
	session_start(); 
	$modulo = "cliente";
?>
<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_title">
				<h2>Panel de Control <small>Lista de clientes</small></h2>
				<hr> <div class="clearfix"></div>
			</div>
			<div class="row text-left"> 
				<div class="col-sm-12">
			    	<form id="filtros" class="form-inline" method="POST">
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
							<button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
						</div>
						<div class="col-sm-10 col-sm-offset-1" style="padding-top:10px;">
							<div class="checkbox">
							    <label>
							      	<input type="checkbox" id="mostrar_total_reserva" name="mostrar_total_reserva" <?php echo ( $_SESSION[ "total_reservas_".$modulo] == "YES" ) ? 'checked' : ''; ?>> Incluir Total de reservas generadas 
							    </label>
							</div>
						</div>
				    </form>
				</div>
			</div>
			<div class="clear"></div>
			<hr>
		</div>
	  	<div class="col-sm-12">  		
	    	<div class="row">
	    		<div class="col-sm-12" style="font-size: 10px!important;"> 
	    			<table id="_table" class="table table-striped table-bordered table-hover nowrap datatable-buttons" cellspacing="0" width="100%">
			  			<thead>
						    <tr>
						      	<th>#</th>
						      	<th>Fecha Registro</th>
						      	<th>Nombre</th>
						      	<th>Apellido</th>
						      	<th>Email</th>
						      	<th>Teléfono</th>
						      	<th>Nos conocio?</th>
						      	<th>Sexo</th>
						      	<th>Edad</th>
						      	<th># Reservas</th>
						      	<th>1er Conocer </th>
						      	<th>1ra Reserva </th>
						      	<th>Acciones </th>
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
                "url": "<?= plugins_url('kmimos/dashboard/ajax/clientes.php').'?home='.get_home_url() ?>",
                "type": "POST"
            },
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
			'order': [[ 1, 'asc' ]],
			'columnDefs': [
				{ orderable: true, targets: [0] }
			],
			dom: '<"col-md-6"B><"col-md-6"f><"#tblreserva"t><"col-sm-12"i>',
        });

        jQuery("#filtros").submit(function(e){
        	e.preventDefault();
        	var val = jQuery(this).val();

        	var cont = ( jQuery("#mostrar_total_reserva").prop("checked") ) ? "YES" : "NO";

        	jQuery.post(
        		"<?= plugins_url('kmimos/dashboard/ajax/set_sesion_data.php') ?>",
        		{ 
        			total_reservas_cliente: cont, 
        			desde_cliente: jQuery("#desde").val(), 
        			hasta_cliente: jQuery("#hasta").val() 
        		},
        		function(data){
        			console.log( data );
        			table.ajax.reload(); //  null, false 
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
