<?php

class Reservas {
    
    private $db;

    public $servicio;
    public $data;
    public $reserva;
    public $sql;
    public $user_id;

    function Reservas($db, $data){
        $this->db = $db;
        $this->data = $data;
    }

    function new_reserva(){

        $this->new_order();

        $this->new_item();

        extract($this->data);

        $this->user_id = $cliente;

        $sql = "
            INSERT INTO
                wp_posts 
            VALUES (
                NULL, 
                '{$cliente}', 
                '{$hoy}',
                '{$hoy}',
                '', 
                'Reserva - {$token}', 
                '', 
                '{$status_reserva}', 
                'closed', 
                'closed', 
                '', 
                'reserva-{$token}', 
                '', 
                '', 
                '{$hoy}',
                '{$hoy}',
                '', 
                {$id_order},
                'http://www.kmimos.com.mx/?post_type=wc_booking',
                0, 
                'wc_booking', 
                '', 
                0
            );
        ";
        $this->db->multi_query($sql);

        $this->data["id_reserva"] = $this->db->insert_id();

        $this->create_metas_reserva();

        $this->update_item();
        
        return $this->data["id_order"];
    }

    function update_item(){
        extract($this->data);
        
        $sql = "UPDATE wp_woocommerce_order_itemmeta SET meta_value = '{$id_reserva}' WHERE order_item_id = {$id_item} AND meta_key = 'Reserva ID'";

        $this->db->multi_query($sql);
    }

    function create_metas_reserva(){
        extract($this->data);

        $this->servicio = $servicio;

        if( !isset($_SESSION) ){ session_start(); }
        if( $_SESSION["wlabel"] != "" ){
            // $wlabel = "(NULL, '{$id_reserva}', '_wlabel', '".$_SESSION["wlabel"]."'),";
        }

        $otros_metas = '';
        if( isset($paquete) ){ $otros_metas .= "(NULL, '{$id_reserva}', '_booking_paquete', '{$paquete}'),"; }
        if( isset($dias) ){ $otros_metas .= "(NULL, '{$id_reserva}', '_booking_dias', '{$dias}'),"; }

        if( isset($_SESSION['test_conocer']) ){ $otros_metas .= "(NULL, '{$id_reserva}', '_booking_test_conocer', '".$_SESSION['test_conocer']."'),"; }
        
        if( isset($_SESSION['landing_test']) ){ $otros_metas .= "(NULL, '{$id_reserva}', '_booking_landing_test_petco', '".$_SESSION['landing_test']."'),"; }

        $sql = "
            INSERT INTO wp_postmeta VALUES
                {$wlabel}
                {$otros_metas}
                (NULL, '{$id_reserva}', '_booking_flash',           '{$reservaFlash}'),
                (NULL, '{$id_reserva}', '_booking_checkin',         '{$checkin}'),
                (NULL, '{$id_reserva}', '_booking_checkout',        '{$checkout}'),
                (NULL, '{$id_reserva}', '_booking_customer_id',     '{$cliente}'),
                (NULL, '{$id_reserva}', '_booking_all_day',         '1'),
                (NULL, '{$id_reserva}', '_booking_start',           '{$inicio}000000'),
                (NULL, '{$id_reserva}', '_booking_end',             '{$fin}235959'),
                (NULL, '{$id_reserva}', '_booking_cost',            '{$monto}'),
                (NULL, '{$id_reserva}', '_booking_fee',             '{$fee}'),
                (NULL, '{$id_reserva}', '_booking_persons',         '{$num_mascotas}'),
                (NULL, '{$id_reserva}', '_booking_order_item_id',   '{$id_item}'),
                (NULL, '{$id_reserva}', '_booking_product_id',      '{$servicio}');
        ";

        $this->db->multi_query($sql);
    }

    function new_order(){
        extract($this->data);
        $sql = "
            INSERT INTO
                wp_posts 
            VALUES (
                NULL, 
                1, 
                '{$hoy}', 
                '{$hoy}', 
                '', 
                'Orden - {$token}', 
                '', 
                '{$status_orden}', 
                'closed', 
                'closed', 
                '', 
                'orden-{$token}', 
                '', 
                '', 
                '{$hoy}', 
                '{$hoy}', 
                '', 
                0,
                'http://www.kmimos.com.mx/?post_type=shop_order',
                0, 
                'shop_order', 
                '', 
                0
            );
        ";

        $this->db->multi_query($sql);

        $this->data["id_order"] = $this->db->insert_id();

        $this->create_metas_order();

    }

