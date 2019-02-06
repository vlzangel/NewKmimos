<?php
	extract($_GET);
	$page = file_get_contents($url);

	$page = preg_replace("/[\r\n|\n|\r]+/", " ", $page);

	preg_match_all("#td-main-content-wrap(.*?)</article>#im", $page, $matches);

	preg_match_all("#<h1(.*?)>(.*?)</h1#im", $matches[0][0], $matches_2);

	preg_match_all('#td-post-featured-image.*?<img.*?src="(.*?)"#im', $matches[0][0], $matches_3);

	preg_match_all('#end A(.*?)<p(.*?)>(.*?)<!--#im', $matches[0][0], $matches_4);

	// preg_match_all('#article(.*?)</article#im', $matches[0][0], $matches_3);
	

		// print_r($matches_3);
		// print_r($matches[0][0]);
		// print_r($matches_2[2][0]);


		echo "<h1>".$matches_2[2][0]."</h1>";
		echo "<img src='".$matches_3[1][0]."' />";

		//print_r($matches[0][0]);

		echo "<p>".$matches_4[3][0];

		// print_r($matches[0][0]);
	
	
	// echo $page;
?>

<style>
body {
    width: 768px;
    margin: 50px auto;
    text-align: justify;
    font-size: 16px;
    font-family: Arial;
}

img{
    text-align: center;
    display: block;
    margin: 0px auto;
}
</style>