jQuery("#commentform").submit(function(e){
    if( jQuery("#g-recaptcha-response").val() == "" ){
        event.preventDefault();
        alert( "Debes validar el CAPTCHA para continuar." );
        return false;
    }

    var result = getAjaxData('/procesos/generales/comment.php','post', jQuery(this).serialize());
    
    result = jQuery.parseJSON(result);

    if(result['result']=='success'){
        jQuery('.BoxComment').fadeOut();
        GetComments();
    }else if(result['result']=='error'){
        alert(result['message']);
    }


});

function GetComments(){
    var data = getAjaxData('/procesos/cuidador/comentarios.php','post', {servicio: SERVICIO_ID});
    comentarios_cuidador = jQuery.parseJSON(data);
    comentarios();
}

