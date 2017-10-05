jQuery( document ).ready(function() {
	
	jQuery("#rc_nombres").on("keyup", function(e){
		var valor = jQuery("#rc_nombres").val();
		
	});

});

jQuery(document).on("click", '[data-target="#popup-registro-cuidador1"]' ,function(e){
	e.preventDefault();

	jQuery("#vlz_form_nuevo_cuidador input").val('');

	jQuery(".popup-registro-exitoso").hide();
	jQuery(".popup-registro-cuidador-paso1").hide();
	jQuery(".popup-registro-cuidador-paso2").hide();
	jQuery(".popup-registro-cuidador-paso3").hide();
	jQuery(".popup-registro-cuidador-correo").hide();
	jQuery(".popup-registro-exitoso-final").hide();

	jQuery(".popup-registro-cuidador").fadeIn("fast");

	jQuery( jQuery(this).data('target') ).modal('show');
});

jQuery(document).on("click", '.popup-registro-cuidador .km-btn-popup-registro-cuidador', function ( e ) {
	e.preventDefault();

	jQuery(".popup-registro-cuidador").hide();
	jQuery(".popup-registro-cuidador-correo").fadeIn("fast");
});
 
jQuery("#cr_minus").on('click', function(e){
	e.preventDefault();
	var el = jQuery(this);
	if( !el.hasClass('disabled') ){
		var div = el.parent();
		var span = jQuery(".km-number", div);
		var input = jQuery("input", div);
		if ( span.html() > 0 ) {
			jQuery("#cr_plus").removeClass('disabled');
			var valor = parseInt(span.html()) - 1;
			span.html( valor );
			input.val( valor );
		}

		if ( span.html() <= 1 ) {
			el.addClass("disabled");			
		}
	}
});

jQuery("#cr_plus").on('click', function(e){
	e.preventDefault();
	var el = jQuery(this);

	if( !el.hasClass('disabled') ){
		var div = el.parent();
		var span = jQuery(".km-number", div);
		var input = jQuery("input", div);
		if ( span.html() >= 0 ) {
			jQuery("#cr_minus").removeClass('disabled');
			var valor = parseInt(span.html()) + 1;
			span.html( valor );
			input.val( valor );
		}

		if ( span.html() >= 6) {
			el.addClass("disabled");
			span.html( 6 );
			input.val( 6 );	
		}
	}
});

jQuery(document).on("click", '.popup-registro-cuidador-correo .km-btn-popup-registro-cuidador-correo', function ( e ) {
	e.preventDefault();		
	var a = HOME+"/procesos/cuidador/registro-paso1.php";
	var obj = jQuery(this);

	jQuery('input').css('border-bottom', '#ccc');
	jQuery('[data-error]').css('visibility', 'hidden');

	var list = ['rc_email','rc_nombres','rc_apellidos','rc_ife','rc_email','rc_clave','rc_telefono', 'rc_referred'];
	var valid = km_cuidador_validar(list);

	if( valid ){
		obj.html('Enviando datos');
		jQuery.post( a, jQuery('#vlz_form_nuevo_cuidador').serialize(), function( data ) {
			data = eval(data);
			if( data['error'] == "SI" ){				 
				if( data['fields'] != 'null' ){
					jQuery.each(data['fields'], function(id, val){
						mensaje( "rc_"+val['name'],val['msg']  );
					});
				}
				obj.html('SIGUIENTE');
			}else{
				jQuery('[data-target="name"]').html( jQuery('[name="rc_nombres"]').val() );
				jQuery(".popup-registro-cuidador-correo").hide();
				jQuery(".popup-registro-exitoso").fadeIn("fast");
			}
		});
	}
});

jQuery(document).on("click", '.popup-registro-exitoso .km-btn-popup-registro-exitoso', function ( e ) {
	e.preventDefault();

	jQuery(".popup-registro-exitoso").hide();
	jQuery(".popup-registro-cuidador-paso1").fadeIn("fast");
});

