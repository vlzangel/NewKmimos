<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/fotos/reporte_fotos.css'>
<script src='<?php echo getTema(); ?>/admin/backend/fotos/reporte_fotos.js'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Control de Fotos</h2>
        <hr>
    </div>

    <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;" >
        <thead>
            <tr>
                <th>Reserva</th>
                <th>Cuidador</th>
                <th>Cliente</th>
                <th>E-mail</th>
                <th>Tel&eacute;fono</th>
                <th>Mascotas</th>
                <th>Fotos 12 m</th>
                <th>Fotos 06 pm</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<?php
    include_once(dirname(dirname(__DIR__)).'/recursos/modal.php');
?>