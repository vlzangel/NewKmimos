<?php 
	require_once dirname(__FILE__) . '/lib/PHPGit/Repository.php';

	$repo = new PHPGit_Repository( dirname(__DIR__) );
	$data = $repo->git('https://gitlab.com/vlzangel/jesca.git');

?>