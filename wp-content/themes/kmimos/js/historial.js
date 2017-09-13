jQuery(document).ready(function(){
    jQuery(document).on('change','select.redirect', function(e){
        var value=jQuery(this).val();
        if(value!=''){

            if(jQuery(this).find('option[value="'+value+'"]').hasClass('action_confirmed')){

                var confirmed = confirm("Esta Seguro de cancelar esta reserva?");
                if (confirmed == true) {
                    window.location.href = value;
                }

            }else{
                if(jQuery(this).find('option[value="'+value+'"]').hasClass('modified')){

                    var data = jQuery(this).val();
                    
                    console.log(data);

                    jQuery.post(
                        URL_PROCESOS_PERFIL, 
                        {
                            accion: "update_reserva",
                            data: data
                        },
                        function(resp){
                            console.log(resp);
                            //location.href = RAIZ+DATA.url;
                        }, 
                        'json'
                    );

                }else{
                    window.location.href = value;
                }

            }
        }
    });

});