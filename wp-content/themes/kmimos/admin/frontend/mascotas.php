<?php
    $mascotas = kmimos_get_my_pets($user_id);

    if( count($mascotas) > 0 ) {
        $CONTENIDO .= '<h1 style="margin: 0px; padding: 0px;">Mis Mascotas</h1><hr style="margin: 5px 0px 10px;"><ul class="mascotas_container">';
        foreach($mascotas as $pet){
            $pet_detail = get_post_meta($pet->ID);

            $photo = (!empty($pet_detail['photo_pet'][0])) ? get_home_url().'/'.$pet_detail['photo_pet'][0] : get_home_url().'/wp-content/themes/pointfinder/images/default.jpg';
            $CONTENIDO .= '
                <li class="mascotas_box">
                    <div class="mascotas_item">
                        <a class="mascotas_content" href="'. get_home_url()."/perfil-usuario/mascotas/ver/".$pet->ID.'">
                            <div class="vlz_img_portada_perfil">
                                <div class="vlz_img_portada_fondo" style="background-image: url('.$photo.');"></div>
                                <div class="vlz_img_portada_normal" style="background-image: url('.$photo.');"></div>
                            </div>
                            <div class="mascotas_data">
                                <h3 class="kmi_link">'.$pet->post_title.'</h3>
                                <br>
                                <img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/100.png" width="50px">
                            </div>
                        </a>
                        <div class="mascotas_delete" data-img="'.$pet->ID.'">
                            Eliminar
                        </div>
                    </div>
                </li>';
        }
        $CONTENIDO .= '</ul>';
    }else{
        $CONTENIDO .=  '
            <p class="mascotas_vacio">
                <img src="'.get_home_url().'/wp-content/plugins/kmimos/assets/rating/100.png" width="50px">
                No tienes ninguna mascota cargada
            </p>';
    }

?>