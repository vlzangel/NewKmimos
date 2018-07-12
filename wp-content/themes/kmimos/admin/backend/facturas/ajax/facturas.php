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

    $where = '';
    if( isset($ini) && !empty($ini) ){ 
        $where = " where  fechaGeneracion >= '{$ini} 00:00:00' "; 
        if( isset($fin) && !empty($fin) ){ 
            $where .= " and fechaGeneracion <= '{$fin} 23:59:59' "; 
        }
    }

    $facturas = $db->get_results("SELECT * FROM facturas {$where} ORDER BY fechaGeneracion ASC");

    if( $facturas != false ){
        $i = 0;
        foreach ($facturas as $key => $value) {

            $cuidador_name = $db->get_var( "SELECT display_name FROM wp_users WHERE ID = ".$value->cuidador_id );
            $cliente_name = $db->get_var( "SELECT display_name FROM wp_users WHERE ID = ".$value->cliente_id );

            $data["data"][] = array(
                "<input type='checkbox' data-type='fact_selected' name='fact_selected[]' value='".$value->reserva_id.'_'.$value->numeroReferencia."'>",
                $value->fechaGeneracion,
                $value->serie . "-" . $value->reserva_id,
                $cuidador_name,
                $cliente_name,
                $value->numeroReferencia,
                $value->serieCertificado,
                $value->serieCertificadoSAT,
                $value->folioFiscalUUID,
                $value->receptor,
                $value->estado,
                "<a style='padding:5px;' href='".$value->urlXml."'><i class='fa fa-cloud-download'></i> XML </a>".
                "<a style='padding:5px;' href='".$value->urlPdf."'><i class='fa fa-cloud-download'></i> PDF </a>"
            );
        }
    }

    echo json_encode($data);

?>