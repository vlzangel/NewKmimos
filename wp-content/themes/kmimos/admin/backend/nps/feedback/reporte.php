<?php 
    include_once('lib/nps.php'); 
    $encuesta = $nps->get_pregunta_byId($_GET['campana_id']);
?>
<script type="text/javascript">
    var ID = <?php echo $_GET['campana_id']; ?>
</script>

<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/nps/feedback/style.css'>
<script src='<?php echo getTema(); ?>/admin/backend/nps/feedback/script.js'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Feedback</h2>
        <hr>
        <h5><?php echo utf8_encode($encuesta->pregunta); ?></h5>
    </div>

    <div style="display: flex;">
        <div class="col col-30">
            <div class="col-box col-md-12">
                <h5>Usuarios</h5>
                <hr>
                <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;">
                    <thead>
                        <tr>
                            <th>Descripci&oacute;n</th>
                            <th width="10%">Puntos</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="col col-70">
            <div class="col-box col-md-12">
                <h5>Comentarios</h5>
                <hr>
                <div id="comentarios">
                    <div class="media alert alert-warning">
                      <div class="media-body">
                        Selecciona un usuario para cargar los comentarios
                      </div>
                    </div>
                </div>
                <br>
                <br>
                <div class="clear"></div>
                <div class="col-md-12">
                    <h5>Enviar feedback</h5>
                    <hr>
                    <form id="email-feedback">
                        <input type="hidden" name="email">
                        <input type="hidden" name="code">
                        <input type="hidden" name="respuesta_id">
                        <textarea readonly name="comentario" class="col col-md-12" style="height: 100px"></textarea>
                        <dir class="text-right">
                            <button id="enviar_email" type="submit" class="disabled btn btn-success"><i class="fa fa-envelope-o" aria-hidden="true"></i> Enviar comentario</button>
                        </dir>
                    </form>
                </div>
            </div>

        </div>
    </div>

</div>