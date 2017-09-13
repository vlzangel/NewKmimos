<?php
    $photo = "/wp-content/themes/pointfinder/images/noimg.png";

    $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = {$user_id}");
    $tmp_user_id = ($cuidador->id) - 5000;
	$CONTENIDO .= '
    <input type="hidden" name="accion" value="nueva_galeria" />
    <input type="hidden" name="user_id" value="'.$tmp_user_id.'" />

    <section>
        <div class="vlz_img_portada_perfil">
            <div class="vlz_img_portada_fondo" style="background-image: url('.get_home_url().$photo.');"></div>
            <div class="vlz_img_portada_normal" style="background-image: url('.get_home_url().$photo.');"></div>
            <div class="vlz_img_portada_cargando" style="background-image: url('.getTema().'/images/cargando.gif);"></div>
            <div class="vlz_cambiar_portada">
                Cambiar Foto
                <input type="file" id="portada" name="xportada" accept="image/*" />
            </div>
        </div>
        <input type="hidden" class="vlz_img_portada_valor" id="portada" name="portada" data-valid="requerid" />
    </section>';
?>