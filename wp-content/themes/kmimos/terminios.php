<?php
    /*
        Template Name: Términos y Condiciones 
    */

	get_header();

	include __DIR__.'/terminos_HTML.php'; ?>

<style type="text/css">
	#terminos_container u {
	    font-size: 16px;
	    padding: 10px 0px;
	    display: block;
	    color: #000;
	}
	#terminos_container strong {
	    font-size: 14px;
	    color: #000;
	}
</style>
<div class="km-ficha-bg" style="background-image:url(<?php echo getTema()."/images/new/km-ficha/km-bg-ficha.jpg);" ?>" >
	<div class="overlay"></div>
</div>

<div id="terminos_container" class="container">
	<?php
		$parrafos = explode("\n", $HTML_TERMINOS);
		foreach ($parrafos as $parrafo) {
			echo "<p>".$parrafo."</p>";
		}
	?>
</div>

<?php get_footer();  ?>
