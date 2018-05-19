
<link rel="stylesheet" type="text/css" href="<?php echo get_home_url()?>/monitor/reportes/ventas/css/ventas.css">
<script type="text/javascript" src="<?php echo get_home_url()?>/monitor/reportes/ventas/js/ventas.js"></script>

<?php include_once('ajax/ventas.php'); ?>

<div class="col-sm-12">
    
    <div class="row" style="padding:15px 0px 0px 0px;">
        <div class="col-md-6">
            <h2 style="margin-top:0px;margin-bottom:10px;">Resumen Clientes</h2>
        </div>
        <div class="col-md-6">
            <button id="btn-grafico" style="margin-left:2px;" class="btn btn-default pull-right" role="button">
                <small><i class="fa fa-eye-slash grafico-icon" ></i> Grafico</small>
            </button>
            <button id="btn-tabla" style="margin-left:2px;" class="btn btn-default pull-right" role="button">
                <small><i class="fa fa-eye-slash tabla-icon"></i> Tabla</small>
            </button>
            <div class="btn-group pull-right">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-database" aria-hidden="true"></i> Mostrar Datos: <strong id="tipo_datos" >Global</strong>  <span class="caret"></span>
              </button>
              <ul class="dropdown-menu">

                <li role="separator" class="disabled dropdown-title">
                    <small>
                        <strong>Datos Globales</strong>
                    </small></li>
                <li><a href="javascript:;" data-action="Global">Global</a></li>

                <li role="separator" class="divider"></li>
                <li role="separator" class="disabled dropdown-title">
                    <small>
                        <strong>Por producto</strong>
                    </small></li>
                <li><a href="javascript:;" data-action="Kmimos">Kmimos</a></li>
                <li><a href="javascript:;" data-action="Nutriheroes">Nutriheroes</a></li>

                <li role="separator" class="divider"></li>
                <li role="separator" class="disabled dropdown-title">
                    <small>
                        <strong>Por Sucursal</strong>
                    </small></li>
                <li><a href="javascript:;" data-action="Kmimos_mx">Kmimos MX</a></li>
                <li><a href="javascript:;" data-action="Kmimos_co">Kmimos CO</a></li>
                <li><a href="javascript:;" data-action="Kmimos_pe">Kmimos PE</a></li>
                <li><a href="javascript:;" data-action="Nutriheroes_mx">Nutriheroes MX</a></li>
              </ul>
            </div>
        </div>
    </div>
    <hr>
    <div class ="row">
        <div class="col-sm-12 col-md-6 pull-right">
            <form class="inline-form" action="#" id="frm_buscar" method="post">
                <div class="form-group col-md-11 ">
                    <div class="input-group  pull-right">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i> <small>Desde</small></div>
                        <input type="date" class="form-control" name="desde" value="<?php echo $desde; ?>">
                        <div class="input-group-addon"><small>Hasta</small></div>
                        <input type="date" class="form-control" name="hasta" value="<?php echo $hasta ?>">
                        <input type="hidden" name="sucursal" value="<?php echo $sucursal; ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div id="grafico-container" class="col-md-12">
            <div id="chartdiv"></div>
        </div>
        <div id="tabla-container" class="col-md-12">
            <table id="example" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons" cellspacing="0" width="100%">
                <thead>
                    <tr data-header="in">
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="clear"></div>    
</div>

<script>
    var table = "";
    var header = '<?php echo $tbl_header; ?>';
    var data = [
        <?php
        echo '['.$tbl_body['noches_reservadas'].'],';
        echo '['.$tbl_body['noches_promedio'].'],';
        echo '['.$tbl_body['noches_recompradas'].'],';
        echo '['.$tbl_body['total_perros_hospedados'].'],';
        echo '['.$tbl_body['eventos_de_compra'].'],';
        echo '['.$tbl_body['clientes_nuevos'].'],';
        echo '['.$tbl_body['clientes_wom'].'],';
        echo '['.$tbl_body['numero_clientes_que_recompraron'].'],';
        echo '['.$tbl_body['porcentaje_clientes_que_recompraron'].'],';
        echo '['.$tbl_body['precio_por_noche_pagada_promedio'].'],';
        echo '['.$tbl_body['clientes'].'],';
        echo '['.$tbl_body['numero_clientes_vs_mes_anterior'].'],';
        echo '['.$tbl_body['clientes_nuevos_vs_mes_anterior'].']';
        ?>
    ];
 
    // *********************************************
    // Retornar: dataProvider, valueAxes, graphs
    // *********************************************

    var graficoData =  <?php echo json_encode($graficos_data, JSON_UNESCAPED_UNICODE )?>;
   
    cargar_tabla();
    cargar_grafico();

</script>