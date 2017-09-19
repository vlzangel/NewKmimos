<?php

$file = fopen("archivo.txt", "w");
fwrite($file, json_encode($_GET) . PHP_EOL);
fclose($file);