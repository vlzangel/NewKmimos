jQuery("#commentform").submit(function(e){
    if( jQuery("#g-recaptcha-response").val() == "" ){
        event.preventDefault();
        alert( "Debes validar el CAPTCHA para continuar." );
        return false;
    }

    if( !jQuery("#submit").hasClass("disable") ){
        jQuery("#submit").addClass("disable");

        var result = getAjaxData('/procesos/generales/comment.php','post', jQuery(this).serialize());
        
        result = jQuery.parseJSON(result);

        if(result['result']=='success'){
            jQuery("#comment").val("");
            jQuery("#author").val("");
            jQuery("#email").val("");
            jQuery("#submit").removeClass("disable");

            jQuery('.comments').css("display", "none");
            jQuery('.modal_comentario_enviado').slideDown();

            setTimeout(function() {
                jQuery('.BoxComment').fadeOut();
                GetComments();
            } ,3000 ); 

        }else if(result['result']=='error'){
            alert(result['message']);
        }
    }

});

function GetComments(){
    var data = getAjaxData('/procesos/cuidador/comentarios.php','post', {servicio: SERVICIO_ID});
    comentarios_cuidador = jQuery.parseJSON(data);
    comentarios();
}

