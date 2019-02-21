<?php

	print_r($_REQUEST);

$file = fopen("callback.txt", "a");

fwrite($file, json_encode($_REQUEST) . PHP_EOL);
fwrite($file, '--------------------------------------------' . PHP_EOL);

fclose($file);
