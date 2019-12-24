<div>
	<label>Calle y Número</label>
	<input type="text" name="kv_calle" valid="required" class="validar" />
</div>

<div>
	<label>Interior</label>
	<input type="text" name="kv_interior" valid="required" class="validar" />
</div>

<div>
	<label>Estado</label>
	<select name="kv_delegacion" valid="required" class="validar">
		<option value="">Seleccione</option>
		<?php
			global $wpdb;
		    $_estados = $wpdb->get_results("SELECT * FROM states WHERE country_id = 1 ORDER BY `order` ASC, name ASC");
		    $estados = '';
		    foreach ($_estados as $key => $estado) {
		        echo '<option value="'.$estado->id.'" >'.utf8_decode($estado->name).'</option>';
		    }
		?>
	</select>
</div>

<div>
	<label>Delegación o Municipio</label>
	<select name="kv_delegacion" valid="required" class="validar">
		<option value="">Seleccione</option>
		<option>Hombre</option>
		<option>Mujer</option>
	</select>
</div>

<div>
	<label>Colonia</label>
	<select name="kv_colonia" valid="required" class="validar">
		<option value="">Seleccione</option>
		<option>Hombre</option>
		<option>Mujer</option>
	</select>
</div>

<div>
	<label>Código postal</label>
	<input type="number" name="kv_postal" valid="required" class="validar" />
</div>

<div>
	<label>Teléfono Fijo</label>
	<input type="text" name="kv_telf_fijo" valid="required" class="validar" />
</div>

<div>
	<label>Teléfono Movil</label>
	<input type="text" name="kv_telf_movil" valid="required" class="validar" />
</div>