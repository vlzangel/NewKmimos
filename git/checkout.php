<?php 
	require_once dirname(__FILE__) . '/lib/PHPGit/Repository.php';

	$repo = new PHPGit_Repository( dirname(__DIR__) );
	$data = $repo->git('checkout -- .');
	echo "<pre>";
		print_r($data);
	echo "</pre>";
?>