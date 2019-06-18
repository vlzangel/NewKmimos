<?php $MODULO = 'correo_generico'; ?>
<script type="text/javascript"> <?= "var MODULO = '{$MODULO}'; </script>" ?> </script>
<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/<?= $MODULO ?>/css.css'>
<script src='<?php echo getTema(); ?>/admin/backend/<?= $MODULO ?>/js.js'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Correo de confirmación</h2>
        <hr>
    </div>
    <div class="clear"></div>

    <div class='col-md-12 contenedor'>

        <form id="form" method="POST">

            <label>Titulo Correo</label>
            <input type="text" id="titulo" name="titulo" class="input" required />

            <label>Cliente (Correo)</label>
            <input type="email" id="correo" name="correo" class="input" value="" required />

            <label>Parrafo 1</label>
            <textarea id="parrafos[]" name="parrafos[]" class="input" required></textarea>

            <label>Parrafo 2 (Opcional)</label>
            <textarea id="parrafos[]" name="parrafos[]" class="input"></textarea>

            <label>Parrafo 3 (Opcional)</label>
            <textarea id="parrafos[]" name="parrafos[]" class="input"></textarea>

            <label>Sugerencias de Cuidador</label>
            <select id="sugerencias" name="sugerencias" class="input">
                <option value="0">Sin sugerencias</option>
                <option value="2">2 sugerencias</option>
                <option value="4">4 sugerencias</option>
            </select>

            <div class='botones_container confirmaciones'>
                <input type='submit' id='submit' value='Enviar solicitud' class="button button-primary button-large" />
            </div>
        </form>
        
    </div>

</div>

<style type="text/css">

    form#form {
        width: 600px;
        display: block;
        margin: 0px auto;
    }

    #form label {
        display: inline-block !important;
        font-weight: 600;
        margin: 10px 0px 3px !important;
    }

    #form .input {
        display: block !important;
        width: 100%;
        box-sizing: border-box;
        margin: 0px;
    }

    #form textarea.input {
        resize: none;
        height: 150px;
    }

    .botones_container {    
        margin: 20px 0px 0px;
        padding: 20px 0px 0px;
        text-align: right;
        border-top: solid 1px #CCC;
        background: #FFF;
        border-radius: 0px;
    }

    .modal > div {
        max-width: 800px;
    }

</style>

<?php
    include_once(dirname(dirname(__DIR__)).'/recursos/modal.php');
?>