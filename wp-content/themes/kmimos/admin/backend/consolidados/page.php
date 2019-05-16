<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/consolidados/css.css'>
<script src='<?php echo getTema(); ?>/admin/backend/consolidados/js.js'></script>

<div class="container_listados">

    <div class='titulos' style="padding-top: 30px;">
        <h2>Consolidados</h2>
        <hr>
    </div>

    <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;" >
        <thead>
            <tr>
                <th>ID</th>
                <th>Comentarios</th>
                <th>Última fecha de contacto</th>
                <th>Atendida por:</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<?php
    include_once(dirname(dirname(__DIR__)).'/recursos/modal.php');
?>