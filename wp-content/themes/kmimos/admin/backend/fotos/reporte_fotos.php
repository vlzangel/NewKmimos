<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/fotos/reporte_fotos.css'>
<script src='<?php echo getTema(); ?>/admin/backend/fotos/reporte_fotos.js'></script>

<div class="botones_container">
    <input type='button' value='Moderar Fotos' onClick='moderar()' class="button button-primary button-large" />
    <input type='button' value='Excel' onClick='des_excel()' class="button button-primary button-large" />
</div>

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
                <th style="max-width: 430px;">Fotos 12 m</th>
                <th style="max-width: 430px;">Fotos 06 pm</th>
                <th>Bloqueo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<div class="botones_container">
    <input type='button' value='Moderar Fotos' onClick='moderar()' class="button button-primary button-large" />
</div>

<div class="legenda">
    <table width="100%" cellspacing="2" cellpadding="2" >
        <tr>
            <th> <span>Status</span> </th>
        </tr>
        <tr>
            <td> <div class='status-2 status-inicio'>&nbsp;</div> Por cargar fotos</td>
        </tr>
        <tr>
            <td> <div class='status-2 status-ok-medio'>&nbsp;</div> Cargo al menos un flujo</td>
        </tr>
        <tr>
            <td> <div class='status-2 status-ok'>&nbsp;</div> Cargo todas</td>
        </tr>
        <tr>
            <td> <div class='status-2 status-mal'>&nbsp;</div> No cargo fotos</td>
        </tr>
        <tr>
            <td> <div class='status-2 status-futuro'>&nbsp;</div> Cargas futuras</td>
        </tr>
    </table>
</div>

<img id='fondo' src='<?php echo getTema(); ?>/images/prueba_galeria/fondo.png' />
<canvas id='myCanvas' width='600' height='495' ></canvas>
<div id='base_table'>
    <table width='600' height='495'>
        <tr><td align='center' valign='middle' id='base'></td></tr>
    </table>
</div>

<?php
    include_once(dirname(dirname(__DIR__)).'/recursos/modal.php');
?>