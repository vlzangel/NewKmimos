<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies
header("Content-Type: application/json; charset=utf-8");

date_default_timezone_set('America/Mexico_City');

$__time = date('Y-m-d H:i:s');

/*
//echo 'algo: '.$JSONstar;
$fo = fopen("suscripcion.csv","w"); 
fwrite($fo,$JSONvariable);
fclose($fo);
*/

include dirname(dirname(dirname(dirname(__DIR__)))).'/wp-load.php';

global $wpdb;



function mail_validate($mail){
	if($mail!='' && strpos($mail,'@')!==FALSE){
		return true;

	}else{
		return false;

	}
}


$file='subscription.csv';

$mail=$_POST['mail'];
$section=$_POST['section'];

$mail_exist='';
$datos=array();

$return=array();
$return['result']=true;
$return['message']='';
$return['data']='';


if(mail_validate($mail)){
	$fo = fopen($file, "r");
	while($data = fgetcsv ($fo,0,";")) {
		$datos[]=$data[0];
		if($data[0]==$mail){
			$mail_exist='y';
			//break;
		}
	}
	fclose($fo);


	if($mail_exist==''){
		$datos[]=$mail;
	}

	$fo = fopen($file, "w");
	foreach($datos as $dato){
		fwrite($fo,$dato."\n");
	}
	fclose($fo);

	//BD
	include_once(__DIR__.'/subscribe.php');
	$table = $_subscribe->table;
	$result = $_subscribe->result("SELECT * FROM $table WHERE email = '$mail'");
	if(count($result)==0){

		$wpdb->query(
			"
				INSERT INTO
					wp_kmimos_subscribe
				VALUES(
					NULL,
					NULL,
					'{$mail}',
					'{$section}',
					'{$__time}'
				);
			"
		);

		$return['message']='Registro Exitoso. Por favor revisa tu correo en la Bandeja de Entrada o en No Deseados';
		$return['result']=true;
	}else{
		$return['result']=true;
		$return['message']='Este correo ya est&aacute; registrado. Por favor intenta con uno nuevo';
	}

}else{
	$return['result']=false;
	$return['message']='El email es incorrecto';
}
$return['data']=$datos;
echo json_encode($return);
?>