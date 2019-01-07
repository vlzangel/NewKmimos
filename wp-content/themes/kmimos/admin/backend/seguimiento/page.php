<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/seguimiento/css.css'>
<script src='<?php echo getTema(); ?>/admin/backend/seguimiento/js.js'></script>

<div class="container_listados">

    <div class='titulos'>
        <h2>Control de Clientes</h2>
        <hr>
    </div>

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
    <div class="clear"></div>

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


</div>
 