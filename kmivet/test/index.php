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

    $INFORMACION = [
        "VETERINARIO" => "CESAR ALEJANDRO DE LA ROSA VELAZQUEZ",
        "CEDULA" => "11076555",
        "PACIENTE" => "Rob Cuevas",
        "EDAD" => "41",
        "TRATAMIENTO" => "Tylex flu una cada 8 hrs por 5 días Celestamine ns una cada 12 hrs por 5 días Naproxeno 250 mg una cada 12 hrs por 5 días",
    ];

    foreach ($INFORMACION as $key => $value) {
        $html = str_replace('['.$key.']', $value, $html);
    }

    $dompdf->loadHtml( $html );
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $output = $dompdf->output();
    file_put_contents('mipdf.pdf', $output);
?>