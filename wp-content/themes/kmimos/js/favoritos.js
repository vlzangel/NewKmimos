jQuery( document ).ready(function() {
    jQuery(".favorito").on("click", function(e){
        var fav_active = jQuery(this).attr('data-active');
        var fav_num = jQuery(this).attr('data-num');
        if( fav_active == "false" ){
            var fav = jQuery(this);
            var data = {
                'action': 'get_favorites',
                'item': fav_num,
                'active': fav_active,
                'security': ''
            };
            jQuery.post( HOME + '/procesos/generales/favorites.php', data, function( data ) {
                var result = data;
                if( result['user'] > 0 ){
                    fav.attr('data-active', result['active']);
                    fav.addClass('favoritos_delete');
                }else{
                    jQuery('#popup-iniciar-sesion').modal('show');
                }
            });
        }else{
            var obj = jQuery(this);
            var fav_user = jQuery(this).attr('data-user');
            var data = {
                accion: "delete_favorito",
                cuidador_id: fav_num,
                user_id: fav_user
            };
            jQuery.ajax({
                type: "POST",
                url: HOME + '/procesos/perfil/index.php', 
                data: {
                    accion: "delete_favorito",
                    cuidador_id: fav_num,
                    user_id: fav_user
                },
                success: function(data){
                    console.log( data );
                    obj.removeClass('favoritos_delete');
                    obj.attr('data-active', false);
                }
            });
        }


    });
});


/*jQuery(document).on('click','favorito',function(){
    var fav = jQuery(this);
    var fav_num = jQuery(this).data('num');
    var fav_active = jQuery(this).data('active');
    var data = {
        'action': 'get_favorites',
        'item': fav_num,
        'active': fav_active,
        'security': ''
    };
    jQuery.post( HOME + '/procesos/generales/favorites.php', data, function( data ) {

        console.log( data );

        var result = data;
        if( result['user'] > 0 ){
            fav.data('active',result['active']);
            fav.attr('data-active',result['active']);
            fav.removeClass('active');
            fav.addClass('favoritos_delete');
            fav.attr('src', HOME+"/recursos/img/BUSQUEDA/SVG/iconos/Favorito.svg");
            if( result['active'] ){
                fav.addClass('active');
            }
        }else{
            jQuery('#popup-iniciar-sesion').modal('show');
        }
    });
});
*/