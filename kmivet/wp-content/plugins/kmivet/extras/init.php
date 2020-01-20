<?php
	function get_status_reserva($status) {
		switch ( $status ) {
			case 0:
				return 'No pagada';
			break;
			case 1:
				return 'Confirmada';
			break;
			case 2:
				return 'Arribo al domicilio';
			break;
			case 3:
				return 'Finalizada';
			break;
			case 4:
				return 'Cancelada';
			break;
			case 5:
				return 'Finalizada con Calificación';
			break;
			
			default:
				return 'No valido';
			break;
		}
	}
?>