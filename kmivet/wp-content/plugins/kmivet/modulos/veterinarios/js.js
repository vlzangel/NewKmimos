function _activar(id){
    var confirmed = confirm("¿Esta Seguro de Activar al Veterinario?");
    if (confirmed == true) {
        jQuery.post(
            AJAX+"&t=ajax&a=activar",
            {
                user_id: id
            },
            function( data ){
               console.log( data );
               table.ajax.reload();
            }
        );
    }
}

function _desactivar(id){
    var confirmed = confirm("¿Esta Seguro de Desactivar al Veterinario?");
    if (confirmed == true) {
        jQuery.post(
            AJAX+"&t=ajax&a=desactivar",
            {
                user_id: id
            },
            function( data ){
               console.log( data );
               table.ajax.reload();
            }
        );
    }
}