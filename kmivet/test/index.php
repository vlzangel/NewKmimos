<?php
    require_once __DIR__.'/dompdf/lib/html5lib/Parser.php';
    require_once __DIR__.'/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
    require_once __DIR__.'/dompdf/lib/php-svg-lib/src/autoload.php';
    require_once __DIR__.'/dompdf/src/Autoloader.php';

    Dompdf\Autoloader::register();
    use Dompdf\Dompdf;
    $dompdf = new Dompdf();
    ob_start();
        require_once ( __DIR__.'/template/recipe.php');
    $html = ob_get_clean();

    include dirname(__DIR__).'/vlz_config.php';
    include dirname(__DIR__).'/wp-content/themes/kmimos/procesos/funciones/db.php';
    include dirname(__DIR__).'/wp-content/themes/kmimos/procesos/funciones/mediqo.php';

    function CalculaEdad( $fecha ) {
        list($Y,$m,$d) = explode("-",$fecha);
        return( date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y );
    }

    $wpdb = new db( new mysqli($host, $user, $pass, $db) );

    $cita_id = $_GET['cita_id'];
    $pf = 'wp_kmivet_';

    $appointment = get_appointment($cita_id);

    $medicamentos = "";
    foreach ($appointment['result']->prescription as $key => $medicamento) {
        $medicamentos .= $medicamento->medicine->name."(".$medicamento->medicine->presentation.") ".$medicamento->indication."<br>";
    }
    $medicamentos .= $appointment['result']->treatment;

    $INFORMACION = [
        "VETERINARIO" => $appointment['result']->medic->firstName.' '.$appointment['result']->medic->lastName,
        "CEDULA"      => $appointment['result']->medic->medicInfo->professionalLicenceNumber,
        "PACIENTE"    => $appointment['result']->patient->firstName.' '.$appointment['result']->patient->lastName,
        "EDAD"        => CalculaEdad( $appointment['result']->patient->birthday ),
        "TRATAMIENTO" => $medicamentos
    ];

    foreach ($INFORMACION as $key => $value) {
        $html = str_replace('['.$key.']', $value, $html);
    }

    $path = dirname(__DIR__)."/wp-content/uploads/recipes/". $cita_id;

    if( !file_exists($path) ){ mkdir( $path ); }

    $dompdf->loadHtml( $html );
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $output = $dompdf->output();
    file_put_contents(  $path.'/recipe.pdf' , $output);

    echo get_home_url()."/wp-content/uploads/recipes/".$cita_id.'/recipe.pdf';
?>