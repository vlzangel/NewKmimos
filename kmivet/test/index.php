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

    include dirname(__DIR__).'/wp-load.php';

    global $wpdb;

    $cita_id = $_GET['cita_id'];

    $reserva = $wpdb->get_row("SELECT * FROM {$pf}reservas WHERE cita_id = '{$cita_id}' ");
    $veterinario = $wpdb->get_row("SELECT * FROM {$pf}veterinarios WHERE veterinario_id = '{$reserva->veterinario_id}' ");
    $INFORMACION = (array) json_decode( $reserva->info_email );
    $appointment = get_appointment($cita_id);

    $INFORMACION["AVATAR_URL"] = kmimos_get_foto($veterinario->user_id);
    $INFORMACION["DIAGNOSTICO"] = $appointment['result']->diagnostic->diagnostic->title;
    $INFORMACION["DIAGNOSTICO_NOTA"] = $appointment['result']->diagnostic->notes;
    $INFORMACION["TRATAMIENTO"] = $appointment['result']->treatment;

    $INFORMACION = [
        "VETERINARIO" => "Medico Medico Prueba Kmivet",
        "CEDULA" => "4363749922",
        "PACIENTE" => "Paciente Cuatro",
        "EDAD" => "41",
        "TRATAMIENTO" => "Tylex flu una cada 8 hrs por 5 días Celestamine ns una cada 12 hrs por 5 días Naproxeno 250 mg una cada 12 hrs por 5 días",
    ];

    foreach ($INFORMACION as $key => $value) {
        $html = str_replace('['.$key.']', $value, $html);
    }

    $path = dirname(__DIR__)."/wp-content/uploads/recipes/". $cita_id;

    if( !file_exists($path) ){
        mkdir( $path );
    }

    $dompdf->loadHtml( $html );
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $output = $dompdf->output();
    file_put_contents(  $path.'/recipe.pdf' , $output);

    echo $path.'/recipe.pdf';
?>