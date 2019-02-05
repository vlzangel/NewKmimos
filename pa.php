<?php
	extract($_GET);
	$page = file_get_contents($url);

	preg_match_all('#td-main-content-wrap(.?)td_screen_width#gi', $page, $matches);

	echo "<pre>";
		print_r($matches);
	echo "</pre>";
?>