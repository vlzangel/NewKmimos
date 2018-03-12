<?php 
	
	include("../../../../../vlz_config.php");
	include("../funciones/db.php");

	$conn = new mysqli($host, $user, $pass, $db);
	$db = new db($conn);

 	$cantpost = $db->get_results("select tx.count as cantidad,tx.term_id as id from wp_terms t inner join wp_term_taxonomy tx on t.term_id=tx.term_id where  t.slug='destacado'");
						foreach ($cantpost as $cant) { 
							$numeropost=$cant->cantidad;
							$id=$cant->id;
						}


	$cantpost2 = $db->get_results("select tx.count as cantidad,tx.term_id as id from wp_terms t inner join wp_term_taxonomy tx on t.term_id=tx.term_id where  t.slug='destacados_cuidadores'");
						foreach ($cantpost2 as $cant2) { 
							$numeropost2=$cant2->cantidad;
							$id2=$cant2->id;
						}

	echo json_encode(
		array(
			   'cantidad' => $numeropost,
			   'id' => $id,
			   'cantidad2' => $numeropost2,
			   'id2' => $id2
		)
	);



?>