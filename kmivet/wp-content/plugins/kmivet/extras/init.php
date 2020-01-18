<?php
	function get_status_reserva($status) {
		switch ( $status ) {
			case 0:
				return 'No pagada';
			break;
			case 1:
				return 'Cita confirmada';
			break;
			case 2:
				return 'Arribo al domicilio';
			break;
			case 3:
				return 'Finalización de la cita';
			break;
			case 4:
				return 'Cita cancelada';
			break;
			case 5:
				return 'Cita finalizada con Calificación';
			break;
			
			default:
				return 'No valido';
			break;
		}
	}
?>