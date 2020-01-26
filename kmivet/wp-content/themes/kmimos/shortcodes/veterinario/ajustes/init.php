<?php
	$veterinario = $wpdb->get_row("SELECT * FROM {$pf}veterinarios WHERE user_id = '{$user_id}' ");
?>
<h1 class="titulo_perfil">Mis Ajustes</h1>
<form id="kv_form" autocomplete="off" enctype="multipart/form-data">

	<div class="inputs_containers">

		<section>
            <label for="firstname" class="lbl-text">Precio del servicio:</label>
            <label class="lbl-ui">
                <input type="text" id="precio" name="precio" value="<?= $veterinario->precio ?>" />
            </label>
        </section>

	</div>

	<div class="container_btn">
	    <input type="submit" id="btn_actualizar" class="km-btn-primary" value="Actualizar">
	    <div class="perfil_cargando" style="background-image: url('.getTema().'/images/cargando.gif);" ></div>
	</div>
</form>