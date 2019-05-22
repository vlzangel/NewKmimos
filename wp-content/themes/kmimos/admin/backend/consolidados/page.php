<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/consolidados/css.css?v=<?= time() ?>'>
<script src='<?php echo getTema(); ?>/admin/backend/consolidados/js.js?v=<?= time() ?>'></script>

<div class="container_listados">

    <div class='titulos' style="padding-top: 30px;">
        <h2>Consolidados</h2>
        <hr>
    </div>

    <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;" >
        <thead>
            <tr>
                <th>#</th>
                <th># Reserva</th>
                <th>Flash</th>
                <th>Estatus</th>
                <th>Fecha Reservacion</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Noches</th>
                <th># Mascotas</th>
                <th># Noches Totales</th>
                <th>Nombre Cliente</th>
                <th>Correo Cliente</th>
                <th>Tel&eacute;fono Cliente</th>
                <th>Recompra (1Mes)</th>
                <th>Recompra (3Meses)</th>
                <th>Recompra (6Meses)</th>
                <th>Recompra (12Meses)</th>
                <th>Donde nos conocio?</th>
                <th>Mascotas</th>
                <th>Razas</th>
                <th>Edad</th>
                <th>Nombre Cuidador</th>
                <th>Correo Cuidador</th>
                <th>Tel&eacute;fono Cuidador</th>
                <th>Servicio Principal</th> 
                <th>Servicios Especiales</th> <!-- Servicios adicionales -->
                <th>Estado</th>
                <th>Municipio</th>
                <th>Forma de Pago</th>
                <th>Tipo de Pago</th>
                <th>Total a pagar ($)</th>
                <th>Monto Pagado ($)</th>
                <th>Monto Remanente ($)</th>
                <th>Cupones kmimos</th>
                <th>Cupones Cuidador</th>
                <th>Total cupones</th>
                <th># Pedido</th>
                <th>Observaci&oacute;n</th>
                <th>Comentarios</th>
                <th>Última fecha de contacto</th>
                <th>Atendida por:</th>
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