var table; 
var table_modal; 
jQuery(document).ready(function() {
    loadTabla();
    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });
    jQuery("#btn-search").on("click", function(e){
        loadTabla();
    });
});

function loadTabla(tabla, accion){
    if( tabla == undefined ){ tabla = 'example'; }
    if( accion == undefined ){ accion = 'list'; }

    var _table = jQuery('#'+tabla).DataTable();
    _table.destroy();
    _table = jQuery('#'+tabla).DataTable({
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
        "scrollX": true,
        "ajax": {
            "url": ADMIN_AJAX+'?action=vlz_'+MODULO_ACTUAL+'_'+accion,
            "type": "POST"
        }
    });

    if( tabla == 'example' ){
        table = _table;
    }else{
        table_modal = _table;
    }
}

function init_modal_2(data){
    hide_modal();
    jQuery(".modal > div > span").html(data["titulo"]);
    jQuery.ajax({
        async:true, 
        cache:false, 
        type: 'POST', 
        url: ADMIN_AJAX+'?action=vlz_'+data["modal"],
        data: data["info"], 
        success:  function(HTML){
            jQuery(".modal > div > div").html( HTML );
            jQuery(".modal").css("display", "block");
            jQuery("body").css("overflow", "hidden");
        },
        beforeSend:function(){},
        error:function(e){
            console.log(e);
        }
    });
    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });
}

function _new(e) {
    init_modal_2({
        "titulo": e.attr("data-titulo"),
        "modal": e.attr("data-modal"),
        "info": {
            "ID": e.attr("data-id")
        }
    });
}

function _edit(e) {
    init_modal_2({
        "titulo": e.attr("data-titulo"),
        "modal": e.attr("data-modal"),
        "info": {
            "ID": e.attr("data-id")
        }
    });
}

function _del_form(e) {
    init_modal_2({
        "titulo": e.attr("data-titulo"),
        "modal": e.attr("data-modal"),
        "info": {
            "ID": e.attr("data-id")
        }
    });
}

function _insert(id) {

    jQuery("#"+id).unbind("submit").bind("submit", function(e){
        e.preventDefault();
    
        var btn_txt = jQuery("#btn_submit_modal").html();
        jQuery("#btn_submit_modal").html("Procesando...");
        jQuery("#btn_submit_modal").prop("disabled", true);

        jQuery.post(
            ADMIN_AJAX+'?action=vlz_'+jQuery(this).attr("data-modulo")+"_insert",
            jQuery(this).serialize(),
            function(data){
                console.log( data );
                table.ajax.reload();
                if( table_modal != "" ){
                    table_modal.ajax.reload();
                }
                jQuery("#btn_submit_modal").html(btn_txt);
                jQuery("#btn_submit_modal").prop("disabled", false);

                show_msg(data);
            },
            'json'
        );
    });
}

function _update(id) {

    jQuery("#"+id).unbind("submit").bind("submit", function(e){
        e.preventDefault();
    
        var btn_txt = jQuery("#btn_submit_modal").html();
        jQuery("#btn_submit_modal").html("Procesando...");
        jQuery("#btn_submit_modal").prop("disabled", true);

        jQuery.post(
            ADMIN_AJAX+'?action=vlz_'+jQuery(this).attr("data-modulo")+"_update",
            jQuery(this).serialize(),
            function(data){
                console.log( data );
                table.ajax.reload();
                if( table_modal != "" ){
                    table_modal.ajax.reload();
                }
                jQuery("#btn_submit_modal").html(btn_txt);
                jQuery("#btn_submit_modal").prop("disabled", false);

                show_msg(data);
            },
            'json'
        );
    });
}

function _delete(id) {

    jQuery("#"+id).unbind("submit").bind("submit", function(e){
        e.preventDefault();
    
        var btn_txt = jQuery("#btn_submit_modal").html();
        jQuery("#btn_submit_modal").html("Procesando...");
        jQuery("#btn_submit_modal").prop("disabled", true);

        jQuery.post(
            ADMIN_AJAX+'?action=vlz_'+jQuery(this).attr("data-modulo")+"_delete",
            jQuery(this).serialize(),
            function(data){
                console.log( data );
                table.ajax.reload();
                if( table_modal != "" ){
                    table_modal.ajax.reload();
                }
                jQuery("#btn_submit_modal").html(btn_txt);
                jQuery("#btn_submit_modal").prop("disabled", false);

                show_msg(data);
            },
            'json'
        );
    });
}

function show_msg(data){
    hide_modal();
    if( data.error == "" ){
        type = 'sucess';
        jQuery(".modal > div > p").html( data.msg );
    }else{
        type = 'error';
        jQuery(".modal > div > p").html( data.error );
    }
    switch( type ){
        case 'error':
            jQuery(".modal > div > p").addClass('error');
        break;
        case 'sucess':
            jQuery(".modal > div > p").addClass('sucess');
            setTimeout(function(){
                jQuery(".modal").css("display", "none");
            }, 1500);
        break;
    }
}

function hide_modal(){
    jQuery(".modal > div > p").removeClass('error');
    jQuery(".modal > div > p").removeClass('sucess');
}