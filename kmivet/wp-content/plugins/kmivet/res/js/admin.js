var table = '';
jQuery(document).ready(function() {

    var order = jQuery('#example').parent().data("order");
    var order_dir = jQuery('#example').parent().data("order-dir");

    if( order != undefined ){
        order = parseInt(order);
    }else{
        order = 0;
        order_dir = 'desc';
    }

    table = jQuery('#example').DataTable( {
        responsive: {
            details: {
                display: jQuery.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return SIN+': '+data[0]+' '+data[1];
                    }
                } ),
                renderer: jQuery.fn.dataTable.Responsive.renderer.tableAll()
            }
        },
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
            "paginate": { "first": "Primera", "last": "Última", "next": "Siguiente", "previous": "Anterior" },
            "aria": { "sortAscending":    "Ordenación ascendente", "sortDescending":   "Ordenación descendente" }
        },
        "ajax": { "url": AJAX+"&t=ajax&a=list", "type": "POST" },
        dom: 'lBfrtip',
        buttons: [ 'excelHtml5', 'csvHtml5', 'pdfHtml5', ],
        "lengthMenu": [[10, 20, 30, -1], [10, 20, 30, "Todos"]],
        "order": [[ order, order_dir ]],
    } );

    jQuery("#search").keyup( (e) => {
        jQuery(".dataTables_filter input").val( jQuery("#search").val() );
        jQuery(".dataTables_filter input").keyup();
    } );
    jQuery("#vlz_excel").on('click', (e) => { jQuery(".buttons-excel").click(); } );
    jQuery("#vlz_pdf").on('click',   (e) => { jQuery(".buttons-pdf").click();   } );
    jQuery("#form_modal").on("submit", (e) => {
        e.preventDefault();
        var a = jQuery("#modal_accion").val();
        var t = jQuery("#modal_type").val();

        var btn = jQuery("#submit_btn_accion").html();
        jQuery("#submit_btn_accion").html("Procesando...");
        jQuery("#submit_btn_accion").prop('disabled', true);

        jQuery.post(
            AJAX+'&t='+t+'&a='+a,
            jQuery("#form_modal").serialize(),
            (data) => {
                console.log( data );
                jQuery(".modal_msg").html( data.msg );
                jQuery(".modal_msg").removeClass( 'modal_msg_ok' );
                jQuery(".modal_msg").removeClass( 'modal_msg_ko' );
                jQuery(".modal_msg").addClass( 'modal_msg_show' );
                if( data.status ){
                    jQuery(".modal_msg").addClass( 'modal_msg_ok' );
                }else{
                    jQuery(".modal_msg").addClass( 'modal_msg_ko' );
                }
                table.ajax.reload();


                setTimeout( (e) => {
                    jQuery(".modal_msg").removeClass( 'modal_msg_show' );
                }, 1000 );

                if( t == 'ajax' && a == 'delete' ){
                    setTimeout( (e) => {
                        cerrarModal('#mymodal');
                        jQuery("#submit_btn_accion").html(btn);
                        jQuery("#submit_btn_accion").prop('disabled', false);
                    }, 2000 );
                }else{
                    jQuery("#submit_btn_accion").html(btn);
                    jQuery("#submit_btn_accion").prop('disabled', false);
                }
            },
            'json'
        );
    });

    jQuery('#mymodal').on('hidden.bs.modal', function (e) {
        cerrarModal('#mymodal');
    });

    jQuery("#close_modal").on('click', function(e){
        cerrarModal('#mymodal');
    });

    jQuery('.vlz_bg_close').on('click', function(e){
        cerrarModal('#'+jQuery(this).parent().attr('id'));
    });

} );

function init_table(id){
    jQuery(id).DataTable( {
        responsive: {
            details: {
                display: jQuery.fn.dataTable.Responsive.display.modal( {
                    header: function ( row ) {
                        var data = row.data();
                        return SIN+': '+data[0]+' '+data[1];
                    }
                } ),
                renderer: jQuery.fn.dataTable.Responsive.renderer.tableAll()
            }
        },
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
            "paginate": { "first": "Primera", "last": "Última", "next": "Siguiente", "previous": "Anterior" },
            "aria": { "sortAscending":    "Ordenación ascendente", "sortDescending":   "Ordenación descendente" }
        }, /*
        dom: 'lBfrtip',
        buttons: [ 'excelHtml5', 'pdfHtml5', ], */
        "ordering": false,
        "lengthMenu": [[5], [5]] // ,
        //"order": [[ 0, "desc" ]],
    } );
}

function cerrarModal(id){
    jQuery(id).removeClass('show');
    jQuery(".modal-backdrop.fade").removeClass('show');
    if (stream) {
        stream.getTracks().forEach(function(track) {
            track.stop();
        });
    }
}

function _new(){
    _modal( {
        tit: 'Nuev'+GEN+' '+SIN,
        url: AJAX+"&t=form&a=new",
        btn: jQuery("#vlz_add").data("boton"),
        act: 'insert',
        typ: 'ajax',
        dat: { id: '' }
    } );
}

