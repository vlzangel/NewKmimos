jQuery( document ).ready(function() {

    jQuery('#club_patitas').on('submit', function(e){
       e.preventDefault();

        var danger_color =  '#333';
        var border_color =  '#CCC';
       
        var error_danger_color =  '#c71111';
        var error_border_color =  '#c71111';

        var nombre_valid = true;
        var email_valid = true;

        if( jQuery('#cp_nombre').val() != '' ){
            jQuery('[data-error="cp_nombre"]').css('color', danger_color);
            jQuery('[data-error="cp_nombre"]').html("Este campo es requerido");
            jQuery('[name="cp_nombre"]').css('border-bottom', '1px solid ' + border_color);
            jQuery('[name="cp_nombre"]').css('color', danger_color);
        }else{
            nombre_valid = true;
            jQuery('[data-error="cp_nombre"]').css('color', error_danger_color);
            jQuery('[data-error="cp_nombre"]').html("Este campo es requerido");
            jQuery('[name="cp_nombre"]').css('border-bottom', '1px solid ' + error_border_color);
            jQuery('[name="cp_nombre"]').css('color', error_danger_color);
        }

        var email = jQuery('#cp_email').val();
        if( email != '' && /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(email) ){
            jQuery('[data-error="cp_email"]').css('color', danger_color);
            jQuery('[data-error="cp_email"]').html("Este campo es requerido");
            jQuery('[name="cp_email"]').css('border-bottom', '1px solid ' + border_color);
            jQuery('[name="cp_email"]').css('color', danger_color);
        }else{
            email_valid = false;
            jQuery('[data-error="cp_email"]').css('color', error_danger_color);
            jQuery('[data-error="cp_email"]').html("Este campo es requerido");
            jQuery('[name="cp_email"]').css('border-bottom', '1px solid ' + error_border_color);
            jQuery('[name="cp_email"]').css('color', error_danger_color);
        }

        if( nombre_valid && email_valid ){

            jQuery('#msg').html('Enviando solicitud.');
            jQuery('#cp_loading').removeClass('hidden');
            jQuery('#cp_loading').fadeIn(1500);

            var urls = RAIZ+"/landing/list-subscriber.php?source=kmimos-mx-clientes-referidos&email="+jQuery('#cp_email').val();
            jQuery.get( urls, function(e){});

            var urluser = RAIZ+"landing/registro-usuario.php?email="+jQuery('#cp_email').val()+"&name="+jQuery('#cp_nombre').val()+"&referencia=kmimos-home";
            jQuery.get( urluser, function(e){

                var redirect = RAIZ+"/referidos/compartir/?e="+jQuery('#cp_email').val();
                switch (jQuery.trim(e)){
                    case '0':
                        jQuery('#msg').html('¡No pudimos completar su solicitud!');
                        break;
                    case '1':
                        jQuery('#msg').html('¡Felicidades, ya formas parte de nuestro Club!');
                        jQuery('a[data-redirect="patitas-felices"]').attr('href', redirect);
                        jQuery('a[data-redirect="patitas-felices"]').click();
                        window.open( redirect, '_blank' );
                        break;
                    case '2':
                        jQuery('#msg').html('¡Ya formas parte de nuestro Club!');
                        jQuery('a[data-redirect="patitas-felices"]').attr('href', redirect);
                        jQuery('a[data-redirect="patitas-felices"]').click();
                        window.open( redirect, '_blank' );
                        break;
                    default:
                        jQuery('#msg').html('Registro: No pudimos completar su solicitud, intente nuevamente');
                        jQuery('#cp_loading').addClass('hidden');
                        break;
                }
                setTimeout(function() {
                    jQuery('#cp_loading').fadeOut(1500);
                },3000);
            })
            .fail(function() {
                jQuery('#msg').html('Registro: No pudimos completar su solicitud, intente nuevamente');
                jQuery('#cp_loading').addClass('hidden');
            });

        }

    });

});