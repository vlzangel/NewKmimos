<!-- . Rob quiere que se tengan dos botones, uno rojo para quitar el saldo (dejarlo en 0) y uno verde con su campo de texto para modificarlo. Quiere una confirmación donde aparezca en grande el valor modificado, por si alguien se equivoca.
 -->
<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/saldos/reporte_saldos.css'>
<script src='<?php echo getTema(); ?>/admin/backend/saldos/reporte_saldos.js'></script>

<div class="container_listados">

    <div class='titulos' style="padding: 20px 0px 0px;">
        <h2>Ajuste de Saldos</h2>
        <hr>
    </div>

    <form id="form" action="" method="POST">

        <label>Correo</label>
            <input type="email" id="email" name="email" class="input" />
        <label>Saldo</label>
            <input type="number" id="saldo" name="saldo" class="input" />

        <div class='botones_container'>
            <input type='button' id='quitar' value='Quitar Saldo' onClick='quitarSaldo()' class="btn btn-danger" />
            <input type='button' id='consultar' value='Actualizar' onClick='getSaldo()' class="btn btn-success" />
        </div>

        <div id="info_user">
            <div><label class="info_label">Email: </label> <span></span></div>
            <div><label class="info_label">Saldo: </label> <span></span></div>
        </div>

        <div class='botones_container confirmaciones'>
            <input type='button' id='cancelar' value='Cerrar' onClick='cerrarInfo()' class="btn btn-outline-secondary" style="float: left;" />
            <input type='button' id='confirmar' value='Confirmar' onClick='updateSaldo()' class="btn btn-success" />
        </div>
    </form>
</div>
