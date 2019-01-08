<?php
    $mascotas = kmimos_get_my_pets($user_id);

    if( count($mascotas) > 0 ) {
        $CONTENIDO .= '
        <h1 >Mis Mascotas</h1>
        <div>
            <div style="padding: 10px 0px 40px; text-align: justify;">
                <p>
                    <strong>En esta sección podrás identificar a las mascotas de tu propiedad.</strong>
                </p>
                <p>
                    Si piensas contratar un servicio a través de Kmimos, es importante que las identifiques ya que solo las identificadas en tu perfil estarán amparadas por la cobertura de servicios veterinarios Kmimos.
                </p>
                <p>
                    Si además te interesa formar parte de la familia de Cuidadores asociados a Kmimos, es importante también que tus mascotas estén identificadas. Muchas personas prefieren contratar a cuidadores que tengan perritos similares a los suyos, mientras que hay otros que buscan cuidadores que tengan mascotas de determinadas razas y tamaños.
                </p>
            </div>
            <ul class="mascotas_container">';
            foreach($mascotas as $pet){
                $pet_detail = get_post_meta($pet->ID);

                $photo = (!empty($pet_detail['photo_pet'][0])) ? get_home_url().'/'.$pet_detail['photo_pet'][0] : get_home_url().'/wp-content/themes/kmimos/images/noimg.png';
                $CONTENIDO .= '
                    <li class="mascotas_box">
                        <div class="mascotas_item">
                            <div class="mascotas_content">
                                <div class="vlz_img_portada_perfil">
                                    <div class="vlz_img_portada_fondo" style="background-image: url('.$photo.');"></div>
                                    <div class="vlz_img_portada_normal" style="background-image: url('.$photo.');"></div>
                                </div>
                                <div class="mascotas_data">
                                    <a class="kmi_link" href="'. get_home_url()."/perfil-usuario/mascotas/ver/".$pet->ID.'">'.$pet->post_title.'</a>

                                    <a href="'.get_home_url().'/busqueda" class="boton boton_morado">Reservar</a>
                                </div>
                                <div class="mascotas_delete" data-img="'.$pet->ID.'">
                                    x
                                </div>
                            </div>
                            
                        </div>
                    </li>';
            }
            $CONTENIDO .= '</ul>
        </div>';
    }else{
        $CONTENIDO .=  '
            <h1 class="mascotas_vacio">
                <img src="'.get_home_url().'/wp-content/themes/kmimos/images/new/icon/icon-hueso-color.svg" width="50px">
                No tienes ninguna mascota cargada
            </h1>
        ';
    }

?>