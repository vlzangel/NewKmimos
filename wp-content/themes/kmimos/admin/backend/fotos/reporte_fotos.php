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
                <th>Mascotas</th>
                <th>Fotos 12 m</th>
                <th>Fotos 06 pm</th>
                <th>Bloqueo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<div class="legenda">
    <table width="100%" cellspacing="2" cellpadding="2" >
        <tr>
            <th> <span>Status previo al &uacute;ltimo env&iacute;o</span> </th>
            <th> <span>Status despu&eacute;s del &uacute;ltimo env&iacute;o</span> </th>
            <th> <span>Status futuros env&iacute;os</span> </th>
        </tr>
        <tr>
            <td> <div class='status-2 status-inicio'>&nbsp;</div> Por cargar fotos</td>
            <td> <div class='status-2 status-ok'>&nbsp;</div> Todo Bien</td>
            <td> <div class='status-2 status-futuro'>&nbsp;</div> Cargas futuras</td>
        </tr>
        <tr>
            <td> <div class='status-2 status-ok-medio'>&nbsp;</div> Cargo al menos un flujo</td>
            <td> <div class='status-2 status-medio'>&nbsp;</div> Solo carg&oacute; un flujo</td>
        </tr>
        <tr>
            <td> <div class='status-2 status-mal'>&nbsp;</div> No ha cargado a la hora</td>
            <td> <div class='status-2 status-mal'>&nbsp;</div> No ha cargado a la hora</td>
        </tr>
    </table>
</div>

<?php
    include_once(dirname(dirname(__DIR__)).'/recursos/modal.php');
?>