 
<section class="container titulo">
	<div class="contenedor_widget">
		<article class="wbox wbox-4 check">
			<div class="total" id="resumen_hoy">0</div>
			<h2>Confirmadas <small>(Hoy)</small></h2>
		</article>
		<article class="wbox wbox-4 check">
			<div class="total" id="resumen_confirmadas">0</div>
			<h2>Confirmadas + Pendientes <small>(Este Mes)</small></h2>
		</article>
		<article class="wbox wbox-4 check">
			<div class="total" id="resumen_mes">0</div>
			<h2>Confirmadas <small>(Mes pasado)</small></h2>
		</article>
	</div>
</section>

<section class="">
	<div class="contenedor_widget">
		<div class="wbox wbox-8"> 
			<h1>Reservas por d&iacute;a ( Este mes )</h1>
			<div id="grafico_resumen_por_dia"></div>
		</div>
		<div class="wbox wbox-4"> 
			<h1>Reservas ( Este Mes )</h1>
			<div id="dona_resumen_este_mes"></div>
		</div>
	</div>
</section>

<script type="text/javascript" src="<?php echo get_recurso('js'); ?>/widgets/resumen_reserva.js"></script>
