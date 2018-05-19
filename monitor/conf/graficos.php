<?php
$ruta = get_home_url()."/monitor/recursos/amcharts/";
echo "

        <link rel='stylesheet' href='{$ruta}graficos_style.css' type='text/css'>
        <link rel='stylesheet' href='{$ruta}export.css' type='text/css' media='all' />

        <script src='{$ruta}amcharts.js' 	type='text/javascript'></script>
        <script src='{$ruta}serial.js' 	type='text/javascript'></script>
        <script src='{$ruta}themes/light.js'    type='text/javascript'></script>
        <script src='{$ruta}themes/patterns.js' type='text/javascript'></script>
        <script src='{$ruta}themes/chalk.js'    type='text/javascript'></script>
        <script src='{$ruta}lang/es.js'    	 type='text/javascript'></script>
        <script src='{$ruta}export.min.js'></script>

";
