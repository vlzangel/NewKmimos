<pre>
<?php 
    include_once('lib/pagos.php'); 
    $fecha = $pagos->getRangoFechas();
    $user = wp_get_current_user();
?>
</pre>
<script>
    var ID = <?php echo $user->ID; ?>;
    var fecha = { 
        ini:'<?php echo $fecha['ini']; ?>', 
        fin:'<?php echo $fecha['fin']; ?>'
    };    
</script>
<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/pagos_autorizacion/reporte_pagos_autorizacion.css'>
<script src='<?php echo getTema(); ?>/admin/backend/pagos_autorizacion/reporte_pagos_autorizacion.js'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Control de Autorizaciones de Pagos a cuidadores</h2>
        <hr>
    </div>

    <div class='col-md-12'>
        <div class='row'>
            <div class="col-sm-12 col-md-5">
                <button class="btn btn-defaut" id="select-all"><i class="fa fa-list"></i> Marcar/Desmarcar Todos</button>
                <button class="btn btn-defaut" id="quitar-filtro"><i class="fa fa-filter"></i> Mostrar todos</button>
            </div>
            <div class="col-sm-12 col-md-7 container-search text-right">
                <form id="form-search" name="search">
                    <span><label class="fecha">Desde: </label><input type="date" name="ini" value="<?php echo $fecha['ini']; ?>"></span>
                    <span><label class="fecha">Hasta: <input type="date" name="fin" value="<?php echo $fecha['fin']; ?>"></label></span> 
                    <button class="btn btn-defaut" id="btn-search"><i class="fa fa-search"></i> Buscar</button>
                </form>
            </div>
        </div>
        <hr>
    </div>
    <div class="clear"></div>

    <div class='col-md-12'>

        <dir class="leyenda text-right">
            <ul class="list-inline">
                <li><strong>LEYENDA DE ESTATUS: </strong></li>
                <li><div></div> Por autorizar</li>
                <li><div></div> Autorizado</li>
                <li><div></div> Negado</li>
                <li><div></div> En progreso</li>
                <li><div></div> Cancelado</li>
                <li><div></div> Completado</li>
                <li><div></div> Error</li>
            </ul>
        </dir>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item hidden">
            <a class="nav-link " id="pagosNuevos-tab" data-toggle="tab" href="nuevo" role="tab" aria-controls="pagosNuevos" aria-selected="true">Nuevos Pagos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" id="pagosGenerados-tab" data-toggle="tab" href="generados" role="tab" aria-controls="pagosGenerados" aria-selected="false">Pendientes por Autorizar</a>
          </li>
        </ul>

        <div class="botones_container" id="opciones-nuevo">
            <button type="button" class="btn btn-default" data-titulo='Procesar solicitudes de pago' data-modal='autorizar' data-id="0" >
              Procesar
            </button>
        </div>

        <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;">
            <thead>
                <tr>
                    <th></th>
                    <th>Fecha</th>
                    <th>Estatus</th>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Total a pagar</th>
                    <th>Cant. Reservas</th>
                    <th>Det. Reservas</th>
                    <th>Supervisado por.</th>
                    <th>Opciones</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

</div>

 <?php
    include_once(dirname(dirname(__DIR__)).'/recursos/modal.php');
?>