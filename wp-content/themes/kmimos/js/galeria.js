jQuery( document ).ready(function() {

    jQuery(".mascotas_delete").on("click", function(e){
        var usu = jQuery( this ).attr("data-usu");
        var img = jQuery( this ).attr("data-img");
        if(!confirm("Esta seguro de eliminar la foto.?") ) {
            return false;
        } else {
           	
		   	jQuery.post(
		   		URL_PROCESOS_PERFIL, 
		   		{
		   			accion: "delete_foto",
                    tmp_user_id: usu,
		   			img: img
		   		},
		   		function(data){
			   		location.reload();
			   	}
		   	);

            return false;
        }  
    });

    jQuery("#btn_actualizar").on("click", function(e){
        location.href = URL_NUEVA_IMG;
    });

    jQuery("#btn_actualizar").attr("type", "button");

    if( data['status'] ) == "OK"){

                $mensaje = "Los datos de tu Mascota fueron actualizados";

            }else{
                 $mensaje = "Lo sentimos no se pudo actualizar los datos de su mascota";
            }

            jQuery('.mensaje').html($mensaje):
            setTimeout(function() {  

                 jQuery('.mensaje').remove(); 

                    if( data['status'] ) == "OK"){
                        location.href ="../../";
                    }
            },3000);

        }

});