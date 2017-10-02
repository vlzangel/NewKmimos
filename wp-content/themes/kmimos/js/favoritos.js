jQuery( document ).ready(function() {

    jQuery(".favoritos_delete").on("click", function(e){
        var cuidador_id = jQuery( this ).attr("data-fav");
        var user_id = jQuery( "#user_id" ).val();
        var data_conf = jQuery( this ).attr('data-confirm');
        var confirmar = true;

	   	jQuery.post(
	   		URL_PROCESOS_PERFIL, 
	   		{
	   			accion: "delete_favorito",
	   			cuidador_id: cuidador_id,
                user_id: user_id
	   		},
	   		function(data){
		   		console.log(data);
                location.reload();
		   	}
	   	);

    });

});

// jQuery(document).on('click','[data-favorito="true"]',function(){

//     var cuidador_id = jQuery( this ).attr("data-fav");
//     var user_id = jQuery( "#user_id" ).val();
//     // if(!confirm("Esta seguro de quitar este cuidador de la lista.?") ) {
//     //     return false;
//     // } else {    
//         jQuery.post(
//             URL_PROCESOS_PERFIL, 
//             {
//                 accion: "delete_favorito",
//                 cuidador_id: cuidador_id,
//                 user_id: user_id
//             },
//             function(data){
//                 // location.reload();
//             }
//         );

//         return false;
//     // }  

// });

jQuery(document).on('click','[data-favorito="false"]',function(){
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
        var result = data;
        if( result['user'] > 0 ){
            fav.data('active',result['active']);
            fav.attr('data-active',result['active']);
            fav.removeClass('active');
            fav.addClass('favoritos_delete');
            if( result['active'] ){
                fav.addClass('active');
            }
        }else{
            jQuery('#popup-iniciar-sesion').modal('show');
        }
    });

});
