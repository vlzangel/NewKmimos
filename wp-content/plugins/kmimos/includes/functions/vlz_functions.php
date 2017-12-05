<?php

    if(!function_exists('kmimos_session')){
        function kmimos_session(){
            if( !isset($_SESSION) ){ session_start(); }
            global $current_user;
            $user_id = md5($current_user->ID);

            if( isset( $_SESSION["MR_".$user_id] ) ){
                return $_SESSION["MR_".$user_id];
            }else{
                return false;
            }

        }
    }

    if(!function_exists('kmimos_set_session')){
        function kmimos_set_session($DS){
            if( !isset($_SESSION) ){ session_start(); }
            global $current_user;
            $user_id = md5($current_user->ID);
            $_SESSION["MR_".$user_id] = $DS;
        }
    }

    if(!function_exists('kmimos_quitar_session')){
        function kmimos_quitar_session(){
            if( !isset($_SESSION) ){ session_start(); }
            global $current_user;
            $user_id = md5($current_user->ID);
            unset($_SESSION["MR_".$user_id]);
        }
    }

    if(!function_exists('kmimos_get_kmisaldo')){
        function kmimos_get_kmisaldo(){
            global $current_user;
            return get_user_meta($current_user->ID, "kmisaldo", true)+0;
            
        }
    }

    if(!function_exists('kmimos_set_kmisaldo')){
        function kmimos_set_kmisaldo($id_cliente, $id_orden, $id_reserva){
            global $wpdb;

            $status = $wpdb->get_var("SELECT post_status FROM wp_posts WHERE ID = {$id_orden}");

            $metas_orden = get_post_meta($id_orden);
            $metas_reserva  = get_post_meta( $id_reserva );

            $itemmetas = $wpdb->get_results("SELECT * FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '{$metas_reserva['_booking_order_item_id'][0]}' AND (meta_key = '_wc_deposit_meta' OR meta_key = '_line_total' OR meta_key = '_line_subtotal' )"); 

            $items = array();
            foreach ($itemmetas as $key => $value) {
                $items[ $value->meta_key ] = $value->meta_value;
            }

            $deposito = unserialize( $items['_wc_deposit_meta'] );

            $saldo = 0;
            
            if( $deposito['enable'] == 'yes' ){
                $saldo = $deposito['deposit'];
            }else{
                $saldo = $items['_line_subtotal'];
                $saldo -= $metas_orden['_cart_discount'][0];
            }
        
            $descuento = 0;
            $order_item_id = $wpdb->get_var("SELECT order_item_id FROM wp_woocommerce_order_items WHERE order_id = '{$id_orden}' AND order_item_type = 'coupon' AND order_item_name LIKE '%saldo-%'"); 
            if( $order_item_id != '' ){
                $descuento = $wpdb->get_var("SELECT meta_value FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '{$order_item_id}' AND meta_key = 'discount_amount' ");
            }

            $otros_cupones = $wpdb->get_results("SELECT * FROM wp_woocommerce_order_items WHERE order_id = '{$id_orden}' AND order_item_type = 'coupon' AND order_item_name NOT LIKE '%saldo-%'");
            foreach ($otros_cupones as $key => $value) {
                $cupon_id = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_title = '{$value->order_item_name}'");
                $wpdb->query("DELETE FROM wp_postmeta WHERE post_id = '{$cupon_id}' AND meta_key = '_used_by' AND meta_value = '{$id_cliente}'");
            }

            if($status == 'wc-on-hold' && ( $metas_orden['_payment_method'][0] == 'tienda' || $metas_orden['_payment_method'][0] == 'openpay_store' ) ){
                $saldo = $descuento;  
            }else{
                $saldo += $descuento;                
            }

            $saldo_persistente = get_user_meta($id_cliente, "kmisaldo", true)+0;

            update_user_meta($id_cliente, "kmisaldo", $saldo_persistente+$saldo);
            
        }
    }

    if(!function_exists('kmimos_cupon_saldo')){
        function kmimos_cupon_saldo($monto_cupon){
            global $wpdb;
            
            global $current_user;
            $id_cupon = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_name='saldo-{$current_user->ID}'");
            if( $id_cupon == NULL ){
                date_default_timezone_set('America/Mexico_City');
                $hoy = date("Y-m-d H:i:s");
                $id_cupon = $wpdb->insert('wp_posts', array(
                    "ID" => NULL,
                    "post_author" => $current_user->ID,
                    "post_date" => $hoy,
                    "post_date_gmt" => $hoy,
                    "post_content" => "",
                    "post_title" => "saldo-".$current_user->ID,
                    "post_excerpt" => "",
                    "post_status" => "publish",
                    "comment_status" => "closed",
                    "ping_status" => "closed",
                    "post_password" => "",
                    "post_name" => "saldo-".$current_user->ID,
                    "to_ping" => "",
                    "pinged" => "",
                    "post_modified" => $hoy,
                    "post_modified_gmt" => $hoy,
                    "post_content_filtered" => "",
                    "post_parent" => 0,
                    "guid" => get_home_url()."/?post_type=shop_coupon&#038;p=",
                    "menu_order" => 0,
                    "post_type" => "shop_coupon",
                    "post_mime_type" => "",
                    "comment_count" => 0
                ));
                $id_cupon = $wpdb->get_var("SELECT ID FROM wp_posts WHERE post_name='saldo-{$current_user->ID}'");
                $wpdb->query("UPDATE wp_posts SET guid = '".get_home_url()."/?post_type=shop_coupon&#038;p=".$id_cupon."' WHERE ID = ".$id_cupon);
                $wpdb->query("
                    INSERT INTO wp_postmeta VALUES
                        (NULL, ".$id_cupon.", 'discount_type', 'fixed_cart'),
                        (NULL, ".$id_cupon.", 'coupon_amount', '{$monto_cupon}'),
                        (NULL, ".$id_cupon.", 'individual_use', 'no'),
                        (NULL, ".$id_cupon.", 'product_ids', ''),
                        (NULL, ".$id_cupon.", 'exclude_product_ids', ''),
                        (NULL, ".$id_cupon.", 'usage_limit', '0'),
                        (NULL, ".$id_cupon.", 'usage_limit_per_user', '0'),
                        (NULL, ".$id_cupon.", 'limit_usage_to_x_items', ''),
                        (NULL, ".$id_cupon.", 'expiry_date', ''),
                        (NULL, ".$id_cupon.", 'free_shipping', 'no'),
                        (NULL, ".$id_cupon.", 'exclude_sale_items', 'no'),
                        (NULL, ".$id_cupon.", 'product_categories', 'a:0:{}'),
                        (NULL, ".$id_cupon.", 'exclude_product_categories', 'a:0:{}'),
                        (NULL, ".$id_cupon.", 'minimum_amount', ''),
                        (NULL, ".$id_cupon.", 'maximum_amount', ''),                    
                        (NULL, ".$id_cupon.", 'customer_email', 'a:0:{}');
                ");
            }else{
                $sqls = array(
                    "UPDATE wp_postmeta SET meta_value = '0' WHERE post_id = ".$id_cupon." AND meta_key = 'usage_limit'",
                    "UPDATE wp_postmeta SET meta_value = '0' WHERE post_id = ".$id_cupon." AND meta_key = 'usage_limit_per_user'",
                    "UPDATE wp_postmeta SET meta_value = '{$monto_cupon}' WHERE post_id = ".$id_cupon." AND meta_key = 'coupon_amount'"
                );
                foreach ($sqls as $sql) {
                    $wpdb->query($sql);
                }
            }

            return "saldo-".$current_user->ID;
        }
    }
       
    if( !function_exists('kmimos_saldo_titulo') ){
        function kmimos_saldo_titulo(){
            return "Saldo a favor";
        }
    }

    if(!function_exists('kmimos_desglose_reserva')){

        function kmimos_borrar_formato_numerico($valor){
            return $valor+0;
        }

        function kmimos_format_adicionales($valor, $txt){
            preg_match_all("#;(.*?)\)#", $valor, $matches);
            return array(
                $txt,
                kmimos_borrar_formato_numerico( $matches[1][0] )
            );
        }

        function kmimos_adicionales($orden_items){
            $adicionales_array = array();
            $transporte  = array();
            foreach ($orden_items as $key => $value) {

                switch ($value->meta_value) {

                    case 'Transp. Sencillo - Rutas Cortas':
                        $transporte[] = kmimos_format_adicionales($value->meta_key, $value->meta_value);
                    break;

                    case 'Transp. Sencillo - Rutas Medias':
                        $transporte[] = kmimos_format_adicionales($value->meta_key, $value->meta_value);
                    break;

                    case 'Transp. Sencillo - Rutas Largas':
                        $transporte[] = kmimos_format_adicionales($value->meta_key, $value->meta_value);
                    break;

                    case 'Transp. Redondo - Rutas Cortas':
                        $transporte[] = kmimos_format_adicionales($value->meta_key, $value->meta_value);
                    break;

                    case 'Transp. Redondo - Rutas Medias':
                        $transporte[] = kmimos_format_adicionales($value->meta_key, $value->meta_value);
                    break;

                    case 'Transp. Redondo - Rutas Largas':
                        $transporte[] = kmimos_format_adicionales($value->meta_key, $value->meta_value);
                    break;

                    case 'Baño (precio por mascota)':
                        $adicionales_array[] = kmimos_format_adicionales($value->meta_key, 'Baño');
                    break;

                    case 'Ba&ntilde;o (precio por mascota)':
                        $adicionales_array[] = kmimos_format_adicionales($value->meta_key, 'Baño');
                    break;
                    
                    case 'Corte de Pelo y U&ntilde;as (precio por mascota)':
                        $adicionales_array[] = kmimos_format_adicionales($value->meta_key, 'Corte de Pelo y Uñas');
                    break;
                    
                    case 'Corte de Pelo y Uñas (precio por mascota)':
                        $adicionales_array[] = kmimos_format_adicionales($value->meta_key, 'Corte de Pelo y Uñas');
                    break;
                    
                    case 'Visita al Veterinario (precio por mascota)':
                        $adicionales_array[] = kmimos_format_adicionales($value->meta_key, 'Visita al Veterinario');
                    break;
                    
                    case 'Limpieza Dental (precio por mascota)':
                        $adicionales_array[] = kmimos_format_adicionales($value->meta_key, 'Limpieza Dental');
                    break;
                    
                    case 'Acupuntura (precio por mascota)':
                        $adicionales_array[] = kmimos_format_adicionales($value->meta_key, 'Acupuntura');
                    break;

                }
            }

            return array(
                "adicionales" => $adicionales_array,
                "transporte" => $transporte
            );
        }
    }


    if(!function_exists('kmimos_desglose_reserva_data')){

        function kmimos_desglose_reserva_data($id, $email = false){

            global $wpdb;

            /* Reserva y Orden */

            $reserva = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_type = 'wc_booking' AND post_parent = '".$id."'");

            $metas_orden = get_post_meta($id);
            $metas_reserva = get_post_meta( $reserva->ID );

            /* Producto */

            $producto = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '".$metas_reserva['_booking_product_id'][0]."'");

            $tipo_servicio = explode("-", $producto->post_title);
            $tipo_servicio = $tipo_servicio[0];

            $precio_base = get_post_meta( $producto->ID, "_price", true );

            $inicio = date("d/m/Y", strtotime($metas_reserva['_booking_start'][0]));
            $fin    = date("d/m/Y", strtotime($metas_reserva['_booking_end'][0]));

            $xini = strtotime($metas_reserva['_booking_start'][0]);
            $xfin = strtotime($metas_reserva['_booking_end'][0]);

            $id_orden_item = $metas_reserva['_booking_order_item_id'][0];

            $orden_item = $wpdb->get_results("SELECT * FROM wp_woocommerce_order_itemmeta WHERE order_item_id = '".$id_orden_item."'");

            $data_temp = kmimos_adicionales($orden_item);
            $adicionales_array = $data_temp["adicionales"];
            $transporte  = $data_temp["transporte"];

            $detalles_reserva = array();
            $mascotas = array();
            foreach ( $orden_item as $key => $value ) {
                $detalles_reserva[$value->meta_key] = $value->meta_value;
                if( strpos($value->meta_key, "Mascotas") > -1 ){
                    $mascota = substr(end(explode(" ", $value->meta_key)), 0, 5);
                    $mascotas[ $mascota ] = $value->meta_value;
                }
            }

            $variaciones_array = array(
                "peque" => "Peque", 
                "media" => "Media",
                "grandes"   => "Grand", 
                "gigantes"  => "Gigan"
            );

            $txts = array(
                "peque"  => 'Pequeña', 
                "media"  => 'Mediana', 
                "grandes"   => "Grande", 
                "gigantes"  => "Gigante"
            );

            $dias = ceil(((($xfin - $xini)/60)/60)/24);

            $dias_noches = "Noche"; if( trim($tipo_servicio) != "Hospedaje" ){ $dias_noches = "Día"; }else{ $dias--; }
            $plural_dias = ""; if( $dias > 1 ){ $plural_dias = "s"; } $dias_noches .= $plural_dias;

            $info = kmimos_get_info_syte();

            $variaciones = array(); $grupo = 0;
            foreach ($variaciones_array as $key => $value) {
                if( isset( $mascotas[$value] ) ){
                    $plural_tamanos = ""; if( $detalles_reserva[$value] > 1 ){ $plural_tamanos = "s"; }
                    $variacion_ID = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_parent={$producto->ID} AND post_name LIKE '%{$key}%' ");
                    $unitario = $precio_base+get_post_meta($variacion_ID, "block_cost", true);
                    $variaciones[] = array(
                        $mascotas[$value],
                        //"Mascota".$plural_tamanos." ".
                        $txts[$key].$plural_tamanos,
                        $dias.' '.$dias_noches,
                        number_format( $unitario, 2, ',', '.'),
                        number_format( ($unitario*$mascotas[$value]*$dias), 2, ',', '.')
                    );
                    $grupo += $mascotas[$value];
                }
            }

            $adicionales_desglose = array();
            if( count($adicionales_array) > 0 ){
                $plural_tamanos = ""; if( $grupo > 1 ){ $plural_tamanos = "s"; }
                foreach ($adicionales_array as $key => $value) {
                    $servicio = $value[0];
                    $costo = ($value[1]);

                    $adicionales_desglose[] = array(
                        $servicio,
                        $grupo.' Mascota'.$plural_tamanos,
                        number_format( $costo, 2, ',', '.'),
                        number_format( ($costo*$grupo), 2, ',', '.')
                    );
                }
            }

            $transporte_desglose = array();
            if( count($transporte) > 0 ){
                foreach ($transporte as $key => $value) {
                    $servicio = $value[0];
                    $costo = ($value[1]);

                    $transporte_desglose[] = array(
                        $servicio,
                        'Precio por Grupo',
                        number_format( $costo, 2, ',', '.'),
                        number_format( $costo, 2, ',', '.')
                    );
                }
            }

            $pago = ($detalles_reserva['_line_subtotal']);
            $desglose = unserialize($detalles_reserva['_wc_deposit_meta']);
            $descuento = $metas_orden["_cart_discount"][0];

            if( $desglose['enable'] == "no" ){
                $desglose['deposit'] = $pago;
                if( $metas_orden["_cart_discount"][0]+0 > 0 ){
                    $desglose['deposit'] = $pago-$metas_orden["_cart_discount"][0];
                }
            }else{
                $deposito = $desglose['deposit'];
            }

            $diferencia = 0;
            $pago_descuentos = $desglose['deposit']+$metas_orden["_cart_discount"][0];
            $comision = ($pago-($pago/1.2));

            if( $comision < $pago_descuentos ){
                $diferencia = $pago_descuentos-$comision;
            }

            if( $metas_orden['_payment_method_title'][0] != "" ){
                $pagado_con = $metas_orden['_payment_method_title'][0];
            }else{
                $pagado_con = "";
            }

            $aceptar_rechazar = array(
                "aceptar" => get_home_url().'/perfil-usuario/reservas/confirmar/'.$id,
                "cancelar" => get_home_url().'/perfil-usuario/reservas/cancelar/'.$id
            );

            /* DATA CUIDADOR */

                $cuidador_id = $producto->post_author;
                $cuidador = $wpdb->get_row("SELECT * FROM cuidadores WHERE user_id = '".$cuidador_id."'");
                $metas_cuidador = get_user_meta($cuidador_id);
                $name_cuidador = $wpdb->get_var("SELECT post_title FROM wp_posts WHERE ID='{$cuidador->id_post}'");
                $email_cuidador = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID='{$cuidador_id}'");
                $telefonos_cuidador = $metas_cuidador["user_phone"][0]." / ".$metas_cuidador["user_mobile"][0];
                $dir_cuidador = $cuidador->direccion;

            /* DATA CLIENTE */

                $cliente_id = $metas_reserva["_booking_customer_id"][0];
                $metas_cliente = get_user_meta($cliente_id);

                $nombre = $metas_cliente["first_name"][0];
                $apellido = $metas_cliente["last_name"][0];
                $name_cliente = $nombre." ".$apellido;

                $email_cliente = $wpdb->get_var("SELECT user_email FROM wp_users WHERE ID='{$cliente_id}'");
                $telefonos_cliente = $metas_cliente["user_phone"][0]." / ".$metas_cliente["user_mobile"][0];

                $mascotas_cliente = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_author = '{$cliente_id}' AND post_type='pets' AND post_status = 'publish'");

                $comportamientos_array = array(
                    "pet_sociable"           => "Sociables",
                    "pet_sociable2"          => "No sociables",
                    "aggressive_with_pets"   => "Agresivos con perros",
                    "aggressive_with_humans" => "Agresivos con humanos",
                );
                $tamanos_array = array(
                    "Pequeño",
                    "Mediano",
                    "Grande",
                    "Gigante"
                );
                
                $mascotas = array();

                foreach ($mascotas_cliente as $key => $mascota) {
                    $data_mascota = get_post_meta($mascota->ID);
                    $temp = array();
                    foreach ($data_mascota as $key => $value) {
                        switch ($key) {
                            case 'pet_sociable':
                                if( $value[0] == 1 ){ $temp[] = "Sociable"; }else{ $temp[] = "No sociable"; }
                            break;
                            case 'aggressive_with_pets':
                                if( $value[0] == 1 ){ $temp[] = "Agresivo con perros"; }
                            break;
                            case 'aggressive_with_humans':
                                if( $value[0] == 1 ){ $temp[] = "Agresivo con humanos"; }
                            break;
                        }
                    }
                    $data_mascota['birthdate_pet'][0] = str_replace("/", "-", $data_mascota['birthdate_pet'][0]);
                    $anio = strtotime($data_mascota['birthdate_pet'][0]);
                    $edad_time = strtotime(date("Y-m-d"))-$anio;
                    $edad = (date("Y", $edad_time)-1970)." año(s) ".date("m", $edad_time)." mes(es)";

                    $raza = $wpdb->get_var("SELECT nombre FROM razas WHERE id=".$data_mascota['breed_pet'][0]);
                    if( $raza == "" ){
                        $raza = "Affenpinscher";
                    }
                    $mascotas[] = array(
                        "nombre" => $data_mascota['name_pet'][0],
                        "raza" => $raza,
                        "edad" => $edad,
                        "tamano" => $tamanos_array[ $data_mascota['size_pet'][0] ],
                        "conducta" => implode("<br>", $temp)
                    );
                }

            $detalle = array(
                "aceptar_rechazar" => $aceptar_rechazar,
                "id_reserva" => $reserva->ID,
                "id_orden" => $id,


                "variaciones" => $variaciones,
                "transporte" => $transporte_desglose,
                "adicionales" => $adicionales_desglose,

                "desglose" => $desglose,
                "reembolsar" => $diferencia,
                "descuento" => $descuento,


                "metodo_pago" => $metas_orden['_payment_method_title'][0],
                "pdf" => $metas_orden['_openpay_pdf'][0],

                "servicio" => $producto->ID,
                "servicio_titulo" => $producto->post_title,
                "cuidador" => $producto->post_author,
                "cliente" => $metas_reserva["_booking_customer_id"][0],
                "inicio" => $inicio,
                "fin" => $fin
            );

            $desglose["deposit"] = $desglose["deposit"];
            $desglose["reembolsar"] = $diferencia;
            $desglose["descuento"] = $descuento;

            $tipo_pago = "PAGO";
            if( $metas_orden['_payment_method_title'][0] == "Tienda" ){ $tipo_pago = "PAGO EN TIENDA"; }

            $correos = array(

                "cliente" => array(
                    "id" => $cliente_id,
                    "nombre" => $name_cliente,
                    "telefono" => $telefonos_cliente,
                    "email" => $email_cliente,
                    "mascotas" => $mascotas
                ),
                "cuidador" => array(
                    "id" => $cuidador_id,
                    "nombre" => $name_cuidador,
                    "telefono" => $telefonos_cuidador,
                    "email" => $email_cuidador,
                    "direccion" => $dir_cuidador
                ),
                "servicio" => array(
                    "id_reserva" => $reserva->ID,
                    "id_orden" => $id,
                    "duracion" => $dias.' '.$dias_noches,
                    "tipo" => $tipo_servicio,
                    "inicio" => strtotime( str_replace("/", "-", $inicio) ),
                    "fin" => strtotime( str_replace("/", "-", $fin) ),
                    "metodo_pago" => $metas_orden['_payment_method_title'][0],
                    "desglose" => $desglose,
                    "tipo_pago" => $tipo_pago,

                    "variaciones" => $variaciones,
                    "transporte" => $transporte_desglose,
                    "adicionales" => $adicionales_desglose,

                    "pdf" => $metas_orden['_openpay_pdf'][0],
                    "vence" => $metas_orden['_openpay_tienda_vence'][0],

                    "aceptar_rechazar" => $aceptar_rechazar,

                    "checkin" => date("g:i a", strtotime( $metas_reserva['_booking_checkin'][0] ) ),
                    "checkout" => date("g:i a", strtotime( $metas_reserva['_booking_checkout'][0] ) )
                ),
            );

            $respuesta = $detalle;
            if( $email ){ $respuesta = $correos; }

            return $respuesta;

        }
    }
?>