<?php
	$res = search_medicine($query);

	die( json_encode(
		$res
	) );
?>