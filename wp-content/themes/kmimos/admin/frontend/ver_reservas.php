<?php
    $orden = vlz_get_page();

    $datos_generales = kmimos_datos_generales_desglose($orden, false, false);

    $detalles_cliente = $datos_generales["cliente"];
    $detalles_cuidador = $datos_generales["cuidador"];
    $detalles_mascotas = $datos_generales["mascotas"];

    $cliente_email  = $datos_generales["cliente_email"];
    $cuidador_email = $datos_generales["cuidador_email"];

    /* Detalles del servicio */

    $detalles = kmimos_desglose_reserva($orden);

    $msg_id_reserva = $detalles["msg_id_reserva"];
    $aceptar_rechazar = $detalles["aceptar_rechazar"];
    $detalles_servicio = $detalles["detalles_servicio"];
    $detalles_factura = $detalles["detalles_factura"];

    $titulo = '<h2>Detalles de la solicitud:</h2>';

    $CONTENIDO .= '
    <div class="vlz_titulos_superior">
        <a href="'.get_home_url().'/perfil-usuario/reservas/" class="volver">
            Volver
        </a> - Detalles de la reserva - '.$orden.'
    </div>

    <div class="row_mitad">
        <section>
            <div class="vlz_titulos_tablas">Detalles del cliente</div>
            <div class="vlz_contenido_tablas">
                '.$detalles_cliente.'
            </div>
        </section>

        <section>
            <div class="vlz_titulos_tablas">Detalles del servicio</div>
            <div class="vlz_contenido_tablas">
                '.$detalles_servicio.'
            </div>
        </section>
    </div>

    <div class="row_full">
        <div class="vlz_titulos_tablas">Detalles de las mascotas</div>
        <div class="vlz_contenido_tablas">
            '.$detalles_mascotas.'
        </div>
    </div>

    <div class="row_full">
        <div class="vlz_titulos_tablas">Detalles de facturación</div>
        <div class="vlz_contenido_tablas desglose">
            '.$detalles_factura.'
        </div>

    </div>';
?>