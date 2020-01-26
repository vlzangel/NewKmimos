<?php $i = get_dias_meses(); ?>
<div>
	<label>Día de la semana</label>
	<select name="dia">
		<?php
			foreach ($i['dias_slug'] as $key => $dia) {
				echo '<option value="'.$key.'">'.$dia.'</option>';
			}
		?>
	</select>
</div>
<div class="kv_col_2">
	<div>
		<label>Hora Inicio</label>
		<input type="time" name="ini" />
	</div>
	<div>
		<label>Hora Fin</label>
		<input type="time" name="fin" />
	</div>
</div>