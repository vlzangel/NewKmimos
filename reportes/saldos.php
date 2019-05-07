<?php
	include dirname(__DIR__)."/wp-load.php";

	$titulo = "Saldos a Favor";
	$name = "saldo_".date('Ymd_His',time()).".xls";

	/*
	header('Content-type: application/vnd.ms-excel; charset=utf-8' );
	header(sprintf( 'Content-Disposition: attachment; filename=%s', $name ) );
	*/

	global $wpdb;

	//FILE
	$file = 'excels/'.$name;
	$file_path = dirname(__FILE__)."/".$file;

	$data = $wpdb->get_results("
		SELECT 
			u.ID AS id,
			n.meta_value AS nombre,
			a.meta_value AS apellido,
			u.user_email AS email,
			m.meta_value AS saldo
		FROM 
			wp_usermeta AS m
		INNER JOIN wp_users AS u ON ( u.ID = m.user_id )
		INNER JOIN wp_usermeta AS n ON ( u.ID = n.user_id )
		INNER JOIN wp_usermeta AS a ON ( u.ID = a.user_id )
		WHERE 
			n.meta_key = 'first_name' AND
			a.meta_key = 'last_name' AND
			m.meta_key = 'kmisaldo' AND
			m.meta_value > 0
		GROUP BY u.ID
	");

	/*
	echo "<pre>";
		print_r($data);
	echo "</pre>";
	*/

	$HTML = '<table border="1" cellpadding="2" cellspacing="0" width="100%">
	<caption>'.$titulo.'</caption>';
	foreach ($data as $key_1 => $info) {
		$HTML .= '<tr>';
			$HTML .= '<td>'.$info->id.'</td>';
			$HTML .= '<td>'.$info->nombre.'</td>';
			$HTML .= '<td>'.$info->apellido.'</td>';
			$HTML .= '<td>'.$info->email.'</td>';
			$HTML .= '<td align="right">'.$info->saldo.'</td>';
		$HTML .= '</tr>';
	}
	$HTML .= '</table>';

	$handle = fopen($file_path,'w+');
		fwrite($handle, utf8_decode($HTML) );
	fclose($handle);

	echo "<a href='".get_home_url()."/reportes/".$file."'>Descargar</a>";
?>