    function create_metas_order(){
        extract($this->data);
        
        $remanente = "";
        if( $deposito["enable"] == "yes" ){
            $total = $deposito["deposit"];
            $remanente = "(NULL, '{$id_order}', '_wc_deposits_remaining', '{$deposito["remaining"]}'),";
        }else{
            $total = $monto;
            $remanente = "(NULL, '{$id_order}', '_wc_deposits_remaining', '0'),";
        }

        $sql = "
            INSERT INTO wp_postmeta VALUES
            {$remanente}
            (NULL, '{$id_order}', '_customer_user',                         '{$cliente}'),
            
            (NULL, '{$id_order}', '_order_total',                           '{$total}'),
            (NULL, '{$id_order}', '_order_fee',                             '{$fee}'),
            (NULL, '{$id_order}', '_cart_discount',                         '{$descuento}'),

            (NULL, '{$id_order}', '_order_key',                             'wc_order_{$token}'),
            (NULL, '{$id_order}', '_order_stock_reduced',                   '1'),
            (NULL, '{$id_order}', '_cart_discount_tax',                     '0'),
            (NULL, '{$id_order}', '_order_version',                         '2.5.5'),
            (NULL, '{$id_order}', '_payment_method',                        '{$metodo_pago}'),
            (NULL, '{$id_order}', '_recorded_sales',                        'yes'),
            (NULL, '{$id_order}', '_download_permissions_granted',          '1'),
            (NULL, '{$id_order}', '_order_shipping_tax',                    '0'),
            (NULL, '{$id_order}', '_order_tax',                             '0'),
            (NULL, '{$id_order}', '_order_shipping',                        ''),
            (NULL, '{$id_order}', '_payment_method_title',                  '{$metodo_pago_titulo}'),
            (NULL, '{$id_order}', '_created_via',                           'checkout'),
            (NULL, '{$id_order}', '_customer_user_agent',                   ''),
            (NULL, '{$id_order}', '_customer_ip_address',                   '::1'),
            (NULL, '{$id_order}', '_prices_include_tax',                    'yes'),
            (NULL, '{$id_order}', '_order_currency',                        '{$moneda}');
        ";

        $this->db->multi_query( $sql );
    }

    function new_item(){
        extract($this->data);

        $sql = "
            INSERT INTO
                wp_woocommerce_order_items
            VALUES (
                NULL, 
                '{$titulo_servicio}', 
                'line_item', 
                '{$id_order}'
            );
        ";

        $this->db->multi_query($sql);

        $this->data["id_item"] = $this->db->insert_id();
        
        $this->create_metas_item();
    }

    function create_metas_item(){
        extract($this->data);

        $mascotas = "";
        foreach ($this->data["mascotas"] as $key => $value) {
            $mascotas .= "(NULL, '{$id_item}', '{$key}', '{$value}'),";
        }

        $adicionales = "";
        foreach ($this->data["adicionales"] as $value => $key) {
            $adicionales .= "(NULL, '{$id_item}', '{$key}', '{$value}'),";
        }

        if( $transporte != "" ){
            $transporte = "(NULL, '{$id_item}', 'Servicios de Transportación (precio por grupo) (&#36;{$transporte[1]})', '{$transporte[0]}'),";
        }

        $deposito = serialize($deposito);

        $mascotas = str_replace('"', '\"', $mascotas);

        $sql = "
            INSERT INTO wp_woocommerce_order_itemmeta VALUES
            (NULL, '{$id_item}', 'Reserva ID', '{$id_reserva}'),
            (NULL, '{$id_item}', 'Duración', '{$duracion_formato}'),

            {$mascotas}
            {$adicionales}
            {$transporte}

            (NULL, '{$id_item}', '_line_total',    '{$monto}'),
            (NULL, '{$id_item}', '_line_subtotal', '{$monto}'),
            (NULL, '{$id_item}', 'Fecha de Reserva', '{$fecha_formato}'),
            (NULL, '{$id_item}', '_product_id', '{$servicio}'),
            (NULL, '{$id_item}', 'Ofrecido por', '{$cuidador}'),
            (NULL, '{$id_item}', '_wc_deposit_meta', '{$deposito}'),

            (NULL, '{$id_item}', '_line_tax_data', 'a:2:{s:5:\"total\";a:0:{}s:8:\"subtotal\";a:0:{}}'),
            (NULL, '{$id_item}', '_line_tax', '0'),
            (NULL, '{$id_item}', '_line_subtotal_tax', '0'),
            (NULL, '{$id_item}', '_variation_id', '0'),
            (NULL, '{$id_item}', '_tax_class', ''),
            (NULL, '{$id_item}', '_qty', '1');
        ";

        $this->sql = $sql;

        $this->db->multi_query( utf8_decode($sql) );
    }

