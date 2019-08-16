<?php

    function normaliza($cadena){
        $originales = 'ÁáÉéÍíÓóÚúÑñ';
        $modificadas = 'aaeeiioouunn';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        $cadena = strtolower($cadena);
        return utf8_encode($cadena);
    }

    function comparar($s, $txt){
        $txt = str_replace(", ", " ", $txt);
        $txt = str_replace("(", " ( ", $txt);
        $txt = str_replace(")", " ) ", $txt);
        $txt = str_replace("  ", " ", $txt);
        $s = explode(" ", $s);
        $pos = 0;
        $con = 0;
        foreach ($s as $key => $_s) {
            $p = stripos(" ".$txt." ", " ".$_s." ");
            if( $p !== false ){
                $pos += $p;
                $con++;
            }
        }
        if( $con == 0 ){
            foreach ($s as $key => $_s) {
                $p = stripos($txt, $_s);
                if( $p !== false ){
                    $pos += $p;
                    $con++;
                }
            }
            return ( $con == 0 ) ? false : [$pos, 'parsial', $con];
        }
        return [$pos, 'completo', $con];
    }

	extract($_GET);

    include 'ubicaciones.php';

    $r_completo = [];
    $r_parsial = [];

    foreach ($data as $_id => $_txt) {
        $_value = normaliza($_txt);
        $posicion = comparar($s, $_value);
        if( $posicion !== false ){
            if( $posicion[1] == 'completo' ){
                $r_completo[ $posicion[2] ][ $posicion[0] ][] = "<li value='".$_id."' data-value='".$_value."'>".$_txt."</li>";
            }else{
                $r_parsial[ $posicion[2] ][ $posicion[0] ][] = "<li value='".$_id."' data-value='".$_value."'>".$_txt."</li>";
            }
        }
    }
    
    krsort($r_completo, SORT_NUMERIC);
    krsort($r_parsial, SORT_NUMERIC);

    $resp = '';
    $total = 0;

    foreach ($r_completo as $key => $info) {
        ksort($info, SORT_NUMERIC);
        foreach ($info as $primeras) {
            foreach ($primeras as $li) {
                $resp .= $li;
                $total++;
                if( $total >= 10 ){ break; }
            }
            if( $total >= 10 ){ break; }
        }
        if( $total >= 10 ){ break; }
    }

    foreach ($r_parsial as $key => $info) {
        ksort($info, SORT_NUMERIC);
        foreach ($info as $primeras) {
            foreach ($primeras as $li) {
                $resp .= $li;
                $total++;
                if( $total >= 10 ){ break; }
            }
            if( $total >= 10 ){ break; }
        }
        if( $total >= 10 ){ break; }
    }

    echo $resp;
?>