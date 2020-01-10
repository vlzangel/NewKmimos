function kv_validar(step){
    var errores = [];
    jQuery("#step_"+step+" .validar").each(function(i, v){
        var valid = jQuery(this).attr('valid');
        if( valid != undefined ){
            var validaciones = valid.split("|");
            for (var i = 0; i < validaciones.length; i++) {
                switch( validaciones[ i ] ){
                    case 'required':
                        var parent = jQuery(this).parent();
                        parent.find('.kv_error').remove();
                        if( String(jQuery(this).val()).trim() == '' ){
                            errores.push( jQuery(this).attr('name') );
                            parent.append('<span class="kv_error">El campo es requerido</span>');
                            i = validaciones.length;
                        }
                    break;
                    case 'checked':
                        var parent = jQuery(this).parent();
                        parent.find('.kv_error').remove();
                        if( !jQuery(this).prop('checked') ){
                            errores.push( jQuery(this).attr('name') );
                            parent.append('<span class="kv_error">Debes marcar esta casilla</span>');
                            i = validaciones.length;
                        }
                    break;
                    case 'email':
                        var parent = jQuery(this).parent();
                        parent.find('.kv_error').remove();
                        if( !/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test( String(jQuery(this).val()).trim() ) ){
                            errores.push( jQuery(this).attr('name') );
                            parent.append('<span class="kv_error">El formato del correo es incorrecto</span>');
                        }
                    break;
                }
            }
        }
    });
    return errores;
}