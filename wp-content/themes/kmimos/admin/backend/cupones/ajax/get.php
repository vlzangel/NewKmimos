<?php
	
	extract($_POST);
    $raiz = dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (dirname(dirname(dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );

    $email = trim($email);

    $cliente = $db->get_row("SELECT * FROM wp_users WHERE user_email = '{$email}' ");
    if( $cliente == null ){
        echo "El email no se encuentra registrado";
    }else{
        $cupones = $db->get_results("SELECT * FROM wp_postmeta WHERE meta_key = '_used_by' AND meta_value = '{$cliente->ID}'");
        
        $cuponesSTR = "";

        if( $cupones == null ){
            $cuponesSTR = "Este cliente no ha usado cupones";
        }else{
            $cuponesUsados = array();
            foreach ($cupones as $value) {
                if( !in_array($value->post_id, $cuponesUsados) ){
                    $cuponesUsados[] = $value->post_id;
                    $cupon = $db->get_row("SELECT * FROM wp_posts WHERE ID = '{$value->post_id}'");
                    $cuponesSTR .= "
                        <div>
                            <label>{$cupon->post_title}</label>
                            <span data-id='{$value->post_id}' data-txt='{$cupon->post_title}' data-user='{$cliente->ID}' >
                                Borrar
                            </span>
                        </div>
                    ";
                }
            }
        }

        echo $cuponesSTR;
    }
    
	exit;
?>