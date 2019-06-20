<?php $MODULO = 'correo_generico'; ?>
<script type="text/javascript"> <?= "var MODULO = '{$MODULO}'; </script>" ?> </script>
<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/<?= $MODULO ?>/css.css?v=<?= time() ?>'>
<script src='<?php echo getTema(); ?>/admin/backend/<?= $MODULO ?>/js.js?v=<?= time() ?>'></script>

<script src='<?php echo getTema(); ?>/js/select_localidad.js?v=<?= time() ?>'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Correo de confirmación</h2>
        <hr>
    </div>
    <div class="clear"></div>


    <form id="form" method="POST">

        <div class="container">
            <div class="row">
                <div class='col-md-6'>
                    <label>Titulo Correo</label>
                    <input type="text" id="titulo" name="titulo" class="input" required />

                    <label>Nombre Cliente</label>
                    <input type="text" id="cliente" name="cliente" class="input" value="" required />

                    <label>Parrafo 1</label>
                    <textarea id="parrafos[]" name="parrafos[]" class="input" required></textarea>

                    <label>Parrafo 2 (Opcional)</label>
                    <textarea id="parrafos[]" name="parrafos[]" class="input"></textarea>

                    <label>Parrafo 3 (Opcional)</label>
                    <textarea id="parrafos[]" name="parrafos[]" class="input"></textarea>

                    <label>Sugerencias de Cuidador</label>
                    <div id="sugerencias">
                        <input type="text" id="sugerencias_ids" name="sugerencias" />
                        <input type="text" id="sugerencias_txt" class="input" value="" readonly />
                    </div>
                </div>

                <div class='col-md-6'>
                    <label  id="lab_sug" for="ubicacion">Buscar sugerencias</label>
                    <div id="camp_sug"  style="position: relative;">
                        <input type="text" class="ubicacion_txt input" name="ubicacion_txt" placeholder="Ubicación estado municipio" autocomplete="off" />
                        <input type="hidden" class="ubicacion" name="ubicacion" />  
                        <div class="cerrar_list_box">
                            <div class="cerrar_list">X</div>
                            <ul class="ubicacion_list"></ul>
                        </div>
                        <div class="barra_ubicacion"></div>
                    </div>

                    <div class="cuidadores_list">
                        <div></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class='col-md-12'>
                    <div class='botones_container confirmaciones'>
                        <input type='submit' id='submit' value='Enviar solicitud' class="button button-primary button-large" />
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

<?php
    include_once(dirname(dirname(__DIR__)).'/recursos/modal.php');
?>