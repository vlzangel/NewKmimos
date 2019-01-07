<?php


    
    $favoritos = get_favoritos($user_id);

    if( count($favoritos)>0 ) {
        $CONTENIDO .= '<h1>Mis Favoritos</h1>
        <input type="hidden" id="user_id" name="user_id" value="'.$user_id.'" />
        <ul class="favoritos_container">';
        $CONTENIDO .= '<div class="km-resultados">';

            foreach($favoritos as $favorito){
                if( !empty($favorito) ){
                    $cuidador = $wpdb->get_row("
                        SELECT 
                            cuidadores.id,
                            cuidadores.id_post,
                            cuidadores.user_id,
                            cuidadores.longitud,
                            cuidadores.latitud,
                            cuidadores.adicionales,
                            (cuidadores.hospedaje_desde*1.2) AS precio,
                            cuidadores.experiencia, 
                            posts.post_title as titulo, 
                            posts.post_name as slug 
                        FROM 
                            cuidadores 
                        LEFT JOIN wp_posts as posts ON cuidadores.id_post=posts.id 
                        WHERE 
                            id_post = '{$favorito}'");
                    $CONTENIDO .= get_ficha_cuidador($cuidador, 0, $favoritos, 'grid', 'true', true);
                }
            }
            $CONTENIDO .= '</ul>';
            
        $CONTENIDO .= '</div>';
    }

    if( empty($CONTENIDO) ){
        $CONTENIDO .=  '
            <h1 class="favoritos_vacio">
                No tienes cuidadores agregados como favoritos
            </h1>';
    }