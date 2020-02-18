<?php 
    if ( ! defined( 'ABSPATH' ) ) { exit; } 
    extract($_GET);
    session_start();
    $ini = ( isset($ini) && strlen($ini) > 0 ) ? $ini : date("Y-m")."-01";
    $fin = ( isset($fin) && strlen($fin) > 0 ) ? $fin : date("Y-m-d");
?>
<script type="text/javascript">
    var INI = "<?= $ini ?>";
    var FIN = "<?= $fin ?>";
</script>
<script type="text/javascript"> var ADMIN_AJAX = "<?= plugin_dir_url(__FILE__).'/core/NEW/reservas/php.php' ?>"; </script>
<link rel='stylesheet' type='text/css' href='<?php echo plugin_dir_url(__FILE__) ?>/core/NEW/reservas/css.css?v=<?= time() ?>'>
<script src='<?php echo plugin_dir_url(__FILE__) ?>/core/NEW/reservas/js.js?v=<?= time() ?>'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Control de Reservas</h2>
        <hr>
    </div>

    <div class='col-md-12'>
        <div class='row'>
            <div class="col-sm-12 col-md-12 container-search text-right">
                <form id="form-search" name="search" action="<?= get_home_url(); ?>/wp-admin/admin.php">
                    <input type="hidden" name="page" value="bp_reservas">
                    <span>
                        <label class="fecha">Desde: </label><input type="date" name="ini" value="<?php echo $ini; ?>">
                    </span>
                    <span>
                        <label class="fecha">Hasta: <input type="date" name="fin" value="<?php echo $fin; ?>"></label>
                    </span> 
                    <button class="btn btn-defaut" id="btn-search"><i class="fa fa-search"></i> Buscar</button>
                </form>
            </div>
        </div>
        <hr>
    </div>
    <div class="clear"></div>

    <div class='col-md-12'>
 
        <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Origen</th>
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
                    <th>Servicios Especiales</th>
                    <th>Estado</th>
                    <th>Municipio</th>

                    <th>Fecha Creación</th>
                    <th>Fecha Pago</th>
                    
                    <th>Forma de Pago</th>
                    <th>Tipo de Pago</th>
                    <th>Total a pagar ($)</th>
                    <th>Monto Pagado ($)</th>
                    <th>Monto Remanente ($)</th>

                    <th>Saldo a favor</th>
                    <th>Promoción</th>
                    <th>Monto promoción</th>
                    <th>Promoción cuidador</th>
                    <th>Monto promo cuidador</th>

                    <th># Pedido</th>
                    <th>Observaci&oacute;n</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>


</div>
 