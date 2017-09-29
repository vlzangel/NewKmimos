jQuery( document ).ready(function() {

    jQuery(".favoritos_delete").on("click", function(e){
        var cuidador_id = jQuery( this ).attr("data-fav");
        var user_id = jQuery( "#user_id" ).val();
        if(!confirm("Esta seguro de quitar este cuidador de la lista.?") ) {
            return false;
        } else {
           	
		   	jQuery.post(
		   		URL_PROCESOS_PERFIL, 
		   		{
		   			accion: "delete_favorito",
		   			cuidador_id: cuidador_id,
                    user_id: user_id
		   		},
		   		function(data){
			   		location.reload();
			   	}
		   	);

            return false;
        }  
    });

});


jQuery(document).on('click','.km-link-favorito',function(){
    var fav = jQuery(this);
    var fav_num = jQuery(this).data('num');
    var fav_active = jQuery(this).data('active');

    var data = {
        'action': 'get_favorites',
        'item': fav_num,
        'active': fav_active,
        'security': ''
    };

    var result = getAjaxData('/procesos/generales/favorites.php','post', data);
        result = jQuery.parseJSON(result);

    fav.data('active',result['active']);
    fav.attr('data-active',result['active']);
    fav.removeClass('active');
    if( result['active'] ){
        fav.addClass('active');
    }
/*    fav.addClass(result['active']);*/
    console.log(result);
});
