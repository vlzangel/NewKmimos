<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/openpay/css.css'>
<script src='<?php echo getTema(); ?>/admin/backend/openpay/js.js'></script>

<div class="container_listados">

    <div class='titulos' style="padding: 20px 0px 0px;">
        <h2>Bloqueo Tarjeta</h2>
        <hr>
    </div>

    <form id="form" action="" method="POST">

        <label>Id Reserva</label>
            <input type="number" id="reserva" name="reserva" class="input" />

        <div class='botones_container'>
            <input type='button' id='consultar' value='Consultar' onClick='getStatus()' class="button button-primary button-large" />
        </div>

        <div id="info_user"></div>

        <div class='botones_container confirmaciones'>
            <input type='button' id='correo_openpay' value='Enviar solicitud' onClick='mail_openpay()' class="button button-primary button-large" />
        </div>
    </form>

</div>

<style type="text/css">
    .contenedor{
        display: table;
        width: 100%;
    }
    .contenedor > div {
        display: table-cell;
        width: 50%;
        font-size: 14px;
    }
    #form label {
        display: inline-block !important;
        font-weight: 600;
        margin: 0px !important;
    }

    .botones_container {
        margin: 10px 0px 0px;
        padding: 10px 0px 0px;
        text-align: right;
        border-top: solid 1px #CCC;
    }

    .contenedor h2, .contenedor .h2 {
        font-size: 20px;
        text-transform: uppercase;
        border-bottom: solid 2px;
        margin-right: 10px;
    }

    @media only screen and (max-width: 767px) {
        .contenedor{
            display: block;
            width: 100%;
        }
        .contenedor > div {
            display: block;
            width: 100%;
        }

        .contenedor > div {
            font-size: 13px;
            margin-bottom: 14px;
        }

        h2, .h2 {
            font-size: 16px;
            margin-bottom: 10px;
        }
    }
</style>
