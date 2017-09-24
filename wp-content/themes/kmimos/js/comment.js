jQuery("#commentform").submit(function(e){
    if( jQuery("#g-recaptcha-response").val() == "" ){
        event.preventDefault();
        alert( "Debes validar el CAPTCHA para continuar." );
        return false;
    }

    var result = getAjaxData('/procesos/generales/comment.php','post', jQuery(this).serialize());
    jQuery('.BoxComment').fadeOut();
    GetComments();

    result = jQuery.parseJSON(result);
    //console.log(result);

    if(result['result']=='success'){

    }else if(result['result']=='error'){
        alert(result['message']);
    }


});

function GetComments(){
    var data = getAjaxData('/procesos/cuidador/comentarios.php','post', {servicio: SERVICIO_ID});
    //console.log(data);
    comentarios_cuidador = jQuery.parseJSON(data);
    comentarios();

}

