<?php 
	require_once dirname(__FILE__) . '/lib/PHPGit/Repository.php';

	$repo = new PHPGit_Repository( dirname(__DIR__) );
	$data = $repo->git('pull');
	echo "<pre>";
		print_r($data);
	echo "</pre>";
?>