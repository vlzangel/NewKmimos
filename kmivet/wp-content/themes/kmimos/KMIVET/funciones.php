<?php

    function kv_get_emails_admin(){
        return [
            'BCC: a.veloz@kmimos.la',
            // 'BCC: y.chaudary@kmimos.la',
        ];
    }

	function kv_get_email_html($content, $params = []){

        $content = buildEmailTemplate(
            $content,
            $params
        );

		extract($params);
		$telf  = '01 (55) 8526 1162';
		$What  = '+52 1 (33) 1261 41 86';
		$email  = 'contactomex@kmimos.la';
        $header = "";
        if( $_header ){
            $header = "
            <div style='text-align:center; background-color: #000;'>
                <img src='".get_recurso('img')."KMIVET/img/logo.png' style='margin: 10px; height: 40px;' />
            </div>";
        }
        $html = "
        <html>
            <head>
                <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>
                <style>
                    p{ margin:0px; }
                    a[id*='kmimos_container'] > div { max-width: 400px !important; }
                    @media max-device-width: 480px { a[id*='kmimos_container'] > div { max-width: 400px !important; } }
                </style>
            </head>
            <body>
                <div id='kmimos_container' style='font-family: Arial;'>
                    <div style='margin: 0px auto; padding: 0px; max-width: 400px;'>
                        ".$header."
                        ".$content."
                        <div style='text-align: center;'>
                            <img style='width: 50%; margin: 20px 0px;' src='".getTema()."/KMIVET/img/img_3.png' />
                        </div>
                        <div style='text-align: center;'>
                            <a href='".get_home_url()."'> <img style='width: 50%; margin: 0px;' src='".getTema()."/KMIVET/img/img_4.png' /> </a>
                        </div>
                    </div>      
                </div>
            </body>
        </html>";
        if( $test ){
            $html = str_replace(get_home_url(), "https://kmimos.com.mx/QA2", $html);
        }
        return $html;
    }
    
    function email_set_format_slug($cadena){
        $originales = [ 'Á','É','Í','Ó','Ú' ];
        $modificadas = [ 'a','e','i','o','u' ];
        foreach ($originales as $key => $value) {
            $cadena = str_replace($value, $modificadas[ $key ], $cadena);
        }
        return strtolower($cadena);
    }

    function email_set_format_name($cadena){
        $originales = [ 'Á','É','Í','Ó','Ú', 'Ñ' ];
        $modificadas = [ '&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&ntilde;' ];
        foreach ($originales as $key => $value) {
            $cadena = str_replace($value, $modificadas[ $key ], $cadena);
        }
        return mb_strtolower($cadena, 'UTF-8');
    }

    function email_set_format_precio($price){
        $temp = explode('.', $price);
        if( !isset($temp[1]) ){ $temp[1] = '00'; }
        return '<span>MXN$</span> <strong style="font-size: 25px;">'.$temp[0].',</strong><span>'.$temp[1].'</span>';
    }

    function email_set_format_ranking($img, $ranking){
        $ranking += 0;
        if( $ranking > 5 ){ $ranking = 5; }
        if( $ranking < 1 ){ $ranking = 1; }
        $_ranking = '';
        for ($i=1; $i <= $ranking; $i++) {  $_ranking .= '<img src="'.$img.'/CONSULTA/CANCELACION/hueso_full.png" style="width: 20px; margin-right: 5px;" />'; }
        if( $ranking < 5 ){ for ($i=$ranking; $i < 5; $i++) {  $_ranking .= '<img src="'.$img.'/CONSULTA/CANCELACION/hueso_vacio.png" style="width: 20px; margin-right: 5px;" />'; } }
        return $_ranking;
    }

    /* BUSQUEDA */

    function set_format_slug($cadena){
        // $cadena = utf8_encode( $cadena );
        $originales = [ 'Á','É','Í','Ó','Ú' ];
        $modificadas = [ 'a','e','i','o','u' ];
        foreach ($originales as $key => $value) {
            $cadena = str_replace($value, $modificadas[ $key ], $cadena);
        }
        return strtolower($cadena);
    }

    function set_format_name($cadena){
        $originales = [ 'Á','É','Í','Ó','Ú', 'Ñ' ];
        $modificadas = [ '&aacute;','&eacute;','&iacute;','&oacute;','&uacute;','&ntilde;' ];
        foreach ($originales as $key => $value) {
            $cadena = str_replace($value, $modificadas[ $key ], $cadena);
        }
        return mb_strtolower($cadena, 'UTF-8');
    }

    function set_format_precio($price){
        $temp = explode('.', $price);
        if( !isset($temp[1]) ){ $temp[1] = '00'; }
        return '<span>MXN$</span> <strong>'.$temp[0].',</strong><span>'.$temp[1].'</span>';
    }

    function set_format_ranking($ranking){
        $ranking += 0;
        if( $ranking > 5 ){ $ranking = 5; }
        if( $ranking < 1 ){ $ranking = 1; }
        $_ranking = '';
        for ($i=1; $i <= $ranking; $i++) {  $_ranking .= '<span class="active"></span>'; }
        if( $ranking < 5 ){ for ($i=$ranking; $i < 5; $i++) {  $_ranking .= '<span></span>'; } }
        return $_ranking;
    }

    function get_dias_meses(){
        $dias  = [     "Lunes", "Martes",  "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo" ];
        $dias_slug  = [ 
            "lunes" => "Lunes", 
            "martes" => "Martes",  
            "miercoles" => "Miércoles", 
            "jueves" => "Jueves", 
            "viernes" => "Viernes", 
            "sabado" => "Sábado", 
            "domingo" => "Domingo" 
        ];
        $_dias  = [ 
            [ "lunes", "Lunes"          ], 
            [ "martes", "Martes"        ],  
            [ "miercoles", "Miércoles"  ], 
            [ "jueves", "Jueves"        ], 
            [ "viernes", "Viernes"      ], 
            [ "sabado", "Sábado"        ], 
            [ "domingo", "Domingo"      ] 
        ];

        $meses = [ "", "Enero", "Febrero", "Marzo", "Abril",  "Mayo", "Junio",  "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ];
        $meses_slug = [ 
            "", 
            "enero" => "Enero", 
            "febrero" => "Febrero", 
            "marzo" => "Marzo", 
            "abril" => "Abril",  
            "mayo" => "Mayo", 
            "junio" => "Junio",  
            "julio" => "Julio", 
            "agosto" => "Agosto", 
            "septiembre" => "Septiembre", 
            "octubre" => "Octubre", 
            "noviembre" => "Noviembre",
            "diciembre" => "Diciembre"
        ];        

        return [ 
            'dias' => $dias, 'meses' => $meses,
            '_dias' => $_dias,
            'dias_slug' => $dias_slug, 'meses_slug' => $meses_slug
        ];
    }

    /* GENERALES */

    function get_modal($id){
        echo '
        <div id="'.$id.'" class="modal fade modal_interno" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form class="modal-content">

                    <input type="hidden" name="m" />
                    <input type="hidden" name="a" />
                    <input type="hidden" name="id" />

                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"></button>
                    </div>
                </form>
            </div>
        </div>';
    }
?>
