jQuery(document).ready(function() {

    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });

    jQuery("#actualizar_kmimos").on("click", function(){
    	var btn = jQuery(this);
    	btn.html( '<i class="fa fa-refresh fa-spin fa-fw"></i> Guardando... ' );
    	jQuery('#kmimos_mensaje').remove();
    	jQuery.post( TEMA+'/admin/backend/facturas_configuracion/ajax/update_kmimos.php', 
        jQuery('#form-kmimos').serialize(),
        function(data){
            data = JSON.parse(data);
            if( data['estatus'] == "listo" ){
            	btn.after('<label id="kmimos_mensaje" style="margin: 0px 20px;" >Datos Guardados</label>');
            }
        	btn.html( '<i class="fa fa-save"></i> Guardar' );
        })
    });

    jQuery("#actualizar_servicios").on("click", function(){
        var btn = jQuery(this);
        btn.html( '<i class="fa fa-refresh fa-spin fa-fw"></i> Guardando... ' );
        jQuery('#kmimos_mensaje').remove();
        jQuery.post( TEMA+'/admin/backend/facturas_configuracion/ajax/update_servicios.php', 
        jQuery('#form-servicios').serialize(),
        function(data){
            data = JSON.parse(data);
            if( data['solicitudes'] == data['correctos'] ){
                btn.after('<label id="kmimos_mensaje" style="margin: 0px 20px;" > Datos guardados</label>');
            }else{
                btn.after('<label id="kmimos_mensaje" style="margin: 0px 20px;" > '+data['correctos']+' Registros guardados - '+data['incorrectos']+' Errores detetados <br>'+data['errores']+' </label>');
            }
            btn.html( '<i class="fa fa-save"></i> Guardar' );
        })
    });

	jQuery.post( TEMA+'/admin/backend/facturas_configuracion/ajax/get_configuracion.php', {},
    function(data){
        data = JSON.parse(data);
        console.log(data);
    });



});

 
function abrir_link(e){
	init_modal({
		"titulo": e.attr("data-titulo"),
		"modulo": "facturas",
		"modal": e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id")
		}
	});
}

 








 