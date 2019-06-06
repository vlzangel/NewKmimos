<?php
    
    error_reporting(0);

    extract($_POST);

    date_default_timezone_set('America/Mexico_City');

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    function get_mascotas($cliente){
        global $db;
        $mascotas_cliente = $db->get_results("SELECT * FROM wp_posts WHERE post_author = '{$cliente}' AND post_type='pets' AND post_status = 'publish'");
        $comportamientos_array = array(
            "pet_sociable"           => "Sociables",
            "pet_sociable2"          => "No sociables",
            "aggressive_with_pets"   => "Agresivos con perros",
            "aggressive_with_humans" => "Agresivos con humanos",
        );
        $tamanos_array = array( "Pequeño", "Mediano", "Grande", "Gigante" );
        $mascotas = array();
        foreach ($mascotas_cliente as $key => $mascota) {
            $data_mascota = kmimos_get_post_meta($mascota->ID);
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
            $data_mascota['birthdate_pet'] = str_replace("/", "-", $data_mascota['birthdate_pet']);
            $edad_time = strtotime(str_replace("/", "-", $data_mascota['birthdate_pet']));
            $edad = date("d/m/Y", $edad_time);
            if( $data_mascota['pet_type'] == "2608" ){
                $raza = "Gato";
            }else{
                $raza = $db->get_var("SELECT nombre FROM razas WHERE id=".$data_mascota['breed_pet']).", ".$tamanos_array[ $data_mascota['size_pet'] ];
            }
            
            $mascotas[] = array(
                "nombre" => $data_mascota['name_pet'],
                "raza" => $raza,
                "edad" => $edad,
                "conducta" => implode("<br>", $temp)
            );
        }
        $mascotas_txt = "";
        foreach ($mascotas as $mascota) {
            $mascotas_txt .= utf8_encode($mascota['nombre'])." (".$mascota['edad'].", ".utf8_encode($mascota['raza']).", ".utf8_encode($mascota['conducta']).")<br>";
        }
        return $mascotas_txt;
    }

    $data = array( "data" => array() );
    $actual = time();

    $PERIODO = 1;
    if( date("H", $actual) > 12 ){ $PERIODO = 2; }

    $hoy = date("Y-m-d");

    $fotos = $db->get_results("
        SELECT * 
        FROM fotos AS f
        INNER JOIN wp_posts AS r ON ( r.ID = f.reserva )
        WHERE 
            f.fecha = '{$hoy}' AND
            r.post_status = 'confirmed'
    ");

    if( $fotos != false ){
        $i = 0;
        foreach ($fotos as $key => $value) {

            $cliente_id = $db->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = {$value->reserva} AND meta_key = '_booking_customer_id' ");
            $metas_cliente = kmimos_get_user_meta($cliente_id);

            $telf = array();
            $telf[] = $metas_cliente["user_mobile"];
            $telf[] = $metas_cliente["user_phone"];
            $telf = implode(" / ", $telf);

            $cliente_name = $metas_cliente["first_name"]." ".$metas_cliente["last_name"]." (";
            $cliente_name .= $telf.", ";
            $cliente_name .= $db->get_var("SELECT user_email FROM wp_users WHERE ID = {$cliente_id}").")";


            $cuidador = $db->get_row("SELECT * FROM cuidadores WHERE user_id = {$value->cuidador}");
            $cuidador_id = $cuidador->id_post;
            $cuidador_name = $db->get_var("SELECT post_title FROM wp_posts WHERE ID = {$cuidador_id}");

            $metas_cuidador = kmimos_get_user_meta($cuidador->user_id);
            $telf = array();
            $telf[] = $metas_cuidador["user_mobile"];
            $telf[] = $metas_cuidador["user_phone"];
            $telf = implode(" / ", $telf);

            $cuidador_name = utf8_encode($cuidador_name)." (";
            $cuidador_name .=  $telf.", ";
            $cuidador_name .=  $db->get_var("SELECT user_email FROM wp_users WHERE ID = {$cuidador->user_id}").")";

            $cliente_name = utf8_encode($cliente_name);
            $status_val = $value->subio_12+$value->subio_06;
            if( date("H", $actual) < 18 && $status_val == 1 ){ $status_val = 4; }
            if( date("H", $actual) > 11 && $status_val == 0 ){ $status_val = 3; }

            $status = ""; $status_txt = "";
            switch ( $status_val ) {
                case '0': $status = "status-inicio"; $status_txt = "Por cargar fotos"; break;
                case '1': $status = "status-ok-medio"; $status_txt = "Cargo al menos un flujo"; break;
                case '2': $status = "status-ok"; $status_txt = "Todo Bien"; break;
                case '3': $status = "status-mal"; $status_txt = "No se cargaron fotos"; break;
                case '4': $status = "status-ok-medio"; $status_txt = "Cargo al menos un flujo"; break;
                default: $status = "status-ok"; $status_txt = "Todo Bien"; break;
            }

            $moderacion = unserialize( $value->moderacion );

            $URL_BASE = get_home_url()."wp-content/uploads/fotos/";

            $PATH_BASE = dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))."/uploads/fotos/";
            $PATH_PERIODO = "/".date("Y-m-d");

            $dia = "No"; 
            if( $value->subio_12 == 1 ){ 
                $moderar = "&nbsp;";
                if( date("H", $actual) < 12 ){
                    if( isset( $moderacion[1] ) ){
                        $moderar = "Fue moderado";
                    }
                    $moderar .= " - {$moderar_imgs}";
                    $i++;
                    $dia = $moderar; 
                }else{
                    $dia = "Imagenes cargadas y enviadas"; 
                }
            }

            $noche = "No"; 
            if( $value->subio_06 == 1 ){ 
                $moderar = "&nbsp;";
                if( date("H", $actual) < 18 ){
                    if( isset( $moderacion[2] ) ){
                        $moderar = "Fue moderado";
                    }
                    $moderar .= " - {$moderar_imgs}";
                }else{
                    $noche = "Imagenes cargadas y enviadas"; 
                }
            }

            $bloqueo = "No";
            if( $value->bloqueo == 1 ){ 
                $bloqueo = "Si";
            }

            $data["data"][] = array(
                "{$value->reserva}",
                "{$cuidador_name}",
                "{$cliente_name}",
                get_mascotas( $cliente_id ),
                $dia,
                $noche,
                "{$bloqueo}",
                "{$status_txt}"
            );
        }
    }

    // echo json_encode($data);

    $file = '/files/fotos_'.date('Ymd',time()).'.xls';
    $file_path = dirname(__DIR__).$file;
    $file_url = $urlbase.'admin/backend/fotos'.$file;

    $HTML = '<table border="1" cellpadding="2" cellspacing="0" width="100%">
    <caption><h3>Reporte de fotos '.date('d/m/Y').'</h3></caption>';
    $HTML .= '<tr>';
        foreach ($title as $key_2 => $td) {
            $HTML .= '<th>'.$td.'</th>';
        }
    $HTML .= '</tr>';
    foreach ($data["data"] as $key_1 => $info) {
        $HTML .= '<tr>';
            foreach ($info as $key_2 => $td) {
                $HTML .= '<td valign="top">'.$td.'</td>';
            }
        $HTML .= '</tr>';
    }
    $HTML .= '</table>';

    $handle = fopen($file_path,'w+');
    fwrite($handle, utf8_decode($HTML) );
    fclose($handle);

    $return['file_path'] = $file_path;
    $return['file'] = $file_url;

    echo json_encode($return);
?>