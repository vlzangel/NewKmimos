<?php
	$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
	if(file_exists($kmimos_load)){
	    include_once($kmimos_load);
	}

	global $wpdb;


	function getDataReserva($id){
		global $wpdb;

		if( $id != "" && $id != NULL ){
			$reserva = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = {$id}");

			$metas = get_post_meta($id);

			$_mascotas = unserialize($metas["_booking_persons"][0]);
			$mascotas = 0;
			foreach ($_mascotas as $mascota_id => $mascota_cantidad) {
				$mascotas+=$mascota_cantidad;
			}

			$servicio = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = {$metas['_booking_product_id'][0]}");

			$info = explode(" - ", $servicio->post_title);

			$status = [
				"confirmed" => "Confirmado",
				"cancelled" => "Cancelado",
			];

			$r = [
				"id" => $reserva->ID,
				"fecha" => date("d/m/Y", strtotime($reserva->post_date) ),
				"checkin" => date("d/m/Y", strtotime($metas["_booking_start"][0]) ),
				"checkout" => date("d/m/Y", strtotime($metas["_booking_end"][0]) ),
				"mascotas" => $mascotas." mascota(s)",
				"monto" => "MXN $".number_format( $metas["_booking_cost"][0], 2, ",", "." ),
				"cuidador" => $info[1],
				"servicio" => $info[0],
				"status" => $status[ $reserva->post_status ]
			];

			return $r;
		}
	}

	$ult_3_meses = date("Y-m-d 00:00:00", strtotime("-3 month") );

	$SQL = "
		SELECT 
			wp_users.ID AS ID,
			wp_users.user_email AS user_email,
			wp_users.user_registered AS user_registered,
			( SELECT ID FROM wp_posts WHERE wp_users.ID = wp_posts.post_author AND wp_posts.post_type = 'wc_booking' AND wp_posts.post_status IN ('confirmed', 'cancelled') ORDER BY ID ASC LIMIT 0, 1 ) AS primera_reserva,
			( SELECT ID FROM wp_posts WHERE wp_users.ID = wp_posts.post_author AND wp_posts.post_type = 'wc_booking' AND wp_posts.post_status IN ('confirmed', 'cancelled') AND wp_posts.post_date >= '{$ult_3_meses}' ORDER BY ID ASC LIMIT 0, 1 ) AS reserva_ult_3_meses
		FROM 
			wp_users
		LEFT JOIN wp_usermeta AS wlabel ON ( wp_users.ID = wlabel.user_id )
		WHERE 
			( wlabel.meta_key = 'user_referred' OR wlabel.meta_key = '_wlabel' ) AND
			( wlabel.meta_value = 'cc-petco' OR wlabel.meta_value = 'petco' ) AND
			wp_users.user_registered >= '2018-09-01 00:00:00'
		GROUP BY wp_users.ID
	";

	$usuarios = $wpdb->get_results($SQL);
	$registros = "";
	if( count($usuarios) > 0 ){
		foreach ($usuarios as $usuario) {
			$metas = get_user_meta($usuario->ID);
			$otra = "N/A";
			$cancelo = "N/A";

			$otra_3_meses = "N/A";
			$cancelo_3_meses = "N/A";

			if( $usuario->primera_reserva != null ){
				$info = getDataReserva($usuario->primera_reserva);
				$_info = "
					<strong>ID</strong>: {$info['id']}<br>
					<strong>Creada</strong>: {$info['fecha']}<br>
					<strong>Inicio</strong>: {$info['checkin']}<br>
					<strong>Fin</strong>: {$info['checkout']}<br>
					<strong># mascotas</strong>: {$info['mascotas']}<br>
					<strong>Total</strong>: {$info['monto']}<br>
					<strong>Cuidador</strong>: {$info['cuidador']}<br>
					<strong>Servicio</strong>: {$info['servicio']}<br>
					<strong>Status</strong>: {$info['status']}<br>
				";

				$cancelo = ( $info['status'] == "Cancelado" ) ? "Si" : "No";

				if( $cancelo == "Si" ){
					$SQL = "SELECT ID FROM wp_posts WHERE post_author = {$usuario->ID} AND post_type = 'wc_booking' AND post_status = 'confirmed' ORDER BY ID ASC";
					$_nueva_reserva = $wpdb->get_var($SQL);
					if( $_nueva_reserva != null){
						$info = getDataReserva($_nueva_reserva);
						$otra = "
							<strong>ID</strong>: {$info['id']}<br>
							<strong>Creada</strong>: {$info['fecha']}<br>
							<strong>Inicio</strong>: {$info['checkin']}<br>
							<strong>Fin</strong>: {$info['checkout']}<br>
							<strong># mascotas</strong>: {$info['mascotas']}<br>
							<strong>Total</strong>: {$info['monto']}<br>
							<strong>Cuidador</strong>: {$info['cuidador']}<br>
							<strong>Servicio</strong>: {$info['servicio']}<br>
							<strong>Status</strong>: {$info['status']}<br>
						";
					}else{
						$otra = "No realiz&oacute; otra reserva";
					}
				}
			}else{
				$_info = "No tiene reservas confirmadas o canceladas";
			}

			$_info_3_meses = "Sin reservas";
			if( $usuario->reserva_ult_3_meses != null ){
				$info = getDataReserva($usuario->reserva_ult_3_meses);
				$_info_3_meses = "
					<strong>ID</strong>: {$info['id']}<br>
					<strong>Creada</strong>: {$info['fecha']}<br>
					<strong>Inicio</strong>: {$info['checkin']}<br>
					<strong>Fin</strong>: {$info['checkout']}<br>
					<strong># mascotas</strong>: {$info['mascotas']}<br>
					<strong>Total</strong>: {$info['monto']}<br>
					<strong>Cuidador</strong>: {$info['cuidador']}<br>
					<strong>Servicio</strong>: {$info['servicio']}<br>
					<strong>Status</strong>: {$info['status']}<br>
				";

				$cancelo_3_meses = ( $info['status'] == "Cancelado" ) ? "Si" : "No";

				if( $cancelo_3_meses == "Si" ){
					$SQL = "SELECT ID FROM wp_posts WHERE post_author = {$usuario->ID} AND post_type = 'wc_booking' AND post_status = 'confirmed' AND post_date >= '{$ult_3_meses}' ORDER BY ID ASC";
					$_nueva_reserva = $wpdb->get_var($SQL);
					if( $_nueva_reserva != null){
						$info = getDataReserva($_nueva_reserva);
						$otra_3_meses = "
							<strong>ID</strong>: {$info['id']}<br>
							<strong>Creada</strong>: {$info['fecha']}<br>
							<strong>Inicio</strong>: {$info['checkin']}<br>
							<strong>Fin</strong>: {$info['checkout']}<br>
							<strong># mascotas</strong>: {$info['mascotas']}<br>
							<strong>Total</strong>: {$info['monto']}<br>
							<strong>Cuidador</strong>: {$info['cuidador']}<br>
							<strong>Servicio</strong>: {$info['servicio']}<br>
							<strong>Status</strong>: {$info['status']}<br>
						";
					}else{
						$otra_3_meses = "No realiz&oacute; otra reserva";
					}
				}
			}else{
				$_info_3_meses = "No tiene reservas confirmadas o canceladas";
			}

			if( $_info != "No tiene reservas confirmadas o canceladas" ){

				$registros .= "
					<tr>
						<td>".$metas["first_name"][0]." ".$metas["last_name"][0]."</td>
						<td>".$usuario->user_email."</td>
						<td>".( date("d/m/Y", strtotime( $usuario->user_registered ) ) )."</td>
						<td>".$metas["user_mobile"][0]."</td>
						<td>".$metas["user_referred"][0]."</td>
						<td>".$_info."</td>
						<td>".$cancelo."</td>
						<td>".$otra."</td>

						<td>".$_info_3_meses."</td>
						<td>".$cancelo_3_meses."</td>
						<td>".$otra_3_meses."</td>
					</tr>
				";
				
			}
		}
	}
