jQuery( document ).ready(function() {

    jQuery(".mascotas_delete").on("click", function(e){
        var pet_id = jQuery( this ).attr("data-img");
        if(!confirm("Esta seguro de eliminar la mascota.?") ) {
            return false;
        } else {
           	
		   	jQuery.post(
		   		URL_PROCESOS_PERFIL, 
		   		{
		   			accion: "delete_mascotas",
		   			pet_id: pet_id
		   		},
		   		function(data){
			   		location.reload();
			   	}
		   	);

            return false;
        }  
    });
        var $mensaje="";

               if( data.status == "OK"){


                $mensaje = "Los datos de fueron actualizados";

            }else{
                 $mensaje = "Lo sentimos no se pudo actualizar los datos";
            }


            jQuery('#btn_actualizar').before('<span class="mensaje">'+$mensaje+'</span>');  
                         if( data.status == "OK"){
                    location.href = URL_NUEVA_IMG;
}

                  setTimeout(function() { 
                 jQuery('.mensaje').remove(); 
              

            },3000);
    jQuery("#btn_actualizar").on("click", function(e){
        
    });

    jQuery("#btn_actualizar").attr("type", "button");

});