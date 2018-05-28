<?php

    # Recursos
    include_once( dirname(dirname(dirname(__DIR__))).'/monitor/conf/importador.php' );
    include_once( dirname(dirname(dirname(__DIR__))).'/monitor/conf/graficos.php' );
    
    # Controller    
    include_once( 'ajax/general.php' ); 
?>

<link rel="stylesheet" type="text/css" href="<?php echo $ruta; ?>/monitor/reportes/marketing/css/marketing.css">
<script type="text/javascript" src="<?php echo $ruta; ?>/monitor/reportes/marketing/js/marketing.js"></script>


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
            <h2 style="margin-top:0px;margin-bottom:10px;">Control de Campañas</h2>
        </div>
        <div class="col-md-6 hidden">
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
        <div class="col-sm-12 col-md-12">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#nuevo"><i class="fa fa-plus"></i> Nuevo</button>
        </div>
    </div>
    <br>
    <div class ="row">
        <div id="tabla-container" class="col-md-12">
            <table id="example" class="table table-striped table-bordered dt-responsive table-hover table-responsive nowrap datatable-buttons" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <td width="10%">Fecha</td>
                        <td>Nombre</td>
                        <td>Costo</td>
                        <td>Plataforma</td>
                        <td>Tipo</td>
                        <td>Canal</td>
                        <td width="5%">Opciones</td>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div class="clear"></div>    
</div>

<?php include_once( 'modal/nuevo.php' ); ?>