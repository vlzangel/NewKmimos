<?php

/*
header('Content-Encoding: UTF-8');
header('Content-Type: text/csv; charset=utf-8' );
header(sprintf( 'Content-Disposition: attachment; filename=my-csv-%s.csv', date( 'dmY-His' ) ) );
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
*/

header('Content-type: application/vnd.ms-excel; charset=utf-8' );
header(sprintf( 'Content-Disposition: attachment; filename=my-xls-%s.xls', date( 'dmY-His' ) ) );

//include_once(dirname(dirname(dirname(__DIR__))).'/wlabel.php');
//$return['message']=$_wlabel_user->LogOut();

//FILE
$module=$_POST['module'];
$urlbase=$_POST['urlbase'];
$file='/csv/detalle_'.$module.'_'.date('Ymd',time()).'.xls';
$file_path=dirname(__FILE__).$file;
$file_url=$urlbase.$file;

$text="";
$text.=$_POST['title']."\n\n";
$text.=$_POST['data'];

$data = explode("\n", $_POST['data']);
// $data = explode(";", $_POST['data']);

$HTML = '<table border="1" cellpadding="2" cellspacing="0" width="100%">
<caption>'.$_POST['title'].'</caption>';
foreach ($data as $key_1 => $info) {
	if( $info != "" ){
		$temp = explode(";", substr($info, 0, -1));
		$HTML .= '<tr>';
			$fin = count($temp);
			foreach ($temp as $key_2 => $td) {
				$HTML .= '<td>'.$td.'</td>';
			}
		$HTML .= '</tr>';
	}
}
$HTML .= '</table>';

// print_r( $HTML );

////// CREATE CSV
$handle = fopen($file_path,'w+');
fwrite($handle, utf8_decode($HTML) );
fclose($handle);

$return['message']='Exportado';//.$text
$return['file']='<a class="file" href="'.$file_url.'">Ver Excel</a>';


echo json_encode($return);
?>