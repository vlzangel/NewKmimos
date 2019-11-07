<?php

    date_default_timezone_set('America/Mexico_City');

    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    $data = array(
        "data" => array()
    );

    $actual = time();

    extract($_POST);

    $where = " where receptor='".$tipo."' ";
    if( $tipo == 'cliente' ){    
        if( isset($rfc) && $rfc == 'XAXX010101000' ){
            $where .= " and rfc = 'XAXX010101000' "; 
        }else{
            $where .= " and rfc <> 'XAXX010101000' "; 
        }
    }
    if( isset($ini) && !empty($ini) ){ 
        $where .= " and fechaGeneracion >= '{$ini} 00:00:00' "; 
        if( isset($fin) && !empty($fin) ){ 
            $where .= " and fechaGeneracion <= '{$fin} 23:59:59' "; 
        }
    }

//echo "SELECT * FROM facturas {$where} ORDER BY fechaGeneracion ASC";

    $facturas = $db->get_results( "SELECT * FROM facturas {$where} ORDER BY fechaGeneracion ASC" );
 
//echo "SELECT * FROM facturas {$where} ORDER BY fechaGeneracion ASC" ; 

    if( $facturas != false ){
        $i = 0;
        foreach ($facturas as $key => $value) {

            $cuidador_name = $db->get_var( "SELECT CONCAT( nombre,' ', apellido ) as nombre FROM cuidadores WHERE user_id = ".$value->cuidador_id );
            if( $value->receptor == 'cuidador' ){
                $cliente_name = 'Kmimos';
            }else{
                $cliente_name = $db->get_var( "SELECT meta_value as name FROM wp_usermeta WHERE meta_key = 'billing_razon_social' AND user_id = ".$value->cliente_id );
            }

            $data["data"][] = array(
                "<input type='checkbox' data-type='fact_selected' name='fact_selected[]' value='".$value->reserva_id.'_'.$value->numeroReferencia."'>",
                date('d', strtotime($value->fechaGeneracion)),
                date('m', strtotime($value->fechaGeneracion)),
                date('Y', strtotime($value->fechaGeneracion)),
                $value->reserva_id,
                $value->servicio,
                $value->total,
                $value->serie . "-" . $value->reserva_id,
                utf8_encode($cuidador_name),
                utf8_encode($cliente_name),
                $value->numeroReferencia,
                $value->receptor,
                $value->estado,
                "<button style='padding:5px;' data-pdfxml='".$value->reserva_id.'_'.$value->numeroReferencia."'><i class='fa fa-cloud-download'></i> Descargar PDF y XML </button>"
            );
        }
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>