<?php

    # Recursos
    include_once( dirname(dirname(dirname(__DIR__))).'/monitor/conf/importador.php' );
    include_once( dirname(dirname(dirname(__DIR__))).'/monitor/conf/graficos.php' );

    # Controller
    include_once('controller.php'); 
?>

<!-- Style -->
<link rel="stylesheet" type="text/css" href="<?php echo $ruta; ?>/monitor/reportes/resumen/css/resumen.css">

<!-- Vista -->
<div class="col-sm-12">
    
    <!-- Header -->
    <header class="row" style="padding:15px 0px 0px 0px;">
        <div class="col-md-6">
            <h2 style="margin-top:0px;margin-bottom:10px;">Resumen de Ventas</h2>
        </div>
        <div class="col-md-6">
            <div class="btn-group pull-right">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-database" aria-hidden="true"></i> 
                Mostrar Datos: <strong id="tipo_datos" ><?php echo ucfirst( $sucursal ); ?></strong>  
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">

                <li role="separator" class="disabled dropdown-title">
                    <small>
                        <strong>Datos Globales</strong>
                    </small></li>
                <li><a href="javascript:;" data-action="global" data-label="Global" >Global</a></li>

                <li role="separator" class="divider"></li>
                <li role="separator" class="disabled dropdown-title">
                    <small>
                        <strong>Por producto</strong>
                    </small></li>
                    <?php echo $menu['grupo']; ?>

                <li role="separator" class="divider"></li>
                <li role="separator" class="disabled dropdown-title">
                    <small>
                        <strong>Por Sucursal</strong>
                    </small></li>
                    <?php echo $menu['sucursal']; ?>
              </ul>
            </div>
        </div>
    </header>
    <hr>

    <!-- Opciones -->
    <section class ="row hidden">
        <div class="btn-group col-sm-12 col-md-6" role="group" aria-label="rango">
            <button type="button" class="btn btn-default option-select" data-value="diario">Diario</button>
            <button type="button" class="btn btn-default option-select activo" data-value="mensual">Mensual</button>
            <button type="button" class="btn btn-default option-select" data-value="anual">Anual</button>
        </div>
        <div class="col-md-6 col-sm-12 text-right" >
            <form class="form-inline" action="post" id='frm-search'>
                <div class="form-group">
                    <label><i class="fa fa-calendar"></i> Desde</label>
                    <input type="date" class="form-control" name="desde" value="<?php echo $desde ?>">
                </div>
                <div class="form-group">
                    <label><i class="fa fa-calendar"></i> Hasta</label>
                    <input type="date" class="form-control" name="hasta" value="<?php echo $hasta ?>">
                </div>
                <input type="hidden" name="sucursal" value="<?php echo $_POST['sucursal']; ?>">
                <button id="btn-cargar-datos" type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
            </form>
        </div>
    </section>

    <!-- Contenido -->
    <section class ="row">
        <?php include_once(dirname(dirname(dirname(__DIR__)))."/monitor/conf/loading_data.php"); ?>
    </section>

    <section class="container-item" >
        <article class="col-sm-4 alert-info">
            <h2>$ 0.00</h2>
            <hr>
            <p>Total de Ventas</p>
        </article>
        <article class="col-sm-4 alert-info">
            <h2>0</h2>
            <hr>
            <p>Ventas confirmadas</p>
        </article>
        <article class="col-sm-4 alert-info">
            <h2>0</h2>
            <hr>
            <p>Noches Reservadas</p>
        </article>
    </section>

    <section>
        <div id="grafico-container" class="col-md-6" style="height: 400px">
            <div id="ventas" style="height: 100%"></div>
        </div>
     
        <div id="grafico-container" class="col-md-6" style="height: 400px">
            <div id="ventas_dollar" style="height: 100%"></div>
        </div>
     
    </section>

    <div class="clear"></div>

</div>

<!-- Scripts -->
<script type="text/javascript" src="<?php echo $ruta; ?>/monitor/reportes/resumen/js/resumen.js"></script>

