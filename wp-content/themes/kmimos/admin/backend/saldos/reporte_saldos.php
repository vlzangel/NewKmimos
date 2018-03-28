<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/saldos/reporte_saldos.css'>
<script src='<?php echo getTema(); ?>/admin/backend/saldos/reporte_saldos.js'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Control de Saldos</h2>
        <hr>
    </div>

    <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;" >
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<?php
    include_once(dirname(dirname(__DIR__)).'/recursos/modal.php');
?>