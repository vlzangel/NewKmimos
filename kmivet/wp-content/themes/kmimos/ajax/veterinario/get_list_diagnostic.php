<?php
	$res = get_list_diagnostic($id, $level);
	die( json_encode(
		$res
	) );
?>