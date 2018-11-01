<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/status_solicitudes/css.css'>
<script src='<?php echo getTema(); ?>/admin/backend/status_solicitudes/js.js'></script>

<div class="container_listados">

    <div class='titulos' style="padding: 20px 0px 0px;">
        <h2>Ajuste  de Estatus de Solicitud</h2>
        <hr>
    </div>

    <form id="form" action="" method="POST">

        <label>Id Solicitud</label>
            <input type="number" id="id" name="id" class="input" />

        <div class='botones_container'>
            <input type='button' id='consultar' value='Consultar' onClick='getStatus()' class="button button-primary button-large" />
        </div>

        <div id="info_user"></div>

        <div class='botones_container confirmaciones'>
            <input type='button' id='cancelar' value='Cerrar' onClick='cerrarInfo()' class="button button-primary button-large" style="float: left;" />
            <input type='button' id='confirmar' value='Confirmar' onClick='updateStatus()' class="button button-primary button-large" />
        </div>
    </form>

</div>

