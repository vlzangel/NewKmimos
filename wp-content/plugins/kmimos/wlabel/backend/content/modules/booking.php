<?php

global $wpdb;
$kmimos_load=dirname(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))))).'/wp-load.php';
if(file_exists($kmimos_load)){
    include_once($kmimos_load);
}

include dirname(dirname(dirname(dirname(__DIR__)))).'/dashboard/core/ControllerReservas.php';

function number_round($number){
    $number=(round($number*100))/100;
    $number=number_format($number, 2, ',', '.');
    return $number;
}

$wlabel=$_wlabel_user->wlabel;
$WLcommission=$_wlabel_user->wlabel_Commission();

/*
    $_wlabel_user->wlabel_Options('booking');
    $_wlabel_user->wLabel_Filter(array('trdate'));
    $_wlabel_user->wlabel_Export('booking','RESERVAS','table');
*/

$wlabel = $_SESSION["label"]->wlabel; ?>

<div class="module_title">
    Reservas
</div>

<div class="module_botones" style="display: none;">
    <table>
        <tr>
            <td><strong>Desde:</strong></td>
            <td><strong>Hasta:</strong></td>
        </tr>
        <tr>
            <td><input type="date" id="desde" name="desde" class="form-control form-control-sm" value="2018-09-01" /></td>
            <td><input type="date" id="hasta" name="hasta" class="form-control form-control-sm" value="<?= date("Y-m-d"); ?>" /></td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="">
        <table id="_example_" class="table table-striped table-bordered nowrap" style="width:100%" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th># Reserva</th>
                    <th>Flash</th>
                    <th>Estatus</th>
                    <th>Fecha Reservacion</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Noches</th>
                    <th># Mascotas</th>
                    <th># Noches Totales</th>
                    <th>Cliente</th>
                    <th>Correo Cliente</th>
                    <th>Tel&eacute;fono Cliente</th>
                    <th>Eventos de Reservas</th>
                    <th>T&eacute;rminos y Condiciones</th>
                    <th>Recompra (1Mes)</th>
                    <th>Recompra (3Meses)</th>
                    <th>Recompra (6Meses)</th>
                    <th>Recompra (12Meses)</th>
                    <th>Donde nos conocio?</th>
                    <th>Mascotas</th>
                    <th>Razas</th>
                    <th>Edad</th>
                    <th>Cuidador</th>
                    <th>Correo Cuidador</th>
                    <th>Tel&eacute;fono Cuidador</th>
                    <th>Servicio Principal</th> 
                    <th>Servicios Especiales</th> <!-- Servicios adicionales -->
                    <th>Estado</th>
                    <th>Municipio</th>
                    <th>Forma de Pago</th>
                    <th>Tipo de Pago</th>
                    <th>Total a pagar ($)</th>
                    <th>Monto Pagado ($)</th>
                    <th>Monto Remanente ($)</th>
                    <th># Pedido</th>
                    <th>Observaci&oacute;n</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<style type="text/css">
    .modal-header { display: block; }
    .modal-title { font-size: 17px; }
    .modal-body td{ font-size: 13px; }
    .modal-body table td { vertical-align: top; }
    .mostrarInfo{ cursor: pointer; text-align: center; font-weight: 600; color: #0f80ca; }
    .mostrarInfo:hover{ color: #52bbff; }
</style>

<div class="modal fade" id="respModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar Ventana">×</button>
                <h4 class="modal-title">Informaci&oacute;n sobre los t&eacute;rminos y condiciones</h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>  
</div>

<script type="text/javascript">

    function mostrarEvento(user_id){
        params = {'id_affiliate' : user_id};
        jQuery.post(
            "<?= get_home_url(); ?>/wp-content/plugins/kmimos/wlabel/backend/content/ajax/terminos_info.php",
            { user_id: user_id },
            function(data){
                if( data.error == "no" ){
                    var HTML = "<table>";
                    HTML += "   <tr><td><strong>IP: &nbsp;&nbsp;</strong></td><td><span>"+data.info.ip+"</span></td>";
                    HTML += "   <tr><td><strong>Fecha: &nbsp;&nbsp;</strong></td><td><span>"+data.info.fecha+"</span></td>";
                    HTML += "   <tr><td><strong>Dispositivo: &nbsp;&nbsp;</strong></td><td><span>"+data.info.dispositivo+"</span></td>";
                    HTML += "</table>";
                    jQuery("#respModal .modal-body").html( HTML );
                    jQuery('#respModal').modal('show');
                }else{
                    jQuery("#respModal .modal-body").html( "<div>No hay informaci&oacute;n disponible</div>" );
                    jQuery('#respModal').modal('show');
                }
            }, "json"
        );
    }

    /* Tabla y Filtros de Fechas */

        jQuery(document).ready(function() {
            table = jQuery('#_example_').DataTable({
                "language": {
                    "emptyTable":           "No hay datos disponibles en la tabla.",
                    "info":                 "Del _START_ al _END_ de _TOTAL_ ",
                    "infoEmpty":            "Mostrando 0 registros de un total de 0.",
                    "infoFiltered":         "(filtrados de un total de _MAX_ registros)",
                    "infoPostFix":          " (actualizados)",
                    "lengthMenu":           "Mostrar _MENU_ registros",
                    "loadingRecords":       "Cargando...",
                    "processing":           "Procesando...",
                    "search":               "Buscar:",
                    "searchPlaceholder":    "Dato para buscar",
                    "zeroRecords":          "No se han encontrado coincidencias.",
                    "paginate": {
                        "first":            "Primera",
                        "last":             "Última",
                        "next":             "Siguiente",
                        "previous":         "Anterior"
                    },
                    "aria": {
                        "sortAscending":    "Ordenación ascendente",
                        "sortDescending":   "Ordenación descendente"
                    }
                },
                dom: '<"top"l>Bfrtip',
                buttons: [
                    'csv', 'excel'
                ],
                "scrollX": true,
                "ajax": {
                    "url": "<?= get_home_url(); ?>/wp-content/plugins/kmimos/wlabel/backend/content/ajax/booking_data.php",
                    "type": "POST",
                    "dataSrc":  function ( json ) {
                        if(typeof postCargaTable === 'function') {
                            json = postCargaTable(json, 4);
                        }
                        return json.data;
                    } 
                }
            });
        } );

        jQuery("#desde").on("change", function(e){ 
            table.ajax.reload();
        });
        jQuery("#hasta").on("change", function(e){ 
            table.ajax.reload();
        });

    /* Fin Tabla y Filtros de Fechas */

</script>