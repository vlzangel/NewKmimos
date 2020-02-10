<div>
	<label>Tipo de Mascota</label>
	<select id="type" name="type">
		<option value="">Selecciones</option>
		<option value="4a21748d1c264b628f755c881ab1a7d6">Animales Terrestres</option>
		<option value="2c8a9af60c654529b9438e1febb25ea1">Animales Acuáticos</option>
	</select>
</div>

<div>
	<label>Especie</label>
	<select id="especie" name="especie">
	</select>
</div>

<div>
	<label>Diagnóstico</label>
	<select id="diagnostico" name="diagnostico">
	</select>
</div>

<div>
	<label>Notas Adicionales</label>
	<textarea id="notas" name="notas"></textarea>
</div>

<script type="text/javascript">
	jQuery("#type").on("change", function(e){
		get_list_diagnostic(jQuery(this).val(), 1, "especie");
	});
	jQuery("#especie").on("change", function(e){
		get_list_diagnostic(jQuery(this).val(), 2, "diagnostico");
	});
</script>