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

<div class="module_botones">
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

        var table = "";
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
                            json = postCargaTable(json);
                        }
                        return json.data;
                    } 
                }
            });
        } );

        var DESDE = new Date( "2018-09-01 00:00:00" ).getTime();
        var HASTA = new Date( "<?= date("Y-m-d"); ?> 00:00:00" ).getTime();
        var eliminar = [];
        var data = [];
        function postCargaTable(json){
            eliminar = [];
            data = [];
            DESDE = new Date( jQuery("#desde").val()+" 00:00:00" ).getTime();
            HASTA = new Date( jQuery("#hasta").val()+" 00:00:00" ).getTime();
            jQuery.each(json.data, function( index, value ) {

                var temp = value[4].split("-");

                var FECHA = new Date( temp[1]+"-"+temp[2]+"-"+temp[0]+" 00:00:00" ).getTime();

                console.log(FECHA);

                if( DESDE <= FECHA && FECHA <= HASTA ){
                    data.push( value );
                }else{
                    eliminar.push(index);
                }
            });
            json.data = data;
            return json;
        }
        jQuery("#desde").on("change", function(e){ table.ajax.reload(); });
        jQuery("#hasta").on("change", function(e){ table.ajax.reload(); });

    /* Fin Tabla y Filtros de Fechas */





    jQuery('.filters select, .filters input').change(function(e){
        setTimeout(function(){
            user_filter();
            duration_filter();
        }, 1000);
    });

    function user_filter(){
        var users = [];
        jQuery('table tbody tr:not(.noshow)').each(function(e){
            var user=jQuery(this).find('.user').data('user');
            if(jQuery.inArray(user,users)<0){
                users.push(user);
            }
        });
        jQuery('#user_filter').find('span').html(users.length);
    }

    function duration_filter(){
        var times=[];
        jQuery('table tbody tr:not(.noshow)').each(function(e){
            var user=jQuery(this).find('.duration').data('user');
            var duration=jQuery(this).find('.duration').data('count');
            var status=jQuery(this).data('status');
            if(status!='cancelled' && status!='modified' && status!='unpaid'){
                if(times[user] == undefined){
                    times[user]=duration;
                }else{
                    times[user]=times[user]-(-duration);
                }
            }
        });
        for(duser in times){
            jQuery('table tbody tr td.duration_total[data-user="'+duser+'"]').html(times[duser]);
        }
    }

    user_filter();
    duration_filter();
</script>

<?php

