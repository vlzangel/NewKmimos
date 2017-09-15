// $(document).on("click", '[data-target="#popup-registro-cuidador1"]' ,function(e){
// 	e.preventDefault();
// });
$(document).on("click", '[data-target="#popup-registro-cuidador1"]' ,function(e){
	e.preventDefault();

	$("#vlz_form_nuevo_cuidador input").val('');

	$(".popup-registro-exitoso").hide();
	$(".popup-registro-cuidador-paso1").hide();
	$(".popup-registro-cuidador-paso2").hide();
	$(".popup-registro-cuidador-paso3").hide();
	$(".popup-registro-cuidador-correo").hide();
	$(".popup-registro-exitoso-final").hide();

	$(".popup-registro-cuidador").fadeIn("fast");
	$( $(this).data('target') ).modal('show');
});

$(document).on("click", '.popup-registro-cuidador .km-btn-popup-registro-cuidador', function ( e ) {
	e.preventDefault();

	$(".popup-registro-cuidador").hide();
	$(".popup-registro-cuidador-correo").fadeIn("fast");
});



	$(document).on("click", '[data-id="km-minus-ext"]', function ( e ) {
		console.log('asd');
		operacionAlg( $(this), "-" );
	});
	$(document).on("click", '[data-id="km-plus-ext"]', function ( e ) {
		console.log('asd');
		operacionAlg( $(this), "+" );
	});

function operacionAlg( _this, operator, cant = 1  ){
	var el = _this;
	var div = el.parent();
	var span = $(".km-number", div);
	var input = $("input", div);
	var valor = 0;
	if ( span.html() > 0 ) {	
		if( operator == '+' ){
			valor = parseInt(span.html()) + cant;
		}else{
			valor = parseInt(span.html()) - cant;
		}
	}
	span.html( valor );
	input.val( valor );
	

	console.log(input.val());

	if ( span.html() <= 0 ) {
		el.addClass("disabled");
	}
}	


$("#cr_minus").on('click', function(){
	e.preventDefault();
	var el = $(this);
	var div = el.parent();
	var span = $(".km-number", div);
	var input = $("input", div);
	if ( span.html() > 0 ) {
		var valor = parseInt(span.html()) - 1;
		span.html( valor );
		input.val( valor );
	}

	if ( span.html() <= 0 ) {
		el.addClass("disabled");
	}
});

$("#cr_plus").on('click', function(){
	e.preventDefault();
	var el = $(this);
	var div = el.parent();
	var span = $(".km-number", div);
	var input = $("input", div);
	if ( span.html() > 0 ) {
		var valor = parseInt(span.html()) - 1;
		span.html( valor );
		input.val( valor );
	}

	if ( span.html() <= 0 ) {
		el.addClass("disabled");
	}
});

function convertData_registrocuidador(){
		return {
			'email': $('[name="rc_email"]').val(),
			'nombres': $('[name="rc_nombres"]').val(),
			'apellidos': $('[name="rc_apellidos"]').val(),
			'ife': $('[name="rc_ife"]').val(),
			'clave': $('[name="rc_clave"]').val(),
			'telefono': $('[name="telefono"]').val(),	
			'longitude': $('[name="longitude"]').val(),
			'latitude': $('[name="latitude"]').val(),
			'portada': $('[name="rc_portada"]').val(),
			'vlz_img_perfil': $('[name="rc_vlz_img_perfil"]').val(),
			'descripcion': $('[name="rc_descripcion"]').val(),
			'estado': $('[name="rc_estado"]').val(),
			'municipio': $('[name="rc_municipio"]').val(),
			'municipio': $('[name="rc_municipio"]').val(),
			'direccion': $('[name="rc_direccion"]').val(),
			'num_mascota': $('[name="rc_num_mascota"]').val()
		};
}

$(document).on("click", '.popup-registro-cuidador-correo .km-btn-popup-registro-cuidador-correo', function ( e ) {
	e.preventDefault();		
	var a = HOME+"/procesos/cuidador/registro-paso1.php";
	var obj = $(this);
		obj.html('Enviando datos');

	$('input').css('border-bottom', '#ccc');
	$('[data-error]').css('visibility', 'hidden');

	var list = ['rc_email','rc_nombres','rc_apellidos','rc_ife','rc_email','rc_clave','rc_telefono'];
	var valid = km_cuidador_validar(list);
	// jQuery.post( a, jQuery("#vlz_form_nuevo_cuidador").serialize(), function( data ) {

	if( valid ){
		var d = convertData_registrocuidador();
		jQuery.post( a, d, function( data ) {
			data = eval(data);
			if( data['error'] == "SI" ){
				console.log(data['fields'].size );
				if( data['fields'].length > 0 ){
					$.each(data['fields'], function(id, val){
						mensaje( val['name'],val['msg']  );
					});
				}
				obj.html('SIGUENTE');
			}else{
				$('[data-target="name"]').html( $('[name="rc_nombres"]').val() );
				$(".popup-registro-cuidador-correo").hide();
				$(".popup-registro-exitoso").fadeIn("fast");
			}
		});
	}
});

$(document).on("click", '.popup-registro-exitoso .km-btn-popup-registro-exitoso', function ( e ) {
	e.preventDefault();

	$(".popup-registro-exitoso").hide();
	$(".popup-registro-cuidador-paso1").fadeIn("fast");
});

