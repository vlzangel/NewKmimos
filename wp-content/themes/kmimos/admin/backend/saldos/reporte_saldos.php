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
            <input type='button' id='consultar' value='Consultar' onClick='getSaldo()' class="button button-primary button-large" />
        </div>

        <div id="info_user">
            <div><label class="info_label">Email: </label> <span>vlzangel91@gmail.com</span></div>
            <div><label class="info_label">Saldo: </label> <span>$150,00 MXN</span></div>
        </div>

        <div class='botones_container confirmaciones'>
            <input type='button' id='cancelar' value='Cerrar' onClick='cerrarInfo()' class="button button-primary button-large" style="float: left;" />
            <input type='button' id='confirmar' value='Confirmar' onClick='updateSaldo()' class="button button-primary button-large" />
        </div>
    </form>
</div>
