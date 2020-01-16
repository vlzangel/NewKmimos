<?php 
	$cid = vlz_get_page();
	$reserva = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}kmivet_reservas WHERE id = '{$cid}' ");
?>
<form id="cancelar_cita">
	<input type="hidden" name="cita_id" value="<?= $reserva->cita_id ?>" />
	<label>Motivo de la cancelación de la cita</label>
	<select name="motivo">
		<option>No estoy disponible</option>
		<option>No pude contactar al paciente</option>
		<option>El paciente ya no requiere la consulta</option>
		<option value="otro">Otro motivo</option>
	</select>
	<div id="otro_motivo">
		<label>Indique el motivo</label>
		<textarea name="otro_motivo"></textarea>
	</div>
	<button type="submit">Cancelar</button>
	<div class="kv_msg"></div>
</form>