$(document).on("click", '[data-step="1"]', function ( e ) {
	e.preventDefault();

	var list = ['rc_descripcion'];
	var valid = km_cuidador_validar(list);
	if( valid ){
		$(".popup-registro-cuidador-paso1").hide();
		$(".popup-registro-cuidador-paso2").fadeIn("fast");		
	}
});
$(document).on("click", '.popup-registro-cuidador-paso1 .km-btn-popup-registro-cuidador-paso1', function ( e ) {
	e.preventDefault();

	var list = ['rc_descripcion'];
	var valid = km_cuidador_validar(list);
	if( valid ){
		$(".popup-registro-cuidador-paso1").hide();
		$(".popup-registro-cuidador-paso2").fadeIn("fast");		
	}
});

$(document).on("click", '[data-step="2"]', function ( e ) {
	e.preventDefault();
	var list = ['rc_estado', 'rc_municipio', 'rc_direccion'];
	var valid = km_cuidador_validar(list);
	if( valid ){
		$(".popup-registro-cuidador-paso2").hide();
		$(".popup-registro-cuidador-paso3").fadeIn("fast");
	}
});
$(document).on("click", '.popup-registro-cuidador-paso2 .km-btn-popup-registro-cuidador-paso2', function ( e ) {
	e.preventDefault();
	var list = ['rc_estado', 'rc_municipio', 'rc_direccion'];
	var valid = km_cuidador_validar(list);
	if( valid ){
		$(".popup-registro-cuidador-paso2").hide();
		$(".popup-registro-cuidador-paso3").fadeIn("fast");
	}
});

$(document).on("click", '.popup-registro-cuidador-paso3 .km-btn-popup-registro-cuidador-paso3', function ( e ) {
	e.preventDefault();

	var a = HOME+"/procesos/cuidador/registro-paso2.php";
	var obj = $(this);
		obj.html('Enviando datos');

	$('input').css('border-bottom', '#ccc');
	$('[data-error]').css('visibility', 'hidden');

	var list = ['rc_num_mascota'];
	var valid = km_cuidador_validar(list);

	if( valid ){
		jQuery.post( a, jQuery("#vlz_form_nuevo_cuidador").serialize(), function( data ) {
			data = eval(data);
			if( data['error'] == "SI" ){
				console.log(data['fields'].size );
				if( data['fields'].length > 0 ){
					$.each(data['fields'], function(id, val){
						console.log(val);
						mensaje( val['name'],val['msg']  );
					});
				}
				obj.html('SIGUENTE');
			}else{
				$(".popup-registro-cuidador-paso3").hide();
				$(".popup-registro-exitoso-final").fadeIn("fast");
			}
		});
	}


});
// POPUP REGISTRO CUIDADOR
jQuery( document ).on('click', "[data-load='portada']", function(e){
	$('#portada').click();
});


jQuery(document).on('change', 'select[name="rc_estado"]', function(e){
	var state=jQuery(this).val();
	var latitude=Coordsearch['state'][state]['lat'];
	var longitude=Coordsearch['state'][state]['lng'];

	if(latitude!='' && longitude!=''){
		lat=latitude;
		lng=longitude;
		$('[name="latitude"]').val( lat );
		$('[name="longitude"]').val( lng );
	}

	// Cargar Municipios
    if( state != "" ){
        var html = "<option value=''>Seleccione un municipio</option>";
        jQuery.each(estados_municipios[state]['municipios'], function(i, val) {
            html += "<option value="+val.id+" data-id='"+i+"'>"+val.nombre+"</option>";
        });
        jQuery('[name="rc_municipio"]').html(html);
    }

});

jQuery(document).on('change', 'select[name="rc_municipio"]', function(e){
	var locale=jQuery(this).val();
	var latitude=Coordsearch['locale'][locale]['lat'];
	var longitude=Coordsearch['locale'][locale]['lng'];

	if(latitude!='' && longitude!=''){
		lat=latitude;
		lng=longitude;
		$('[name="latitude"]').val( lat );
		$('[name="longitude"]').val( lng );
	}
});


// km_cuidador_validar DATOS
jQuery( document ).on('keypress', '[data-clear]', function(e){
	mensaje( $(this).attr('rc_name'), '', true );
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
	$('[data-error="'+label+'"]').css('visibility', visible);
	$('[data-error="'+label+'"]').css('color', danger_color);
	$('[data-error="'+label+'"]').html(msg);
	$( '[name="'+label+'"]' ).css('border-bottom', '1px solid ' + border_color);
	$( '[name="'+label+'"]' ).css('color', danger_color);
}
function km_cuidador_validar( fields ){
	var status = true;
	if( fields.length > 0 ){
		$.each( fields, function(id, val){
			if($('[name="'+val+'"]').val() == ''){
				mensaje(val, 'Este campo no puede estar vacio');
				status = false;
			}else{
				mensaje(val, '', true);
			}
		});
	}
	return status;
}


function vista_previa(evt) {
  	var files = evt.target.files;
  	for (var i = 0, f; f = files[i]; i++) {  
       	if (!f.type.match("image.*")) {
            continue;
       	}
       	var reader = new FileReader();
       	reader.onload = (function(theFile) {
           return function(e) {
           		jQuery(".kmimos_cargando").css("display", "block");
    			redimencionar(e.target.result, function(img_reducida){
    				var a = RAIZ+"imgs/vlz_subir_img.php";
    				var img_pre = jQuery("#vlz_img_perfil").val();
    				jQuery.post( a, {img: img_reducida, previa: img_pre}, function( url ){

			      		jQuery("#perfil-img").attr("src", RAIZ+"imgs/Temp/"+url);
	        			jQuery("#vlz_img_perfil").val( url );

	           			// jQuery(".kmimos_cargando").css("display", "none");
			      	});
    			});
           };
       })(f);
       reader.readAsDataURL(f);
   	}
}      
document.getElementById("portada").addEventListener("change", vista_previa, false);


