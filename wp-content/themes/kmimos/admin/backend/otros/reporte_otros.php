<link rel='stylesheet' type='text/css' href='<?php echo getTema() ?>/admin/backend/otros/reporte_otros.css'>
<script src='<?php echo getTema(); ?>/admin/backend/otros/reporte_otros.js'></script>

<div class="container_listados">

    <div class='titulos' style="padding: 20px 0px 0px;">
        <h2>Ajuste  de Estatus de Reservas</h2>
        <hr>
    </div>
    
    <?php
        $current_user = wp_get_current_user();
        $admin_user_id = $current_user->ID;
        $permitidos = [
            367,
            8966
        ];
        if( in_array($admin_user_id, $permitidos) ){ ?>
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

        <label>Id Reserva</label>
            <input type="number" id="reserva" name="reserva" class="input" />

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
