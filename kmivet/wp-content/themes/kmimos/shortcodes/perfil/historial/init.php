<?php
	wp_enqueue_style( 'bootstrap4_css', "https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css", array(), "1.0.0" );
	wp_enqueue_style( 'responsive_bootstrap4_css', "https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css", array(), "1.0.0" );

	$current_user = wp_get_current_user();
?>

<h1 class="titulo_perfil">Mis Citas</h1>

<i id="sync" class="fas fa-sync-alt"></i>

<table id="historial" class="table table-striped table-bordered nowrap dataTable no-footer">
	<thead>
		<th>ID</th>
		<th>Veterinario</th>
		<th>Fecha</th>
		<th>Estatus</th>
		<th>Acciones</th>
	</thead>
	<tbody></tbody>
</table>

<?php
    wp_enqueue_script('popper.js', "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js", array("jquery"), '1.0.0');
    wp_enqueue_script('jquery.dataTables.js', "https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js", array("jquery"), '1.0.0');
    wp_enqueue_script('dataTables.bootstrap4.js', "https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js", array("jquery"), '1.0.0');
    wp_enqueue_script('dataTables.responsive.js', "https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js", array("jquery"), '1.0.0');
    wp_enqueue_script('responsive.bootstrap4.js', "https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap4.min.js", array("jquery"), '1.0.0');
?>