/*
   $sql = "
            SELECT
                posts.*,
                posts.ID AS ID,
                posts.post_type AS ptype,
                posts.post_status AS status,
                posts.post_parent AS porder,
                posts.post_author AS customer
            FROM
                wp_posts AS posts
                LEFT JOIN wp_postmeta AS postmeta ON (postmeta.post_id=posts.post_parent AND postmeta.meta_key='_wlabel')
                LEFT JOIN wp_usermeta AS usermeta ON (usermeta.user_id=posts.post_author AND usermeta.meta_key='_wlabel')
            WHERE
                posts.post_type = 'wc_booking' AND
                (
                    usermeta.meta_value = '{$wlabel}' OR
                    postmeta.meta_value = '{$wlabel}'
                )
                AND NOT
                posts.post_status LIKE '%cart%'
            ORDER BY
              posts.ID DESC
     ";

   $bookings = $wpdb->get_results($sql);

   foreach($bookings as $key => $booking){
        $ID=$booking->ID;
        $date=strtotime($booking->post_date);
        $customer=$booking->post_author;
        $status=$booking->post_status;
        $order=$booking->post_parent;
        $status_name=$status;

        $_metas_booking = get_post_meta($ID);
        $_metas_order = get_post_meta($order);
        $IDproduct=$_metas_booking['_booking_product_id'][0];
        $IDcustomer=$_metas_booking['_booking_customer_id'][0];
        $IDorder_item=$_metas_booking['_booking_order_item_id'][0];

        $_metas_booking_start=strtotime($_metas_booking['_booking_start'][0]);
        $_metas_booking_end=strtotime($_metas_booking['_booking_end'][0]);
        $duration = floor(($_metas_booking_end-$_metas_booking_start) / (60 * 60 * 24));

        $_meta_WCorder = wc_get_order_item_meta($IDorder_item,'');
       // $_meta_WCorder_line_total = wc_get_order_item_meta($IDorder_item,'_line_total');
        $_meta_WCorder_line_total = wc_get_order_item_meta($IDorder_item,'_line_subtotal');
        $_meta_WCorder_duration = wc_get_order_item_meta($IDorder_item,'Duración');
        $_meta_WCorder_caregiver = wc_get_order_item_meta($IDorder_item,'Ofrecido por');

        //SERVICES
        $post = get_post($IDproduct);
        $services = $post->post_name;
        $services=explode('-',$services);
        if(count($services)>0){
           $services=trim($services[0]);
        }else{
           $services='';
        }

       //DURATION
       $period = 1;
       if(strpos($duration, 'semana') !== false){
           $period = 7;
       }else if(strpos($duration, 'mes') !== false){
           $period = 30;
       }

       $duration=str_replace(array('días','día','dias','dia','day', 'semana', 'semanas', 'mes'),'',$duration);
       $duration=trim($duration);
       $duration_text=' Dia(s)';

       if($services=='hospedaje'){
           $duration=(int)$duration;//-1
           $duration_text=' Noche(s)';
       }

       if($duration<=0){
           $duration=(int)$duration+1;
       }

       $duration_text= $duration.$duration_text;
       $duration_text.='<br>'.date('d/m/Y',(int) strtolower($_metas_booking_start));
       $duration_text.='<br>'.date('d/m/Y',(int) strtolower($_metas_booking_end));

       $_meta_WCorder_services_additional=array();
        foreach($_meta_WCorder as $meta=>$value){
            if(strpos($meta,'Servicios Adicionales') !== false){
                $_meta_WCorder_services_additional[]=str_replace('(precio por mascota)','',$value[0]);
            }
        }
        $_meta_WCorder_services_additional=implode(',',$_meta_WCorder_services_additional);

        //CUSTOMER
        $_metas_customer = get_user_meta($customer);
        $_customer_name = $_metas_customer['first_name'][0] . " " . $_metas_customer['last_name'][0];

       //CAREGIVER
        $caregiver = $post->post_author;
        $_metas_caregiver = get_user_meta($caregiver);
        $_caregiver_name = $_metas_caregiver['first_name'][0] . " " . $_metas_caregiver['last_name'][0];

        $product = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID ='$IDproduct'");

        $html='
        <tr class="trshow" data-day="'.date('d',$date).'" data-month="'.date('n',$date).'" data-year="'.date('Y',$date).'" data-status="'.$status.'">
            <td>'.$booking->ID.'</td>
            <td>'.date('d/m/Y',$date).'</td>
            <td class="user" data-user="'.$customer.'">'.$_customer_name.'</td>
            <td>'.$_caregiver_name.'</td>
            <td>'.$services.'</td>
            <td class="status">'.$status_name.'</td>
            <td class="duration" data-user="'.$customer.'" data-count="'.$duration.'">'.$duration_text.'</td>
            <td class="duration_total" data-user="'.$customer.'"></td>
            <td>'.$_meta_WCorder_services_additional.'</td>
            <td>MXN '.number_round($_meta_WCorder_line_total).'</td>';

        if( $wlabel == "volaris"){
            $html .= '
                <td>'.number_round($_meta_WCorder_line_total*0.20).'</td>
                <td>'.number_round($_meta_WCorder_line_total*0.20*($WLcommission/100)).'</td>
            ';
        }

        $html .= '</tr>';


        echo $html;
    }


 ?>
        </tbody>
    </table>
    </div>
</div>
*/ ?>



