<label>¿De 1 al 5 como calificarías el servicio?</label>
<select name="valor">
	<?php
		for ($i=1; $i <= 5; $i++) { 
			echo "<option>{$i}</option>";
		}
	?>
</select>

<label>Cuéntanos ¿que tal te pareció el servicio?</label>
<textarea name="mensaje"></textarea>