function _ver(ID){
    jQuery("#form_modal").attr("class", "_ver");
    _modal( {
        tit: 'Ver '+SIN,            // Titulo del modal
        url: AJAX+"&t=form&a=show",  // Url a cargar en el modal
        btn: '-',                   // Texto en el boton del modal
        act: '',                    // Accion que realizara el boton del modal
        dat: { id: ID }             // Data a pasar en la carga del modal
    } );
}

function _edit(ID){
    jQuery("#form_modal").attr("class", "_edit");
    _modal( {
        tit: 'Editar '+SIN,    // Titulo del modal
        url: AJAX+"&t=form&a=new",  // Url a cargar en el modal
        btn: 'Actualizar',          // Texto en el boton del modal
        act: 'update',              // Accion que realizara el boton del modal
        dat: { id: ID }             // Data a pasar en la carga del modal
    } );
}

function _delete(ID){
    jQuery("#form_modal").attr("class", "_delete");
    _modal( {
        tit: 'Eliminar '+SIN,           // Titulo del modal
        url: AJAX+"&t=form&a=delete",   // Url a cargar en el modal
        btn: 'Eliminar',                // Texto en el boton del modal
        act: 'delete',                  // Accion que realizara el boton del modal
        dat: { id: ID }                 // Data a pasar en la carga del modal
    } );
}

function _recibo(ID){
    window.open(AJAX+"&a=recibo&t=ajax&id="+ID, '_blank');
}

function _update_table(_this){
    var _tabla = '';
    if( _this.data('tabla') != undefined ){ _tabla = _this.data('tabla'); }
    jQuery.post(
        AJAX+"&t=ajax&a=update_table",
        {
            id: _this.data('id'),
            campo: _this.data('campo'),
            valor: _this.val(),
            tabla: _tabla
        },
        function( data ){
           console.log( data );
        }
    );
}

function _modal( i ){
    jQuery("#modal_content").css("display", "none");
    jQuery(".spinner_container").css("display", "block");
    jQuery("#modal_title").html( i.tit );
    jQuery('#mymodal').modal('show');
    jQuery('#mymodal').addClass('show');
    jQuery('.modal-backdrop.fade').addClass('show');

    if( i.btn == '-' ){
        jQuery(".modal-footer").css('display', 'none');
    }else{
        jQuery(".modal-footer").css('display', 'flex');
        jQuery("#submit_btn_accion").html( i.btn );
        jQuery("#modal_accion").val( i.act );
        jQuery("#modal_type").val( 'ajax' );
    }

    jQuery.post(
        i.url,
        i.dat,
        function(H){
            jQuery(".spinner_container").css("display", "none");
            jQuery("#modal_content").html(H);
            jQuery("#modal_content").css("display", "block");

            if( jQuery(".container_img_select input.emisor_img").length > 0 ){
                jQuery(".container_img_select input.emisor_img").unbind("change").bind("change", function(e) {
                    var parent = jQuery( this ).parent();
                    jQuery.each(e.target.files, function(i, d){
                        var reader = new FileReader();
                        reader.onload = (function(theFile) {
                            return function(res) {
                                parent.find(".receptor_img").attr( 'value', res.target.result );
                                parent.css( "background-image", "url(" + res.target.result + ")" );
                            };
                        })(d);
                        reader.readAsDataURL(d);
                    });
                });
            }
        }
    );
}

/* Camara */

function vlzInitComponenteCamaras(){
    jQuery(".vlzCamara .vlzTomar").unbind('click').bind('click', function(e){
        e.preventDefault();
        var parent = jQuery(this).parent().parent();
        var $estado = parent.find('.vlzEstado');
        if (stream) {
            var $video = parent.find('video')[0];
            var $canvas = parent.find('canvas')[0];
            $video.pause();
            let contexto = $canvas.getContext("2d");
            $canvas.width = $video.videoWidth;
            $canvas.height = $video.videoHeight;
            contexto.drawImage($video, 0, 0, $canvas.width, $canvas.height);
            let foto = $canvas.toDataURL();
            parent.find('.container_img_select').css( "background-image", "url(" + foto + ")" );
            parent.find(".receptor_img").attr( 'value', foto );
            stream.getTracks().forEach(function(track) {
                track.stop();
            });
            parent.find('.vlzTomar').css("display", "none");
            parent.find('.vlzInitCamara').css("display", "inline-block");
            parent.find('video').css("display", "none");
        }else{
            $estado.html("Debe iniciar la camara primero");
        }
    });

    jQuery(".vlzCamara .vlzInitCamara").unbind('click').bind('click', function(e){
        e.preventDefault();
        var parent = jQuery(this).parent().parent();
        if (!tieneSoporteUserMedia()) {
            parent.find('.vlzEstado').html("Parece que tu navegador no soporta esta característica. Intenta actualizarlo.");
        }

        vlzGetDispositivos().then(dispositivos => {
            const dispositivosDeVideo = [];
            dispositivos.forEach(function(dispositivo) {
                const tipo = dispositivo.kind;
                if (tipo === "videoinput") {
                    dispositivosDeVideo.push(dispositivo);
                }
            });
            if (dispositivosDeVideo.length > 0) {
                mostrarStream(dispositivosDeVideo[0].deviceId, parent);
                parent.find('.vlzInitCamara').css("display", "none");
                parent.find('.vlzTomar').css("display", "inline-block");
                parent.find('video').css("display", "block");
            }
        });

    });
}