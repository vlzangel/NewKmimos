<?php
	extract($_GET);
	$page = file_get_contents($url);

	preg_match_all('#td-main-content-wrap(.?)td_screen_width#im', $page, $matches);

	preg_replace('#<script(.?)</script>#im', replacement, subject)

	echo "<pre>";
		print_r($matches);
	echo "</pre>";
?>