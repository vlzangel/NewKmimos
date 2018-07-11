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

    $facturas = $db->get_results("SELECT * FROM facturas ORDER BY fechaGeneracion ASC");

    if( $facturas != false ){
        $i = 0;
        foreach ($facturas as $key => $value) {

            $data["data"][] = array(                
                $value->fechaGeneracion,
                $value->serie . "-" . $value->reserva_id,
                $value->cuidador_id,
                $value->cliente_id,
                $value->numeroReferencia,
                $value->serieCertificado,
                $value->serieCertificadoSAT,
                $value->folioFiscalUUID,
                $value->estado,
                "<a style='padding:5px;' href='".$value->urlXml."'><i class='fa fa-cloud-download'></i> XML </a>".
                "<a style='padding:5px;' href='".$value->urlPdf."'><i class='fa fa-cloud-download'></i> PDF </a>"
            );
        }
    }

    echo json_encode($data);

?>