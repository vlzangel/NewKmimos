<?php
    $favoritos = get_favoritos($user_id);
    //var_dump($favoritos);

    if( count($favoritos)>0 ) {
        $CONTENIDO .= '<h1 style="margin: 0px; padding: 0px;">Mis Favoritos</h1><hr style="margin: 5px 0px 10px;"><input type="hidden" id="user_id" name="user_id" value="'.$user_id.'" /><ul class="favoritos_container">';
        $CONTENIDO .= '<div class="km-resultados-grid">';

        foreach($favoritos as $favorito){

            $cuidador = $wpdb->get_row("SELECT cuidadores.*, posts.post_title as titulo, posts.post_name as slug  FROM cuidadores LEFT JOIN wp_posts as posts ON cuidadores.id_post=posts.id WHERE id_post = '{$favorito}'");
            //$photo = kmimos_get_foto($cuidador->user_id);
            //var_dump($cuidador);
            $cuidador_post = $wpdb->get_row("SELECT * FROM wp_posts WHERE ID = '{$favorito}'");
            //var_dump($cuidador_post);
            $CONTENIDO .= get_ficha_cuidador($cuidador, 0, $favoritos, 'grid');
            /*
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
            */
        }
        $CONTENIDO .= '</ul>';
        $CONTENIDO .= '</div>';
    }else{
        $CONTENIDO .=  '
            <h1 class="favoritos_vacio">
                No tienes ningún favorito agregado
            </h1>';
    }

?>