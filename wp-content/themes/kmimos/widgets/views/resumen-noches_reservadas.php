<section class="container ">
	<aside>
		<button type="button" data-target="noches_reservadas-change" data-id="byDay" class="btn ">Diario</button>
		<button type="button" data-target="noches_reservadas-change" data-id="byMonth" class="btn">Mensual</button>
		<button type="button" data-target="noches_reservadas-change" data-id="total" class="btn active">Acumulado</button>
	</aside>
	<article class="grafico-container">
		<div id="grafico_noches_reservadas"></div>
	</article>
</section>

<script type="text/javascript" src="<?php echo get_recurso('js'); ?>/widgets/resumen_noches_reservadas.js"></script>