    function aplicarCupones($params){
        extract($params);

        if( !isset($_SESSION)){ session_start(); }

        foreach ($cupones as $key => $cupon) {
            $this->db->query( utf8_decode( "INSERT INTO wp_woocommerce_order_items VALUES (NULL, '{$cupon[0]}', 'coupon', '{$order}');" ) );
            $id_item = $this->db->insert_id();
            $sql = "
                INSERT INTO wp_woocommerce_order_itemmeta VALUES
                    (NULL, '{$id_item}', 'discount_amount',     '{$cupon[1]}'),
                    (NULL, '{$id_item}', 'discount_amount_tax', '0');
            ";
            $this->db->multi_query( utf8_decode($sql) );
            $id_seccion = 'MR_'.$this->servicio."_".md5($this->user_id);
            $xsaldo = $this->db->get_var("SELECT meta_value FROM wp_usermeta WHERE user_id='{$this->user_id}' AND meta_key='kmisaldo'");
            $saldo = $xsaldo;
            if( strpos($cupon[0], "saldo") !== false  ){
                if( isset($_SESSION[$id_seccion] ) ){
                    $saldo_temporal = $saldo+$_SESSION[$id_seccion]['saldo_temporal'];
                    if( $cupon[1] < $saldo_temporal ){
                        $saldo = $saldo_temporal-$cupon[1];
                    }else{
                        $saldo = 0;
                    }
                }else{
                    $saldo -= $cupon[1];
                    if( $saldo < 0){ $saldo = 0; }
                }
                $saldo = ( $saldo < 0 ) ? 0 : $saldo ;
                if( $xsaldo === false ){
                    $this->db->query("INSERT INTO wp_usermeta VALUES (NULL, {$this->user_id}, 'kmisaldo', '{$saldo}');");
                }else{
                    $this->db->query("UPDATE wp_usermeta SET meta_value = '{$saldo}' WHERE user_id = {$this->user_id} AND meta_key = 'kmisaldo';");
                }
            }else{
                $id_cupon = $this->db->get_var("SELECT ID FROM wp_posts WHERE post_title='{$cupon[0]}' AND post_type='shop_coupon'");

                $especiales = [
                    "1ngpet",
                    "2pgpet",
                    "2ngpet",
                    "3pgpet",
                    "2pagk", // Agregado el 03-04-19
                ];
                if( in_array($cupon[0], $especiales) ){
                    $uso_cupon = $this->db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_cupon} AND meta_key = 'uso_{$this->user_id}' ");
                    if( $uso_cupon == false ){
                        $data = json_encode([
                            "ordenes" => [$order],
                            "disponible" => $cupon[3]
                        ]);
                        $this->db->query( utf8_decode( "INSERT INTO wp_postmeta VALUES (NULL, '{$id_cupon}', 'uso_{$this->user_id}', '{$data}');" ) );
                    }else{
                        $uso_cupon = json_decode($uso_cupon);
                        $uso_cupon->ordenes[] = $order;
                        $uso_cupon = json_encode([
                            "ordenes" => $uso_cupon->ordenes,
                            "disponible" => $cupon[3]
                        ]);
                        $this->db->query( utf8_decode( "UPDATE wp_postmeta SET meta_value = '{$uso_cupon}' WHERE post_id = {$id_cupon} AND meta_key = 'uso_{$this->user_id}' " ) );
                    }
                }

                $this->db->query( utf8_decode( "INSERT INTO wp_postmeta VALUES (NULL, '{$id_cupon}', '_used_by', '{$this->user_id}');" ) );
                $this->db->query("UPDATE wp_postmeta SET meta_value = (meta_value + 1) WHERE post_id = {$id_cupon} AND meta_key = 'usage_count'");

            }
        }

    }

}




                
                /*switch ( $cupon[0] ) {

                    case '2pgpet':
                        $veces_usado = $this->db->get_results("SELECT * FROM wp_postmeta WHERE post_id = {$id_cupon} AND meta_key = '_used_by' AND meta_value = '{$this->user_id}'");
                        if( $veces_usado !== false ){
                            $datos = json_decode( $this->db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_cupon} AND meta_key = 'paseos_{$this->user_id}' ") );
                            
                            $cont_paseos = [];
                            for ($i=0; $i < $duracion; $i++) { 
                                foreach ($mascotas as $mascota) {
                                    if( is_array($mascota) ){
                                        if( $mascota[0]+0 > 0 ){
                                            $cont_paseos[] = $mascota[0]*$mascota[1];
                                        }
                                    }
                                }
                            }

                            $num = count($cont_paseos);
                            $num = $datos->disponibles-$num;
                            $num = ( $num >= 0 ) ? $num: 0;
                            $datos->orden[] = $order;
                            $paseos = json_encode([
                                "orden" => $datos->orden,
                                "disponibles" => $num
                            ]);
                            $this->db->query( utf8_decode( "UPDATE wp_postmeta SET meta_value = '{$paseos}' WHERE post_id = {$id_cupon} AND meta_key LIKE 'paseos_{$this->user_id}' " ) );
                        }else{
                            $paseos = json_encode([
                                "orden" => [$order],
                                "disponibles" => 2
                            ]);
                            $this->db->query( utf8_decode( "INSERT INTO wp_postmeta VALUES (NULL, '{$id_cupon}', 'paseos_{$this->user_id}', '{$paseos}');" ) );
                        }
                    break;

                    case '2ngpet':
                        $veces_usado = $this->db->get_results("SELECT * FROM wp_postmeta WHERE post_id = {$id_cupon} AND meta_key = '_used_by' AND meta_value = '{$this->user_id}'");
                        if( $veces_usado !== false ){
                            $datos = json_decode( $this->db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_cupon} AND meta_key = 'usos_{$this->user_id}' ") );
                            
                            $cont_paseos = [];
                            for ($i=0; $i < $duracion; $i++) { 
                                foreach ($mascotas as $mascota) {
                                    if( is_array($mascota) ){
                                        if( $mascota[0]+0 > 0 ){
                                            $cont_paseos[] = $mascota[0]*$mascota[1];
                                        }
                                    }
                                }
                            }

                            $num = count($cont_paseos);
                            $num = $datos->disponibles-$num;
                            $num = ( $num >= 0 ) ? $num: 0;
                            $datos->orden[] = $order;
                            $usos = json_encode([
                                "orden" => $datos->orden,
                                "disponibles" => $num
                            ]);
                            $this->db->query( utf8_decode( "UPDATE wp_postmeta SET meta_value = '{$usos}' WHERE post_id = {$id_cupon} AND meta_key LIKE 'usos_{$this->user_id}' " ) );
                        }else{
                            $usos = json_encode([
                                "orden" => [$order],
                                "disponibles" => 2
                            ]);
                            $this->db->query( utf8_decode( "INSERT INTO wp_postmeta VALUES (NULL, '{$id_cupon}', 'usos_{$this->user_id}', '{$usos}');" ) );
                        }
                    break;

                    case '3pgpet':
                        $veces_usado = $this->db->get_results("SELECT * FROM wp_postmeta WHERE post_id = {$id_cupon} AND meta_key = '_used_by' AND meta_value = '{$this->user_id}'");
                        if( $veces_usado !== false ){
                            $datos = json_decode( $this->db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$id_cupon} AND meta_key = 'paseos_{$this->user_id}' ") );
                            
                            $cont_paseos = [];
                            for ($i=0; $i < $duracion; $i++) { 
                                foreach ($mascotas as $mascota) {
                                    if( is_array($mascota) ){
                                        if( $mascota[0]+0 > 0 ){
                                            $cont_paseos[] = $mascota[0]*$mascota[1];
                                        }
                                    }
                                }
                            }

                            $num = count($cont_paseos);
                            $num = $datos->disponibles-$num;
                            $num = ( $num >= 0 ) ? $num: 0;
                            $datos->orden[] = $order;
                            $paseos = json_encode([
                                "orden" => $datos->orden,
                                "disponibles" => $num
                            ]);
                            $this->db->query( utf8_decode( "UPDATE wp_postmeta SET meta_value = '{$paseos}' WHERE post_id = {$id_cupon} AND meta_key LIKE 'paseos_{$this->user_id}' " ) );
                        }else{
                            $paseos = json_encode([
                                "orden" => [$order],
                                "disponibles" => 3
                            ]);
                            $this->db->query( utf8_decode( "INSERT INTO wp_postmeta VALUES (NULL, '{$id_cupon}', 'paseos_{$this->user_id}', '{$paseos}');" ) );
                        }
                    break;

                }*/

?>