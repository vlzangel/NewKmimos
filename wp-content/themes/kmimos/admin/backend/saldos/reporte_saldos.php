<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/saldos/reporte_saldos.css'>
<script src='<?php echo getTema(); ?>/admin/backend/saldos/reporte_saldos.js'></script>

<div class="container_listados">
    <div class='titulos' style="padding: 20px 0px 0px;">
        <h2>Ajuste de Saldos</h2>
        <hr>
    </div>
    <?php
        $current_user = wp_get_current_user();
        $admin_user_id = $current_user->ID;

        if( $admin_user_id == 367){ ?>
            <div class="container_listados">
                <div class='col-md-12'>
                    <table id="example" class="table table-striped table-bordered nowrap" cellspacing="0" style="min-width: 100%;">
                        <thead>
                            <tr>
                                <th width="20">#</th>
                                <th>Cliente</th>
                                <th>Saldo Anterior</th>
                                <th>Saldo Nuevo</th>
                                <th>Administrador</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div> <?php 
        }
    ?>
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
 