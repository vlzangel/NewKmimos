<?php

    function save_user_accept_terms ($user_id, $db){
        date_default_timezone_set('America/Mexico_City');

        $IP = get_client_ip();
        $HF = date("Y-m-d H:i:s");
        $UA = $_SERVER["HTTP_USER_AGENT"];

        $db->query("INSERT INTO terminos_aceptados VALUES ( NULL, '{$user_id}', '{$IP}', '{$HF}', '{$UA}' );");
        
    }

    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

?>