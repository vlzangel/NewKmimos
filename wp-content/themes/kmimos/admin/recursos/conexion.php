<?php

	extract($_POST);

    $raiz = dirname(dirname(dirname(dirname(dirname(((__DIR__)))))));
    include_once($raiz."/vlz_config.php");

    $tema = (((dirname(dirname(__DIR__)))));
    include_once($tema."/procesos/funciones/db.php");
    include_once($tema."/procesos/funciones/generales.php");

    $db = new db( new mysqli($host, $user, $pass, $db) );
?>