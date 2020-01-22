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
<script type="text/javascript">
	jQuery( document ).ready(function() {
		jQuery("[name='motivo']").on('change', function(e){
			if( jQuery(this).val() == 'otro' ){
				jQuery("#otro_motivo").css('display', 'block');
				jQuery("[name='otro_motivo']").prop('required', true);
			}else{
				jQuery("#otro_motivo").css('display', 'none');
				jQuery("[name='otro_motivo']").removeAttr('required');
			}
		});
	});
</script>