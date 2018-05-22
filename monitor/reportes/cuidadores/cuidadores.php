
<link rel="stylesheet" type="text/css" href="<?php echo get_home_url()?>/monitor/reportes/cuidadores/css/cuidadores.css">
<script type="text/javascript" src="<?php echo get_home_url()?>/monitor/reportes/cuidadores/js/cuidadores.js"></script>

<?php include_once('ajax/cuidadores.php'); ?>

<?php
    $grupo = [];
    $por_sucursal = '';
    $por_grupo = '';
    foreach ($plataformas as $plataforma) {
                        
        $por_sucursal .= '<li><a href="javascript:;" data-label="'.$plataforma['descripcion'].'" data-action="byname.'.$plataforma['name'].'">'.$plataforma['descripcion'].'</a></li>';

        if( !in_array($plataforma['grupo'] , $grupo) ) {
            $por_grupo .= '<li><a href="javascript:;" data-label="'.$plataforma['grupo'].'" data-action="bygroup.'.$plataforma['grupo'].'">'.ucfirst($plataforma['grupo']).'</a></li>';
            $grupo[] = $plataforma['grupo'];
        }

    }
?>

<div class="col-sm-12">
    
    <div class="row" style="padding:15px 0px 0px 0px;">
        <div class="col-md-6">
            <h2 style="margin-top:0px;margin-bottom:10px;">Resumen Cuidadores</h2>
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
                    <?php echo $por_grupo; ?>

                <li role="separator" class="divider"></li>
                <li role="separator" class="disabled dropdown-title">
                    <small>
                        <strong>Por Sucursal</strong>
                    </small></li>
                    <?php echo $por_sucursal; ?>
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

        <?php if( $error == 0 ) { ?>
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
        <?php }else{ ?>
            <div class="col-sm-12">
                <div class="alert alert-info text-center">No existen datos para mostrar</div>
            </div>
        <?php } ?>

    </div>

    <div class="clear"></div>    
</div>

<script>
    var table = "";
    var header = '<?php echo $tbl_header; ?>';
    var data = [
        <?php
        echo '['.$tbl_body['total'].'],';
        echo '['.$tbl_body['nuevos'].'],';
        echo '['.$tbl_body['costos_por_campana'].'],';
        echo '['.$tbl_body['costo'].'],';
        ?>
    ];
 
    // *********************************************
    // Retornar: dataProvider, valueAxes, graphs
    // *********************************************

    var graficoData =  <?php echo json_encode($graficos_data, JSON_UNESCAPED_UNICODE )?>;
   
    cargar_tabla();
    cargar_grafico();

</script>