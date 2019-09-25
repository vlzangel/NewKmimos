<?php 
    include_once('lib/nps.php'); 
    $encuesta = $nps->get_pregunta_byId($_GET['campana_id']);
    $preguntas = $nps->get_preguntas('','');
?>
<script type="text/javascript">
    var ID = <?php echo (isset($_GET['campana_id']))? $_GET['campana_id'] : 0 ; ?>
</script>

<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/nps/feedback/style.css?v=<?= time() ?>'>
<script src='<?php echo getTema(); ?>/admin/backend/nps/feedback/script.js'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Feedback</h2>
        <hr>
        
    </div>
    <div class="col-container" style="display:none;">
        <div class="col col-row">
            <label>Campaña NPS: </label>
            <select name="redirect-pregunta" class="form-control">
                <option value="0">Selecciona una campaña NPS</option>
                <?php foreach ($preguntas as $key => $value) { $select = ($encuesta->id == $value->id)? 'selected':''; ?>
                    <option <?php echo $select; ?> data-pregunta="<?php echo $value->pregunta; ?>" value="<?php echo $value->id; ?>"><?php echo $value->titulo; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col col-row" style="margin: 0px!important">
        <h3 id="pregunta-title" style="font-size:19px; "><?php echo $encuesta->pregunta; ?></h3>
    </div>

    <div class="col-container">
        <div class="col col-100 items-list">
            <ul class="list-unstyled list-inline">
                <li data-id="usuario" class="active">Usuarios</li>
                <li data-id="comentario">Comentarios</li>
            </ul>
        </div>
        <div class="col col-30" data-objetivo="list-usuario">
            <div class="col-box col-md-12">
                <h5>Usuarios</h5>
                <div>
                    <hr>
                    <table id="example" class="table table-striped table-bordereds nowrap" cellspacing="0" style="width: 100%;">
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
        </div>
        <div class="col col-70" data-objetivo="list-comentario">
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
                    <p>Hemos recibido sus comentarios, su opinión es muy importante para nosotros. ¡Gracias por compartirla!</p>
                    <p style="
                        margin: 18px 0px; 
                        color: #000; 
                        font-weight: bold;
                        font-size: 16px;
                    ">Kmimos te respondió:</p>
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