?>

<div class="module_title">
    Reservas por primera vez
</div>

<table id="_example_" class="table table-striped table-bordered nowrap" style="width:100%" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th>Nombre y apellido</th>
            <th>Email</th>
            <th>Fecha Registro</th>
            <th>Teléfono</th>
            <th>Donde nos conocio?</th>
            <th>Primera Reserva</th>
            <th>¿Cancel&oacute;?</th>
            <th>Siguiente Reserva</th>
            <th>Reserva Ult. 3 Meses</th>
            <th>¿Cancel&oacute;?</th>
            <th>Siguiente Reserva</th>
        </tr>
    </thead>
    <tbody>
        <?php echo $registros; ?>
    </tbody>
</table>


<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#_example_').DataTable({
            "language": {
                "emptyTable":           "No hay datos disponibles en la tabla.",
                "info":                 "Del _START_ al _END_ de _TOTAL_ ",
                "infoEmpty":            "Mostrando 0 registros de un total de 0.",
                "infoFiltered":         "(filtrados de un total de _MAX_ registros)",
                "infoPostFix":          " (actualizados)",
                "lengthMenu":           "Mostrar _MENU_ registros",
                "loadingRecords":       "Cargando...",
                "processing":           "Procesando...",
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
            "scrollX": true
        });
    } );
</script>

<style type="text/css">
	.paginacion{
		overflow: hidden;
	    padding: 10px 0px;
	}
	.paginacion span {
		display: inline-block;
		padding: 10px;
		cursor: pointer;
	}
	.paginacion span.activo {
		background: #000;
		color: #FFF;
	}
</style>