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