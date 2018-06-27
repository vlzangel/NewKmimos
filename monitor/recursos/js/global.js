
jQuery(document).ready(function() {

	jQuery('#btn-grafico').on('click', function(){
		jQuery('#grafico-container').toggle();
		if( jQuery('#grafico-container').css('display') == 'none' ){
			jQuery('.grafico-icon').removeClass('fa-eye-slash');
			jQuery('.grafico-icon').addClass('fa-eye');
		}else{
			jQuery('.grafico-icon').removeClass('fa-eye');
			jQuery('.grafico-icon').addClass('fa-eye-slash');
		}
	});
	
	jQuery('#btn-tabla').on('click', function(){
		jQuery('#tabla-container').toggle();
		if( jQuery('#tabla-container').css('display') == 'none' ){
			jQuery('.tabla-icon').removeClass('fa-eye-slash');
			jQuery('.tabla-icon').addClass('fa-eye');
		}else{
			jQuery('.tabla-icon').removeClass('fa-eye');
			jQuery('.tabla-icon').addClass('fa-eye-slash');
		}
	});


    // Selector de Sucursales
	jQuery('[data-action]').on('click', function(){
		console.log( jQuery(this).data('action') );
		jQuery('#tipo_datos').html( jQuery(this).data('label') );
		sucursal = jQuery(this).data('action') ;
        cargarDatos();
    });

    // Selector de Periodo ( Dia, Mes, Año )
    jQuery(document).on('click','.option-select', function(){
        jQuery('.option-select').removeClass('activo');
        jQuery(this).addClass('activo');
        periodo = jQuery(this).attr('data-value');
		cargarDatos();
    });

    // Enviar datos
    jQuery('#frm-search').on('submit', function(e){
        e.preventDefault();
        cargarDatos();
    });

});

function mostrarLoading(){
	jQuery('#load-progress').addClass('hidden');
    jQuery("#load-detalle").html('');

    jQuery.each(plataformas, function( index, row ){
        var filtro = sucursal.split('.');
        if( filtro.length > 0 ){
            var sts = 0;
            switch( filtro[0] ){
                case 'bygroup':
                    if( filtro[1] == row.grupo ){
                        sts = 1;
                    }
                    break;
                case 'byname':
                    if( filtro[1] == row.name ){
                        sts = 1;
                    }
                    break;
                default:
                    sts = 1;
                    break;
            }
            if( sts == 1 ){
                lista_sucursales.push( row );
                // mostrar datos html
                jQuery("#load-detalle").append('<span><i id="'+row.name+'" class="fa fa-cog fa-spin fa-fw"></i> '+row.descripcion+'</span>');
            }
        }
    });

    if(lista_sucursales.length>0){
    	jQuery('#load-progress').removeClass('hidden');
    }
}

function mostratMensaje( mensaje ){
    jQuery('#load-notification h4').remove();
    if( mensaje != '' ){
        jQuery('#load-notification').prepend( '<h4>'+mensaje+'</h4>' );
    }
}

function number_format(amount, decimals, separador_decimal=",", separador_miles="." ) {

    amount += ''; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\-\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

    decimals = decimals || 0; // por si la variable no fue fue pasada

    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0) 
        return parseFloat(0).toFixed(decimals);

    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = '' + amount.toFixed(decimals);

    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;

    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + separador_miles + '$2');

    return amount_parts.join(separador_decimal);
}

function meses( mes ){
    var meses = ['--', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    return meses[ eval(mes) ];
}
 
function unique( datos ){
    var duplicados = datos.filter(function(elem, pos) {
       return datos.indexOf(elem) == pos;
    });
    return duplicados;
}