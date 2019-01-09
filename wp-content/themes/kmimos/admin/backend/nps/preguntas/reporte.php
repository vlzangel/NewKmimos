<?php
    $user = wp_get_current_user();
    $user_email = $user->user_email;

?>
<script type="text/javascript">
    var email_user = '<?php echo $user_email; ?>';    
</script>

<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/nps/preguntas/style.css'>
<script src='<?php echo getTema(); ?>/admin/backend/nps/preguntas/script.js'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Campañas NPS</h2>
        <hr>
    </div>

    <div class='col-md-12'>
        <div class='row'>
            <div class="col-sm-12 col-md-5">
                <button class="btn btn-success" data-titulo='Crear campaña' data-modal='crear'
                ><i class="fa fa-rocket"></i> Nuevo</button>
            </div>
            <div class="col-sm-12 col-md-7 container-search text-right">
                <form id="form-search" name="search">
                    <span><label class="fecha text-left">Desde: </label><input type="date" name="ini" value="<?php echo $fecha['ini']; ?>"></span>
                    <span><label class="fecha text-left">Hasta: <input type="date" name="fin" 
                        min="<?php echo $fecha['min']; ?>" 
                        max="<?php echo $fecha['max']; ?>" 
                        value="<?php echo $fecha['fin']; ?>"></label></span> 
                    <button class="btn btn-defaut" id="btn-search"><i class="fa fa-search"></i> Buscar</button>
                </form>
            </div>
        </div>
        <hr>
    </div>
    <div class="clear"></div>

    <div class='col-md-12 text-center'>
        <small>Organizaci&oacute;n NPS</small>
        <h1><span id="score_nps_global">0</span></h1>
        <div class="progress" id="score_nps_progress">
            <div class="progress-bar progress-bar-success" style="width: 0%"></div>
        </div>
        <aside>
            <article class="leyenda-item"> <span class="success"></span> Promoters </article>
            <article class="leyenda-item"> <span class="warning"></span> Pasivos </article>
            <article class="leyenda-item"> <span class="danger"></span> Detractores </article>
        </aside>
        <hr>
    </div>
    <div class="clear"></div>

    <div class='col-md-12'>
 
        <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Titulo</th>
                    <th width="10%">Estatus</th>
                    <th width="10%">Fecha</th>
                    <th width="5%">Feedback</th>
                    <th width="20%">Ptos. NPS</th>
                    <th width="15%">Opciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>

</div>

 <?php
    include_once(dirname(dirname(dirname(__DIR__))).'/recursos/modal.php');
?>
 