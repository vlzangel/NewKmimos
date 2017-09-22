
jQuery("#commentform").submit(function(e){
    if( jQuery("#g-recaptcha-response").val() == "" ){
        event.preventDefault();
        alert( "Debes validar el CAPTCHA para continuar." );
        return false;
    }


});
