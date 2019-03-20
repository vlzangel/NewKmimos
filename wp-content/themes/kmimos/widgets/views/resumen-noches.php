<?php 
	// $theme = 'light';
	$theme = 'dark'; 
?>

<link rel="stylesheet" type="text/css" href="<?php echo get_recurso('css'); ?>/widgets/panel.css">
<link rel="stylesheet" type="text/css" href="<?php echo get_recurso('css'); ?>/widgets/<?php echo $theme; ?>.css">

<section class="container titulo">
	<div class="contenedor_widget">
		<article class="wbox wbox-4 check">
			<h1>Noches</h1>
			<article class="wbox-12 check wbox-margin">
				<div class="total" id="venta_90">0</div>
				<h2>Últimos 90 días</h2>
			</article>
			<article class="wbox-12 check">
				<div class="total" id="venta_12">0 </div>
				<h2>Últimos 12 meses</h2>
			</article>
			<article class="wbox-12 check">
				<div class="total" id="anio_curso">0 </div>
				<h2>Año en curso</h2>
			</article>
		</article>
		<article class="wbox wbox-4 ">
			<h1>Día en curso ( Noches )</h1>
			<div id="grafica_dia_curso"></div>
		</article>
		<article class="wbox wbox-4 ">
			<h1>Mes en curso vs Mes Anterior ( Noches )</h1>
			<div id="grafica_vs_meses"></div>
		</article>
	</div>
</section>

<script type="text/javascript" src="<?php echo get_recurso('js'); ?>/widgets/resumen_noches.js"></script>