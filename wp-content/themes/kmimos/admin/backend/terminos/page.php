<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/terminos/css.css'>
<script src='<?php echo getTema(); ?>/admin/backend/terminos/js.js'></script>

<div class="container_listados">

    <div class='titulos' style="padding-top: 30px;">
        <h2>T&eacute;rminos Aceptados</h2>
        <hr>
    </div>

    <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;" >
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Contactos</th>
                <th>Tipo</th>
                <th>IP</th>
                <th>Fecha</th>
                <!-- <th>Acciones</th> -->
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<?php
    include_once(dirname(dirname(__DIR__)).'/recursos/modal.php');
?>