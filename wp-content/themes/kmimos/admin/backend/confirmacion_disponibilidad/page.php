<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/confirmacion_disponibilidad/css.css'>
<script src='<?php echo getTema(); ?>/admin/backend/confirmacion_disponibilidad/js.js'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Correo de confirmación</h2>
        <hr>
    </div>
    <!--
    <div class='col-md-12'>
        <div class='row'>
            <div class="col-sm-12 col-md-12 container-search text-right">
                <form id="form-search" name="search">
                    <span><label class="fecha">Desde: </label><input type="date" name="ini" value="<?php echo $fecha['ini']; ?>"></span>
                    <span><label class="fecha">Hasta: <input type="date" name="fin" value="<?php echo $fecha['fin']; ?>"></label></span> 
                    <button class="btn btn-defaut" id="btn-search"><i class="fa fa-search"></i> Buscar</button>
                </form>
            </div>
        </div>
        <hr>
    </div>
    -->
    <div class="clear"></div>

    <!--
    <div class='col-md-12'>
        <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Reservas</th>
                    <th>Conocer</th>
                    <th>Fecha</th>
                    <th>Etiquetas</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    -->

    <div class='col-md-12 contenedor'>

        <form id="form" method="POST">

            <label>Id Reserva</label>
            <input type="number" id="id" name="id" class="input" />

            <div class='botones_container confirmaciones'>
                <input type='submit' id='submit' value='Enviar solicitud' class="button button-primary button-large" />
            </div>
        </form>
        
    </div>


</div>
 

<style type="text/css">

    #form label {
        display: inline-block !important;
        font-weight: 600;
        margin: 0px !important;
    }

    #form .input {
        display: block !important;
        width: 100%;
        box-sizing: border-box;
        margin: 0px;
    }

    .botones_container {    
        margin: 10px 0px 0px;
        padding: 10px 0px 0px;
        text-align: right;
        border-top: solid 1px #CCC;
        background: #FFF;
        border-radius: 0px;
    }

</style>