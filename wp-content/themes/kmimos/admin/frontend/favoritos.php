<?php
    $favoritos = kmimos_get_favoritos($user_id);

    if( $favorito ) {
        $CONTENIDO .= '<h1 style="margin: 0px; padding: 0px;">Mis Favoritos</h1><hr style="margin: 5px 0px 10px;"><input type="hidden" id="user_id" name="user_id" value="'.$user_id.'" /><ul class="favoritos_container">';
        foreach($favoritos as $favorito){

            $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE id_post = '{$favorito}'");
            $photo = kmimos_get_foto($cuidador->user_id);

            $cuidador_post = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = '{$favorito}'");

            $CONTENIDO .= '
                <li class="favoritos_box">
                    <div class="favoritos_item">
                        <a class="favoritos_content" href="'. get_home_url()."/petsitters/".$cuidador_post->post_name.'">
                            <div class="vlz_img_portada_perfil">
                                <div class="vlz_img_portada_fondo" style="background-image: url('.$photo.');"></div>
                                <div class="vlz_img_portada_normal" style="background-image: url('.$photo.');"></div>
                            </div>
                            <div class="favoritos_data">
                                <h3 class="kmi_link">'.$cuidador_post->post_title.'</h3>
                            </div>
                        </a>
                        <div class="favoritos_delete" data-fav="'.$favorito.'">
                            Eliminar
                        </div>
                    </div>
                </li>';
        }
        $CONTENIDO .= '</ul>';
    }else{
        $CONTENIDO .=  '
            <p class="favoritos_vacio">
                No tienes ningún favorito agregado
            </p>';
    }

?>