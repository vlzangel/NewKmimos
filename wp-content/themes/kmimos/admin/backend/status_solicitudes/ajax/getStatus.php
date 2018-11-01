<?php
	
	extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    $solicitud = $db->get_row("SELECT * FROM wp_posts WHERE ID = '{$id}' AND post_type='request' ");
    if( $solicitud == null ){
        echo "El ID no pertenece a una solicitud de conocer";
    }else{
        $orden = $db->get_row("SELECT * FROM wp_posts WHERE ID = '{$solicitud->post_parent}'");
        echo "
            <div><label class='info_label'>Solicitud: </label> <span>{$solicitud->post_status}</span></div>
            <div>
                <label class='info_label'>Acci&oacute;n a realizar: </label>
                <span>
                    <select id='status' name='status' >
                        <option value=''>Seleccione una opci&oacute;n</option>

                        <option value='pendiente'>Pendiente</option>
                        <option value='pendiente_email'>Confirmado y enviar email</option>

                        <option value='confirmada'>Confirmada</option>
                        <option value='confirmada_email'>Confirmada y enviar email</option>

                        <option value='cancelada'>Cancelada</option>
                        <option value='cancelada_email'>Cancelada y enviar email</option>
                    </select>
                </span>
            </div>
        ";
    }
    
	exit;
?>