<?php 
    include_once('lib/nps.php'); 
    $encuesta = $nps->get_pregunta_byId($_GET['campana_id']);
?>

<script type="text/javascript">
    var ID = <?php echo $_GET['campana_id']; ?>
</script>

<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/nps/detalle/style.css'>
<script src='<?php echo getTema(); ?>/admin/backend/nps/detalle/script.js'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Campañas NPS: <?php echo $encuesta->titulo; ?></h2>
        <hr>
        <h2 style="font-size:19px; "><?php echo utf8_encode($encuesta->pregunta); ?></h2>
    </div>

    <div class='col-md-12 text-center'>
        <div class='col-md-12' style="background: #546777; color: #fff;">
            <div style="width: 100%;">
                <div id="nps_por_dia" style="height: 300px"></div>
            </div>
            <div style="width:80%; display:inline-block; vertical-align:top;">
                <div class="progress" id="score_nps_progress">
                    <div class="progress-bar progress-bar-success" style="width: 0%"></div>
                </div>
            </div>        
            <div style="text-align:center;width:15%;font-size:30px;display:inline-block;vertical-align:top;font-weight: bold;">
                <div id="score_nps_global">0</div>
            </div>
            <hr>
        </div>
    </div>
    <div class="clear"></div>

    <div class='col-md-12 text-center'>
        <div class="col col-box col-50">
            <div class="clearfix report-container score-analysis-container box-shadow first-row">
                <div class="col-sm-12 score-analysis">
                    <h5>Análisis De Puntuación</h5>
                    <ul class="scale">
                        <li class="score" id="grafico-score" style="margin-left:25,0%"><span>50</span></li>
                        <li class="neg-100">-100</li>
                        <li class="zero">0</li>
                        <li class="pos-100">100</li>
                    </ul>
                    <ul class="key">
                        <li class="good"><span>Bueno</span></li>
                        <li class="excellent"><span>Excelente</span></li>
                        <li class="world"><span>Genial</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col col-box col-25">
            <h5><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Puntuación media</font></font></h5>
            <div class="progress progress-bar-vertical mean-score-box">
                <div id="progress-media" class="progress-bar progress-bar-warning" style="height: 0%; position: absolute;"></div>
                <div class="mean-score">
                    <p id="mean-score">
                        <font style="vertical-align: inherit;">
                            <font id="text-media" style="vertical-align: inherit;">0</font>
                        </font>
                    </p>
                </div>
            </div>
        </div>

        <div class="col col-box col-25">
            <canvas id="feedback_recibidos" width="100%" height="100%"></canvas>
        </div>
    </div>
    <div class="clear"></div>

    <div class='col-md-12 text-left'>
        <div class="col col-box col-50" style="border: 1px solid #ccc;">
            <h5 class="text-center">Desglose De Categoría</h5>
            <div class="col-row" style="margin:30px 0px;">
                <div class="col col-50">Promoters</div>
                <div class="col col-50 text-right" id="desglose-promoters-total">0%</div>
                <div class="col col-100">                
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="desglose-promoters" style="width: 0%;">
                            <span class="sr-only">0% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-row" style="margin:30px 0px;">
                <div class="col col-50">Pasivos</div>
                <div class="col col-50 text-right" id="desglose-pasivos-total">0%</div>
                <div class="col col-100">                
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="desglose-pasivos" style="width: 0%;">
                            <span class="sr-only">0% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-row" style="margin:30px 0px;">
                <div class="col col-50">Detractores</div>
                <div class="col col-50 text-right" id="desglose-detractores-total">0%</div>
                <div class="col col-100">                
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="desglose-detractores" style="width: 0%;">
                            <span class="sr-only">0% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-box col-50" style="border: 1px solid #ccc;">
            <h5 class="text-center">Desglose De Puntuación</h5>
            <div class="col col-100">
                <div style="width: 100%;">
                    <div id="grafico_detalle" style="height: 250px"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    
    <div class='col-md-12'>
        <hr>
        <h5>Puntajes Recientes <a href="<?php echo get_home_url(); ?>/wp-admin/admin.php?page=nps_feedback&campana_id=<?php echo $_GET['campana_id']; ?>" class="pull-right btn btn-especial btn-primary" style="margin-right: 15px;">Ver mas</a></h5>
        <br>
        <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th width="10%">Fecha</th>
                    <th width="10%">Puntos</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>

</div>

 <?php
    include_once(dirname(dirname(dirname(__DIR__))).'/recursos/modal.php');
?>
 