jQuery(document).on("click", '[data-step="1"]', function ( e ) {
	e.preventDefault();
	jQuery(".popup-registro-cuidador-paso3").hide();
	jQuery(".popup-registro-cuidador-paso2").hide();
	jQuery(".popup-registro-cuidador-paso1").fadeIn("fast");
});

jQuery(document).on("click", '[data-step="2"]', function ( e ) {
	e.preventDefault();
	jQuery(".popup-registro-cuidador-paso1").hide();
	jQuery(".popup-registro-cuidador-paso3").hide();
	jQuery(".popup-registro-cuidador-paso2").fadeIn("fast");
});

jQuery(document).on("click", '.popup-registro-cuidador-paso1 .km-btn-popup-registro-cuidador-paso1', function ( e ) {
	e.preventDefault();

	var list = ['rc_descripcion'];
	var valid = km_cuidador_validar(list);
	if( valid ){
		jQuery(".popup-registro-cuidador-paso1").hide();
		jQuery(".popup-registro-cuidador-paso2").fadeIn("fast");		
	}
});

jQuery(document).on("click", '.popup-registro-cuidador-paso2 .km-btn-popup-registro-cuidador-paso2', function ( e ) {
	e.preventDefault();
	var list = ['rc_estado', 'rc_municipio'];
	var valid = km_cuidador_validar(list);
	if( valid ){
		jQuery(".popup-registro-cuidador-paso2").hide();
		jQuery(".popup-registro-cuidador-paso3").fadeIn("fast");
	}
});

jQuery(document).on("click", '.popup-registro-cuidador-paso3 .km-btn-popup-registro-cuidador-paso3', function ( e ) {
	e.preventDefault();

	var a = HOME+"/procesos/cuidador/registro-paso2.php";
	var obj = jQuery(this);
		obj.html('Enviando datos');

	jQuery('input').css('border-bottom', '#ccc');
	jQuery('[data-error]').css('visibility', 'hidden');

	var list = ['rc_num_mascota'];
	var valid = km_cuidador_validar(list);

	if( valid ){
		jQuery.post( a, jQuery("#vlz_form_nuevo_cuidador").serialize(), function( data ) {
			data = eval(data);
			if( data['error'] == "SI" ){
				
				if( data['fields'].length > 0 ){
					jQuery.each(data['fields'], function(id, val){
						
						mensaje( val['name'],val['msg']  );
					});
				}
				obj.html('SIGUIENTE');
			}else{
				jQuery(".popup-registro-cuidador-paso3").hide();
				jQuery(".popup-registro-exitoso-final").fadeIn("fast");
			}
		});
	}
});

/*POPUP REGISTRO CUIDADOR*/
jQuery( document ).on('click', "[data-load='portada']", function(e){
	jQuery('#portada').click();
});

jQuery(document).on('change', 'select[name="rc_estado"]', function(e){
	var estado_id = jQuery(this).val();
	    
    if( estado_id != "" ){
        jQuery.getJSON( 
            HOME+"procesos/generales/municipios.php", 
            {estado: estado_id} 
        ).done(
            function( data, textStatus, jqXHR ) {
                var html = "<option value=''>Seleccione un municipio</option>";
                jQuery.each(data, function(i, val) {
                    html += "<option value="+val.id+">"+val.name+"</option>";
                });
                jQuery('[name="rc_municipio"]').html(html);
            }
        ).fail(
            function( jqXHR, textStatus, errorThrown ) {
                console.log( "Error: " +  errorThrown );
            }
        );
    }

});

jQuery(document).on('change', 'select[name="rc_municipio"]', function(e){
	var locale=jQuery(this).val();
	
});

/*km_cuidador_validar DATOS*/
jQuery( document ).on('keypress', '[data-clear]', function(e){
	mensaje( jQuery(this).attr('rc_name'), '', true );
});

