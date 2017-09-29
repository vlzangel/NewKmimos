jQuery(document).on("click", '[data-target="#popup-conoce-cuidador"]' ,function(e){
    jQuery( '#modal-name-cuidador' ).html( jQuery(this).data('name') );
    jQuery( '[name="post_id"]' ).html( jQuery(this).data('id') );
    jQuery( jQuery(this).data('target') ).modal('show');

});

jQuery(document).on("click", '[data-id="enviar_datos"]' ,function(e){
    e.preventDefault();
    var a = HOME+"procesos/cuidador/conocer-cuidador.php";
    jQuery(this).html('<i style="font-size: initial;" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i> ENVIANDO DATOS...');
    jQuery.post( a, jQuery("#conoce_cuidador").serialize(), function( data ) {
        console.log(data);
        if( data > 0 ){
            jQuery('#popup-conoce-cuidador').modal('show');
        }
        jQuery(this).html('ENVIAR SOLICITUD');
    });
});

jQuery("#meeting_when").change(function(){
    var dt = new Date(jQuery(this).val());
    dt.setDate( parseInt(dt.getDate()) + 1);
    var r = dt.toISOString().split('T');
    jQuery("#service_start").attr("min", r[0]);

});
jQuery("#service_start").change(function(){
    jQuery("#service_end").attr("min",jQuery(this).val());
});


jQuery.noConflict();
jQuery(document).ready(function() {
    jQuery("#meeting_when").change(function(){
        var dt = new Date(jQuery(this).val());
        dt.setDate( parseInt(dt.getDate()) + 1);
        var r = dt.toISOString().split('T');
        jQuery("#service_start").attr("min", r[0]);
    });
    jQuery("#service_start").change(function(){
        jQuery("#service_end").attr("min",jQuery(this).val());
    });
/*    jQuery('#request_form').validate({ 
        rules: {
            meeting_when: {
                required: true,
                date: true,
            },
            meeting_where: {
                required: true,
                minlength: 5,
            },
            type_service: {
                required: true,
            },
            'pet_ids[]': {
                required: true,
                minlength: 1,
            },
            service_start: {
                required: true,
                date: true,
            },
            service_end: {
                required: true,
            },
        },  
        messages:{
            meeting_when:{
               minlength: "La fecha no puede ser menor a {0}",
               required:"Este campo es requido"
            },
            meeting_where:{
               minlength:"Debe ingresar como mínimo {0} carácteres",
               required:"Este campo es requido" 
            },
            'pet_ids[]': {
                required: "Este campo es requido",
            },
            service_start:{
               min: "La fecha no puede ser menor a {0}",
               required:"Este campo es requido"
            },
            service_end:{
               min: "La fecha no puede ser menor a {0}",
               required:"Este campo es requido"
            },
        }
    });*/
});