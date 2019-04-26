<?php

	$PATH_TEMPLATE = (dirname(dirname(dirname(__DIR__))));
    
    global $wpdb;

    $id_orden = vlz_get_page();

    $acc = "CCL"; $usu = "CUI";

    $_GET["CONFIRMACION"] = "YES";

    include($PATH_TEMPLATE."/procesos/reservar/emails/index.php");

    // if( $_GET["CONFIRMACION"] == "YES" ){
    	$CONTENIDO .= "<a class='km-btn-primary volver_msg' href='".get_home_url()."/perfil-usuario/reservas/'>Volver</a>";
    /* }else{
    	$CONTENIDO .= "
    		<a style='display: inline-block; margin-left: 15px !important;' class='km-btn-primary volver_msg' href='".get_home_url()."/perfil-usuario/reservas/cancelar/".$id_orden."?u=cui&CONFIRMACION=YES'>SI</a>
    		<a class='km-btn-primary volver_msg' href='".get_home_url()."/perfil-usuario/reservas/'>NO</a>
    	";
    } */
?>