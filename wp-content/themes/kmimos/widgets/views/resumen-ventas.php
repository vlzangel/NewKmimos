<?php 
	$theme = 'light';
	//$theme = 'dark'; 
?>

<link rel="stylesheet" type="text/css" href="<?php echo get_recurso('css'); ?>/widgets/panel.css">
<link rel="stylesheet" type="text/css" href="<?php echo get_recurso('css'); ?>/widgets/<?php echo $theme; ?>.css">

<section class="container titulo">
	<div class="contenedor_widget">
		<article class="wbox wbox-4">
			<div class="total" id="curso_dia">0</div>
			<h2>Día en curso </h2>
		</article>
		<article class="wbox wbox-4">
			<div class="total" id="curso_mes">0</div>
			<h2>Mes en curso</h2>
		</article>
		<article class="wbox wbox-4">
			<div class="total" id="curso_anterior">0</div>
			<h2>Mes Anterior</h2>
		</article>
	</div>
</section>
<section class="container titulo">
	<div class="contenedor_widget">
		<article class="wbox wbox-4">
			<div class="total" id="ventas_90">0</div>
			<h2>Últimos 90 días</h2>
		</article>
		<article class="wbox wbox-4">
			<div class="total" id="ventas_12">0</div>
			<h2>Últimos 12 meses</h2>
		</article>
		<article class="wbox wbox-4">
			<div class="total" id="ventas_curso">0</div>
			<h2>Año en curso</h2>
		</article>
	</div>
</section>

<section>
	<div class="contenedor_widget">
		<div class="wbox wbox-12"> 
			<h1>Ventas</h1>
			<div id="grafico_resumen_ventas"></div>
		</div>
	</div>
</section>

<script type="text/javascript" src="<?php echo get_recurso('js'); ?>/widgets/resumen_ventas.js"></script>
