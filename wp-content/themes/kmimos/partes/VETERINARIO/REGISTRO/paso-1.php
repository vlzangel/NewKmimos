<div>
	<label>Nombre Completo</label>
	<input type="text" name="kv_nombre" valid="required" class="validar" />
</div>

<div>
	<label>Correo Electrónico</label>
	<input type="text" name="kv_email" valid="required|email" class="validar" />
	<input type="checkbox" name="kv_email_no_usado" class="validar_existe" />
</div>

<div>
	<label>Fecha de nacimiento</label>
	<input type="date" name="kv_fecha" valid="required" class="validar" />
</div>

<div>
	<label>Género</label>
	<select name="kv_genero" valid="required" class="validar">
		<option value="">Seleccione</option>
		<option value="0">Hombre</option>
		<option value="1">Mujer</option>
	</select>
</div>

<div>
	<label>Documento de ID</label>
	<input type="number" name="kv_dni" valid="required" class="validar" />
</div>

<div>
	<label>RFC</label>
	<input type="text" name="kv_rfc" valid="required" class="validar" />
</div>

<div>
	<label>Código de referencia</label>
	<input type="text" name="kv_referencia" valid="required" class="validar" />
</div>

<div>
	<label>¿Cómo te enteraste de nosotros?</label>
	<select name="kv_referido" valid="required" class="validar">
		<option value="">Seleccione</option>
		<option>Facebook</option>
		<option>Twitter</option>
		<option>Anuncio de internet</option>
		<option>Página de MediQo</option>
		<option>Correo electrónico</option>
		<option>Por medio de un conocido</option>
		<option>Bolsa de trabajo</option>
	</select>
</div>