function mensaje( label, msg='', reset=false ){
	var danger_color =  '#c71111';
	var border_color =  '#c71111';
	var visible = 'visible';
	if( reset ){
		danger_color = '#000';
		border_color = '#ccc';
		visible = 'hidden';
	}
	jQuery('[data-error="'+label+'"]').css('visibility', visible);
	/*jQuery('[data-error="'+label+'"]').css('color', danger_color);*/
	jQuery('[data-error="'+label+'"]').html(msg);
	jQuery( '[name="'+label+'"]' ).css('border-bottom', '1px solid ' + border_color);
	/*jQuery( '[name="'+label+'"]' ).css('color', danger_color);*/
}

function km_cuidador_validar( fields ){

	var status = true;
	if( fields.length > 0 ){
		jQuery.each( fields, function(id, val){
			var m = '';
			/*validar vacio*/
			if( jQuery('[name="'+val+'"]').val() == '' ){
				m = 'Este campo no puede estar vacio';
			}
			/*validar longitud*/
			if( m == ''){
				m = rc_validar_longitud( val );
			}

			if( m == ''){
				mensaje(val, m, true);
			}else{
				mensaje(val, m);
				status = false;
			}

		});
	}
	return status;
}

function validar_longitud( val, min, max, type, err_msg){
	result = '';
	var value = 0;
	switch( type ){
		case 'int':
			value = val;
			break;
		case 'string':
			value = val.length;
			break;
	}

	if( value < min || value > max ){
		result = err_msg;
	}
	return result;
}

function rc_validar_longitud( field ){
	var result = '';
	var val = jQuery('[name="'+field+'"]').val();
	switch( field ){
			case 'rc_email':  
				result = validar_longitud( val, 10, 100, 'string', 'Debe estar entre 10 y 100 caracteres');
				break;

			case 'rc_nombres':
				result = validar_longitud( val, 2, 100, 'string', 'Debe estar entre 2 y 100 caracteres');
				break;

			case 'rc_apellidos':
				result = validar_longitud( val, 2, 100, 'string', 'Debe estar entre 2 y 100 caracteres');
				break;

			case 'rc_ife':
				result = validar_longitud( val, 13, 13, 'string', 'Debe tener 13 digitos');
				break;

			case 'rc_clave':
				result = validar_longitud( val, 1, 200, 'string', 'Debe estar entre 1 y 200 caracteres');
				break;

			case 'rc_telefono':
				result = validar_longitud( val, 7, 15, 'string', 'Debe estar entre 7 y 15 caracteres');
				break;

			case 'rc_descripcion':
				result = validar_longitud( val, 1, 600, 'string', 'Debe estar entre 1 y 100 caracteres');
				break;

			case 'rc_direccion':
				result = validar_longitud( val, 1, 600, 'string', 'Debe estar entre 5 y 300 caracteres');
				break;
	};
	return result;
}

function vista_previa(evt) {
	
	jQuery("#perfil-img").attr("src", HOME+"images/cargando.gif" );
    jQuery(".kmimos_cargando").css("visibility", "visible");

  	var files = evt.target.files;
  	for (var i = 0, f; f = files[i]; i++) {  
       	if (!f.type.match("image.*")) {
            continue;
       	}
       	var reader = new FileReader();
       	reader.onload = (function(theFile) {
           return function(e) {

    			redimencionar(e.target.result, function(img_reducida){
    				var a = RAIZ+"imgs/vlz_subir_img.php";
    				var img_pre = jQuery("#vlz_img_perfil").val();
    				
    				 jQuery.ajax({
                      async:true, 
                      cache:false, 
                      type: 'POST',   
                      url: a,
                      data: {img: img_reducida, previa: img_pre}, 
                      success:  function(url){
			      		jQuery("#perfil-img").attr("src", RAIZ+"imgs/Temp/"+url);
	        			jQuery("#vlz_img_perfil").val( url );
		           		jQuery(".kmimos_cargando").css("visibility", "hidden");
                      },
                      beforeSend:function(){},
                      error:function(objXMLHttpRequest){}
                    });
    			});
           };
		})(f);
		reader.readAsDataURL(f);
   	}
}      
document.getElementById("portada").addEventListener("change", vista_previa, false);


