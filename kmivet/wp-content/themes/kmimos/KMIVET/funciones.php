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
                            <img style='width: 100%; margin: 20px 0px;' src='".getTema()."/KMIVET/img/CLIENTE/NUEVO/img_2.png' />
                        </div>
                        <div style='text-align: center;'>
                            <img style='width: 50%; margin: 20px 0px;' src='".getTema()."/KMIVET/img/CLIENTE/NUEVO/img_3.png' />
                        </div>
                        <div style='text-align: center;'>
                            <a href='".get_home_url()."'> <img style='width: 50%; margin: 0px;' src='".getTema()."/KMIVET/img/CLIENTE/NUEVO/img_4.png' /> </a>
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
?>
