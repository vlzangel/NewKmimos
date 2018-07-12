<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/facturas/reporte_facturas.css'>
<script src='<?php echo getTema(); ?>/admin/backend/facturas/reporte_facturas.js'></script>

<div class="container_listados">



    <div class='titulos'>
        <h2>Control de Facturas</h2>
        <hr>
    </div>

    <div class='col-md-12'>
        <div class='row'>
            <div class="col-sm-12 col-md-6">
                <button class="btn btn-defaut" id="select-all"><i class="fa fa-list"></i> Marcar/Desmarcar Todos</button>
                <button class="btn btn-defaut" id="download-zip"><i class="fa fa-cloud-download"></i> Zip</button>
            </div>
            <div class="col-sm-12 col-md-6 container-search">
                <form id="form-search" name="search">
                    <span><label class="fecha">Desde: </label><input type="date" name="ini" value=""></span>
                    <span><label class="fecha">Hasta: <input type="date" name="fin" value=""></label></span> 
                    <button class="btn btn-defaut" id="btn-search"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
        <hr>
    </div>
    <div class="clear"></div>
    <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;" >
        <thead>
            <tr>
                <th></th>
                <th>Fecha Creaci&oacute;n</th>
                <th>Serie y Folio</th>
                <th>Cuidador</th>
                <th>Cliente</th>
                <th>No. Referencia</th>
                <th>Serie Certificado</th>
                <th>Serie Certificado SAT</th>
                <th>Folio Fiscal</th>
                <th>Receptor</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

 