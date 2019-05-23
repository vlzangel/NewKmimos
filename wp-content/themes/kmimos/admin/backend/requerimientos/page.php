<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/requerimientos/css.css?v=<?= time() ?>'>
<script src='<?php echo getTema(); ?>/admin/backend/requerimientos/js.js?v=<?= time() ?>'></script>

<div class="container_listados">

    <div class='titulos' style="padding-top: 10px; margin-top: 10px; position: relative;">
        <h2>Requerimientos</h2>
        <div style="position: absolute; top: 10px; right: 15px; text-align: right;">
            <button id="nuevo" class="button button-primary button-large">Nuevo Requerimiento</button>
            <button id="actualizar_list" class="button button-primary button-large">Actualizar</button>
        </div>
        <hr style="margin: 10px 0px;">
    </div>

    <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;" >
        <thead>
            <tr>
                <th>#</th>
                <th>Medio</th>
                <th>Nombre Cliente</th>
                <th>Email Cliente</th>
                <th>Teléfono Cliente</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Total Noches</th>
                <th>Descripción</th>
                <th>Status</th>
                <th>Observaciones</th>
                <th>Último Contacto</th>
                <th>Atendido por:</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<style type="text/css">

</style>
<?php
    include_once(dirname(dirname(__DIR__)).'/recursos/modal.php');
?>