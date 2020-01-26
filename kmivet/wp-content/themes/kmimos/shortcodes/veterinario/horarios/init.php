<?php
	wp_enqueue_style( 'bootstrap4_css', "https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'responsive_bootstrap4_css', "https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css", array(), "1.0.0" );
	$veterinario = $wpdb->get_row("SELECT * FROM {$pf}veterinarios WHERE user_id = '{$user_id}' ");
?>
<h1 class="titulo_perfil">Mi Horario</h1>

<form id="kv_form" autocomplete="off" enctype="multipart/form-data">

	<div class="inputs_containers">

	    <div>
	        <table id="horario" class="table table-striped table-bordered nowrap dataTable no-footer">
				<thead>
					<th>Día</th>
					<th>Horarios</th>
				</thead>
				<tbody></tbody>
			</table>

	    </div>

	</div>

	<div class="container_btn">
	    <input type="button" onclick="_edit(jQuery(this))" data-id="<?= $user_id ?>" class="km-btn-primary" value="Nuevo Horario">
	    <div class="perfil_cargando" style="background-image: url('.getTema().'/images/cargando.gif);" ></div>
	</div>
</form>

<?= get_modal('horario_modal') ?>

<?php
    wp_enqueue_script('popper.js', "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.dataTables.js', "https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js", array("jquery"), '1.0.0');
    wp_enqueue_script('dataTables.bootstrap4.js', "https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js", array("jquery"), '1.0.0');
    wp_enqueue_script('dataTables.responsive.js', "https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js", array("jquery"), '1.0.0');
    wp_enqueue_script('responsive.bootstrap4.js', "https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js", array("jquery"), '1.0.0');
?>