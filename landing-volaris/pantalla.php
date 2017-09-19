<?php
	if(isset($_POST))
	{
		$d = json_encode($_POST);
		$file = fopen("newsletter.csv", "a+");
		fwrite($file, $d . PHP_EOL);
		fclose($file);
	}
	print_r($d);
