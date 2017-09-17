<?php
	if(isset($_GET['email']))
	{
		$file = fopen("newsletter.csv", "a+");
		fwrite($file, $_GET['email'].";" . PHP_EOL);
		fclose($file);
	}
?>
