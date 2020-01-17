<?php

    $HTML = '';
    if( !is_user_logged_in() ){
        include_once(__DIR__.'/partes/modal_login.php');
        include_once(__DIR__.'/partes/modal_register.php');
    }
    echo comprimir( $HTML );
    wp_enqueue_style( 'generales_css', get_recurso("css")."generales.css", array(), "1.0.0" );
    wp_enqueue_style( 'generales_responsive_css', get_recurso("css")."responsive/generales.css", array(), "1.0.0" );
    
    wp_enqueue_script('global_js', getTema()."/js/global.js", array("jquery"), '1.0.0');
    wp_enqueue_script('global_new_js', get_recurso("js")."global.js", array("jquery"), '1.0.0');
    wp_enqueue_script('boostrap.min.js', getTema()."/js/bootstrap.min.js", array("jquery"), '1.0.0');

    $HTML = '
        <footer>
            <div class="footer_alto">
                <div>
                    <div class="footer_alto_col_1">
                        <h2>Entérate de los últimos ciudados para tu mascota</h2>
                        <ul>
                            <li style="font-size: 13px;">¡Inscribete a nuestro blog y conócelas!</li>
                        </ul>
                    
                        <form id="suscribirse" onsubmit="form_subscribe(this); return false;" class="subscribe" data-subscribe="'.get_home_url().'/wp-content/plugins/kmimos">
                            <input type="hidden" name="section" value="'.$seccion.'" class="form-control" placeholder="Ingresa tu correo">
                            <input type="hidden" id="wlabelSubscribeFooter" name="wlabelSubscribeFooter" value="'.$_SESSION["wlabel"].'" class="form-control" placeholder="Ingresa tu correo">
                            <input type="email" id="email" name="mail" placeholder="Ingresa tu correo" />
                            <input type="submit" value="Inscribirme al blog" />
                            <div class="message message-especial"></div>
                        </form>

                        <div class="siguenos_alto">
                            <span>Siguenos en</span>
                            <a href="https://www.facebook.com/Kmimosmx/" target="_blank">
                                <img src="'.get_recurso("img").'HOME/SVG/Facebook.svg" />
                            </a>
                            <a href="https://www.instagram.com/kmimosmx/" target="_blank">
                                <img src="'.get_recurso("img").'HOME/SVG/Instagram.svg" />
                            </a>
                            <a href="https://www.youtube.com/channel/UCZuzqWCgGdboK-w5yGQjACQ" target="_blank">
                                <img src="'.get_recurso("img").'HOME/SVG/Youtube.svg" />
                            </a>
                        </div>
                    </div>
                    <div>
                        <h2>Servicios</h2>
                        <ul>
                            <p><a href="'.get_home_url().'/quiero-ser-veterinario/">Quiero ser cuidador</a></p>
                            <p><a href="'.get_home_url().'/mediqo">Buscar veterinario certificado</a></p>
                            <p><a target="blank" href="https://kmimos.com.mx/blog">Blog</a></p>
                        </ul>
                    </div>
                    <div>
                        <h2>Navega</h2>
                        <ul>
                            <p><a href="https://kmimos.com.mx/">Nosotros</a></p>
                            <p><a href="javascript:;">Preguntas y Respuestas</a></p>
                            <p><a href="https://kmimos.com.mx/coberturas-de-servicios-veterinarios/">Cobertura Veterinaria</a></p>
                            <p><a href="https://kmimos.com.mx">Comunicados de prensa</a></p>
                            <p><a href="https://kmimos.com.mx/terminos-y-condiciones/">Términos y Condiciones</a></p>
                            <p><a href="javascript:;">Nuestros Aliados</a></p>
                            <p><a href="https://kmimos.com.mx/contacta-con-nosotros/">Contáctanos</a></p> 
                        </ul>
                    </div>
                    <div>
                        <h2>Contáctanos</h2>
                        <ul class="info_contacto">
                            <li> <a href="tel:018009564667" > <i class="fa fa-phone" aria-hidden="true"></i> <span>Teléfono:</span> 01 (800) 9 564667 </a> </li>
                            <li> <a href="tel:+52015585261162" > <i class="fa fa-phone" aria-hidden="true"></i> <span>Telef. Local:</span> 01 (55) 85261162 </a> </li>
                            <!--
                                <li> <a href="whatsapp://send/?phone=+5213312614186" > <i class="fa fa-whatsapp" aria-hidden="true"></i> <span>WhatsApp:</span> +52 1 (33) 12614186 </a> </li>
                                <li> <a href="whatsapp://send/?phone=+5215568922182" > <i class="fa fa-whatsapp" aria-hidden="true"></i> <span>WhatsApp:</span> +52 1 (55) 68922182 </a> </li>
                            -->
                            <li> <a href="whatsapp://send/?phone=+5215531374829" > <i class="fa fa-whatsapp" aria-hidden="true"></i> <span>WhatsApp:</span> +52 1 (55) 31374829 </a> </li>
                            <li> <a href="mailto:contactomex@kmimos.la" > <i class="fa fa-envelope-o" aria-hidden="true"></i> <span>Email:</span> contactomex@kmimos.la </a> </li>
                        </ul>
                    </div>
                    <div class="siguenos_bajo">
                        <span style="display: block;">Siguenos en</span>
                        <a href="https://www.facebook.com/Kmimosmx/" target="_blank">
                            <img src="'.get_recurso("img").'HOME/SVG/Facebook.svg" />
                        </a>
                        <a href="https://www.instagram.com/kmimosmx/" target="_blank">
                            <img src="'.get_recurso("img").'HOME/SVG/Instagram.svg" />
                        </a>
                        <a href="https://www.youtube.com/channel/UCZuzqWCgGdboK-w5yGQjACQ" target="_blank">
                            <img src="'.get_recurso("img").'HOME/SVG/Youtube.svg" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer_bajo">
                <a href="'.get_home_url().'">Kmimos.com.mx</a> Todos los derechos reservados.
            </div>
        </footer>
    ';

    wp_footer();
    
    if(  $_SESSION['admin_sub_login'] == 'YES' ){
        $HTML .= "
            <a href='".get_home_url()."/?i=".md5($_SESSION['id_admin'])."&admin=YES' class='theme_button' style='position: fixed; display: inline-block; left: 50px; bottom: 50px; padding: 10px 25px; font-size: 40px; font-family: Roboto; z-index: 999999999999999999; text-decoration: none; color: #FFF; border-radius: 50%; font-weight: 600; background-color: #00d2c6; border-color: #00d2c6;'>
                X
            </a>
        ";
    }

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $user_tipo = get_user_meta($user_id, "user_type", true);

    switch ( $user_tipo ) {
        case 'veterinario':
            $activo = get_user_meta($user_id, "_mediqo_active", true);
            if( $activo+60 > time()  ){
                echo '
                    <div id="activado" class="modal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Usuario Activado Exitosamente!</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="kmivet_msg">
                                        Felicidades, ya hemos <strong>activado</strong> su cuenta <span>Kmivet</span><br>
                                        Para los próximos inicios de sesión podrá seguir usando su contraseña actual.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        jQuery("#activado").modal("show");
                    </script>
                ';
            }
        break;
    }

    echo comprimir($HTML);

    echo  "</body></html>";
?>

                    