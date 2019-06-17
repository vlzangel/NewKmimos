function removeFoto(_this){
	var index = _this.data("index");
	var new_array = [];
	jQuery.each(fotos_array, function(i, d){
		if(d[0] != index){
			new_array.push( d );
			jQuery("#foto_"+index).remove();
		}
	});

	fotos_array = new_array;
}

function subir_fotos(){
	jQuery.post(
		HOME+"/procesos/cuidador/subir_fotos.php",
		{
			"email": jQuery('[name="rc_email"]').val(),
			"fotos": fotos_array
		},
		function(data){
			console.log(data);
			console.log("Imágenes subidas");
		}, 'json'
	);	
}

var fotos_array = []; var indices = 0;
jQuery( document ).ready(function() {
	
	jQuery("#fotos").on("change", function(e){

		console.log( "Inicio: "+fotos_array.length );

		if( fotos_array.length < 6 ){
			jQuery.each(e.target.files, function(i, d){
				console.log(d);
				getRealMime(d).then(function(MIME){
			        if( MIME.match("image.*") ){

			            var reader = new FileReader();
			            reader.onload = (function(theFile) {
			                return function(e) {
			                    redimencionar(e.target.result, function(img_reducida){

			                    	if( fotos_array.length < 6 ){
			                    		console.log( "Entro: "+fotos_array.length );
			                    		var HTML = '<div id="foto_'+indices+'" style="background-image: url('+img_reducida+');"> <i class="fa fa-times" onclick="removeFoto(jQuery(this))" data-index="'+indices+'" ></i> </div>';
			                    		jQuery(".galeria_container").append( HTML );
			                    		fotos_array.push( [indices, img_reducida] );
			                    		indices++;
			                    	}

			                    	jQuery(".galeria_container").css("display", "block");
			                    });      
			                };
			           })(d);
			           reader.readAsDataURL(d);
			        }else{
			            alert("Solo se permiten imagenes");
			        }
			    }).catch(function(error){
			        alert("Solo se permiten imagenes");
			    });  	
			});
		}
	});

	var maxDatePets = new Date();
	jQuery('#fecha').datepick({
		dateFormat: 'dd/mm/yyyy',
		maxDate: maxDatePets,
		onSelect: function(xdate) {
			if( jQuery('#datepets').val() != '' ){
			}
		},
		yearRange: '1940:'+maxDatePets.getFullYear(),
	});

	jQuery.post(
        HOME+"/procesos/busqueda/ubicacion.php",
        {},
        function(data){
            jQuery("#ubicacion_list").html(data);
            jQuery("#ubicacion_list div").on("click", function(e){
                jQuery("#ubicacion_txt").val( jQuery(this).html() );
                jQuery("#ubicacion").val( jQuery(this).attr("value") );
                jQuery("#ubicacion").attr( "data-value", jQuery(this).attr("data-value") );
                jQuery("#ubicacion_list").css("display", "none");
            });
            jQuery("#ubicacion_txt").attr("readonly", false);
        }
    );

	jQuery(".solo_letras").on("keyup", function(e){
		var valor = jQuery( this ).val();
		if( valor != "" ){
			var resul = ""; var no_permitido = false;
			jQuery.each(valor.split(""), function( index, value ) {
			  	if( /^[a-zA-Z ]*$/g.test(value) ){
					resul += value;
				}else{
					no_permitido = true;
				}
			});
			if( no_permitido ){
				jQuery( this ).val(resul);
			}
		}
	});
	
	jQuery(".solo_numeros").on("keyup", function(e){
		var valor = jQuery( this ).val();
		if( valor != "" ){
			var resul = ""; var no_permitido = false;
			jQuery.each(valor.split(""), function( index, value ) {
			  	if( /^[0-9]*$/g.test(value) ){
					resul += value;
				}else{
					no_permitido = true;
				}
			});
			if( no_permitido ){
				jQuery( this ).val(resul);
			}
		}
	});
	
	jQuery("input").on("keypress", function(e){
		var valor = jQuery( this ).attr("minlength");
		if( valor != undefined && valor+0 > 0 ){
			if( jQuery( this ).val().split("").length > valor ){
				var cont = 0; var result = "";
				jQuery.each(jQuery( this ).val().split(""), function( index, value ) {
				  	if( cont < valor ){
				  		result += value;
				  	}
				});
				jQuery( this ).val(result);
				return false;
			}
		}
	});

	jQuery(".social_email").on("change", function(){
		var email = jQuery(this);
		if( email.val().trim() == "" ){
			mensaje( email.attr("name"), '<span name="sp-email">Ingrese su email</span>' )
		}else{
			jQuery.ajax({
		        data: {
					'email': email.val()
				},
		        url:   HOME+'/procesos/login/main.php',
		        type:  'post',
		        success:  function (response) {
	                if (response == 'SI') {
						mensaje( email.attr("name"), '<span name="sp-email">Este E-mail ya esta en uso</span>' );
	                }else if (response == 'NO'){
						mensaje( email.attr("name"), '', true );
	                }
		        }
		    }); 
		}

	});

	jQuery(".obtener_direccion").on("click", function(e){
	    navigator.geolocation.getCurrentPosition(
	    	function(pos) {
	      		var crd = pos.coords;
	      		var position = {
	      			latitude:  crd.latitude,
	      			longitude: crd.longitude
	      		};
	      		vlz_coordenadas(position);
	      		alert("Por favor valida tu ubicación en el mapa y de ser necesario, ajusta el pin a la posición adecuada");
	    	}, 
	    	function error(err) {
	      		alert("Por favor, selecciona en las siguientes opciones, estado, municipio y posteriormente, ajusta el pin hasta tu ubicación adecuada");
	    	},
	    	{
		      	enableHighAccuracy: true,
		      	timeout: 5000,
		      	maximumAge: 0
		    }
	    );
	});

	/*
	jQuery('[name="rc_tipo_documento"]').on("change", function(e){
		switch( jQuery(this).val() ){
			case "":
				jQuery('#rc_ife').css("display", "none");
				jQuery('#rc_pasaporte').css("display", "none");
			break;
			case "IFE / INE":
				jQuery('#rc_ife').css("display", "block");
				jQuery('#rc_pasaporte').css("display", "none");
			break;
			case "Pasaporte":
				jQuery('#rc_ife').css("display", "none");
				jQuery('#rc_pasaporte').css("display", "block");
			break;
		}
	});
	*/

	jQuery("").on("click", function(e){
		jQuery(".btn_rotar").css("display", "none");
		jQuery(".btn_aplicar_rotar").css("display", "none");
		jQuery(".vlz_rotar").css("background-image", "url(https://kmimos.com.mx/wp-content/themes/kmimos/images/popups/registro-cuidador-foto.png)");
		jQuery("#vlz_img_perfil").val("");
	});

	jQuery('[data-toggle="tooltip"]').tooltip(); 

});

function redireccionar(){
	if( jQuery(".popup-registro-cuidador").css("display") == "none" ){
		if( jQuery(".popup-registro-cuidador-correo").css("display") == "none" ){
			location.href = jQuery("#btn_iniciar_sesion").attr("data-url");
		}
	}
}

function vlz_coordenadas(position){
	console.log("Hola 3");
    if(position.latitude != '' && position.longitude != '') {
        LAT = position.latitude;
        LNG = position.longitude;        
    }

    if( LAT == 0 || LNG == 0 ){ }else{
		jQuery.ajax({
	        url:   'https://maps.googleapis.com/maps/api/geocode/json?latlng='+LAT+','+LNG+'&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8',
	        type:  'get',
	        success:  function (response) {

                jQuery(".km-datos-estado-opcion option:contains('"+response.results[0].address_components[5].long_name+"')").prop('selected', true);
                var estado_id = jQuery(".km-datos-estado-opcion option:contains('"+response.results[0].address_components[5].long_name+"')").val();
                
                cambio_municipio(estado_id, function(){
                	jQuery(".km-datos-municipio-opcion option:contains('"+response.results[0].address_components[4].long_name+"')").prop('selected', true);
                });

                jQuery("#rc_direccion").val( response.results[0].formatted_address );

                jQuery("#rc_direccion").focus();
                
				var myLatLng = {lat: LAT, lng: LNG};
                map.setCenter(myLatLng);
	            marker.setPosition(myLatLng);
	            map.setZoom(12);
	            jQuery('#lat').val(LAT);
   				jQuery('#long').val(LNG);
	        }
	    }, "json"); 
	}
}

var LAT = 0;
var LNG = 0;

jQuery(document).on("click", '[data-target="#popup-registro-cuidador1"]' ,function(e){
	e.preventDefault();

	jQuery("#vlz_form_nuevo_cuidador input").val('');

	jQuery("#popup-registro-cuidador1 .modal-content > div").hide();
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
			input.attr( "value", valor );
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
			input.attr( "value", valor );
		}

		if ( span.html() >= 6) {
			el.addClass("disabled");
			span.html( 6 );
			input.val( 6 );	
		}
	}
});

jQuery("#btn_si_acepto_cuidador").on("click", function(e){
	if( !jQuery( "#btn_si_acepto_cuidador" ).hasClass("btn_disable") ){
		jQuery("#popup-registro-cuidador1 .modal-content > div").hide();
		jQuery(".popup-registro-exitoso").css("display", "block");

		jQuery("#popup-registro-cuidador1").on('hidden.bs.modal', function () {
            location.href = jQuery("#btn_iniciar_sesion").attr("data-url");
	    });

		var a = HOME+"/procesos/cuidador/registro-paso1.php";
		var obj = jQuery(this);
		obj.html('Enviando datos');
		jQuery.post( 
			a, 
			jQuery('#vlz_form_nuevo_cuidador').serialize(), 
			function( data ) {
				data = eval(data);
				console.log( data );

				evento_google("nuevo_registro_cuidador");
				evento_fbq("track", "traking_code_nuevo_registro_cuidador");
			}	
		);

		jQuery('[data-target="name"]').html( jQuery('[name="rc_nombres"]').val() );
		jQuery('[name="rc_num_mascota"]').val(1);
		jQuery('.popuphide').css('display', 'none');
		jQuery('.popup-registro-exitoso').css('display', 'block');

	}else{
		alert("Debe leer los terminos y condiciones primero.");
	}
});

jQuery("#btn_no_acepto_cuidador").on("click", function(e){
	location.reload();
});

jQuery( "#popup-registro-cuidador1 .popup-condiciones .terminos_container" ).scroll(function() {
	if( jQuery( "#popup-registro-cuidador1 .popup-condiciones .terminos_container" )[0].scrollHeight <= ( parseInt( jQuery( "#popup-registro-cuidador1 .popup-condiciones .terminos_container" ).scrollTop() ) + 700  ) ){
		jQuery( "#btn_si_acepto_cuidador" ).removeClass("btn_disable");
	}
});

jQuery(document).on("click", '.popup-registro-cuidador-correo .km-btn-popup-registro-cuidador-correo', function ( e ) {
	e.preventDefault();	

	jQuery('input').css('border-bottom', '1px solid #CCCCCC');
	jQuery('[data-error]').css('visibility', 'hidden');
	jQuery('[data-error]').removeClass('tiene_error');

	var list = [  
		'rc_email',
		'rc_nombres',
		'rc_apellidos',
		// 'rc_tipo_documento',
		'fecha',
		'rc_email',
		'rc_clave',
		'rc_telefono',
		'rc_referred'
	];
	/*
	switch( jQuery('[name="rc_tipo_documento"]').val() ){
		case "IFE / INE":
			list.push("rc_ife");
		break;
		case "Pasaporte":
			list.push("rc_pasaporte");
		break;
	}
	*/
	var valid = km_cuidador_validar(list);
	if( valid ){
		jQuery("#popup-registro-cuidador1 .modal-content > div").hide();
		jQuery(".popuphide").css("display", "none");
		jQuery(".popup-condiciones").css("display", "block");
	}else{
		var primer_error = ""; var z = true;
		jQuery( ".tiene_error" ).each(function() {
		  	if( jQuery( this ).css( "display" ) == "block" ){
		  		if( z ){
		  			primer_error = jQuery( this ); 
		  			z = false;
		  		}
		  	}
		});
		jQuery('html, body').animate({ scrollTop: primer_error.offset().top-75 }, 2000);
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
	var list = ['rc_descripcion', 'rc_vlz_img_perfil'];
	var err = {  
		'rc_vlz_img_perfil': 'perfil-img-a'
	};
	var valid = km_cuidador_validar(list, err);
	if( valid ){
		jQuery(".popup-registro-cuidador-paso1").hide();
		jQuery(".popup-registro-cuidador-paso2").fadeIn("fast");	

		subir_fotos();
	}
});

jQuery(document).on("click", '.popup-registro-cuidador-paso2 .km-btn-popup-registro-cuidador-paso2', function ( e ) {
	e.preventDefault();
	var list = ['rc_estado','rc_direccion', 'rc_municipio'];
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
				jQuery('[data-id="ilernus-user"]').html( jQuery('[name="rc_email"]').val() );
				jQuery('[data-id="ilernus-pass"]').html( jQuery('[name="rc_clave"]').val() );

				jQuery(".popup-registro-cuidador-paso3").hide();
				jQuery(".popup-registro-exitoso-final").fadeIn("fast");
			}
		});
	}
});

jQuery(document).on('click', '#finalizar-registro-cuidador', function(){

	var url = jQuery(this).attr('data-href');

	// $("<a>").attr("href", "https://kmimos.ilernus.com/login/index.php").attr("target", "_blank")[0].click();

	setTimeout(function() {
		location.href = url;
    },1500);
});

/*POPUP REGISTRO CUIDADOR*/
jQuery( document ).on('click', "[data-load='portada']", function(e){
	jQuery('#portada').click();
});

function cambio_municipio(estado_id, CB = false){
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

            if( CB != false ){
            	CB();
            }
        }
    ).fail(
        function( jqXHR, textStatus, errorThrown ) {
            console.log( "Error: " +  errorThrown );
        }
    );
}

jQuery(document).on('change', 'select[name="rc_estado"]', function(e){
	var estado_id = jQuery(this).val();
	    
    if( estado_id != "" ){
        cambio_municipio(estado_id);
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
	jQuery('[data-error="'+label+'"]').addClass('tiene_error');
 	jQuery('[data-error="'+label+'"]').html(msg);
	jQuery('[name="'+label+'"]').css('border-bottom', '1px solid ' + border_color);
}
  
function km_cuidador_validar( fields, error_field={} ){

 	var status = true;
 	var primerError = '';
	if( fields.length > 0 ){
		jQuery.each( fields, function(id, val){
			var m = '';
			/*validar vacio*/
			if( jQuery('[name="'+val+'"]').val() == '' ){
				m = 'Este campo no puede estar vacio';
				primerError = (!empty(primerError))? primerError : '[name="'+val+'"]' ;
			}
			/*validar longitud*/
			if( m == ''){
				m = rc_validar_longitud( val );
				primerError = (!empty(primerError))? primerError : '[name="'+val+'"]' ;
			}

			if( m == ''){
				mensaje(val, m, true);
			}else{
				mensaje(val, m);
				status = false;
  				jQuery('#'+error_field[val]).css('border', '1px solid red');
			    jQuery('#popup-registro-cuidador1').animate({ scrollTop: jQuery(primerError).offset().top-180 }, 500); 
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

			case 'rc_pasaporte':
				result = validar_longitud( val, 10, 28, 'string', 'Debe tener entre 10 y 28 digitos');
			break;

			case 'fecha':
				result = validar_longitud( val, 10, 10, 'string', 'Debe tener 10 digitos');
			break;

			case 'rc_clave':
				result = validar_longitud( val, 1, 200, 'string', 'Debe estar entre 1 y 200 caracteres');
				break;

			case 'rc_telefono':
				result = validar_longitud( val, 7, 15, 'string', 'Debe estar entre 7 y 15 caracteres');
				break;

			case 'rc_descripcion':
				result = validar_longitud( val, 1, 1000, 'string', 'Debe estar entre 1 y 1000 caracteres');
				break;

			case 'rc_direccion':
				result = validar_longitud( val, 1, 600, 'string', 'Debe estar entre 5 y 300 caracteres');
				break;
	};
	return result;
}

function vista_previa(evt) {
	
	jQuery('#perfil-img-a').css('border', '0px');	
	jQuery('[data-error="rc_vlz_img_perfil"]').css('visibility', 'hidden');
	var files = evt.target.files;
	getRealMime(this.files[0]).then(function(MIME){
        if( MIME.match("image.*") ){

        	jQuery(".vlz_cargando").css("display", "block");

            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    redimencionar(e.target.result, function(img_reducida){
                        var img_pre = jQuery(".vlz_rotar_valor").attr("value");
                        jQuery.post( RUTA_IMGS+"/procesar.php", {img: img_reducida, previa: img_pre}, function( url ) {
                           
                        	jQuery("#perfil-img-a").css("background-image", "url("+RAIZ+"imgs/Temp/"+url+")" );
				      		jQuery("#perfil-img").css("display", "none" );
		        			jQuery("#vlz_img_perfil").val( url );
		        			jQuery(".vlz_rotar_valor").attr( "value", url );
			           		jQuery(".kmimos_cargando").css("visibility", "hidden");

                            jQuery(".btn_rotar").css("display", "block");
                            jQuery(".btn_quitar_foto").css("display", "block");

                            jQuery(".vlz_cargando").css("display", "none");
                        });
                    });      
                };
           })(files[0]);
           reader.readAsDataURL(files[0]);
        }else{
        	padre.children('#portada').val("");
            alert("Solo se permiten imagenes");
        }
    }).catch(function(error){
        padre.children('#portada').val("");
        alert("Solo se permiten imagenes");
    }); 

}      
document.getElementById("portada").addEventListener("change", vista_previa, false);


function empty(valor){
	return ( String( valor ).length <= 0 );
}

var lat = null;
var lng = null;
var map = null;
var geocoder = null;
var marker = null;
         
jQuery(document).ready(function(){
    jQuery('[name="rc_estado"]').on("change", function(){
    	codeAddress(8);
    });
	jQuery('[name="rc_municipio"]').on("change", function(){
		codeAddress(12);
	});

    jQuery('#rc_direccion').on("keypress", function(e){
        /*console.log( e );*/
        /*if( e.charCode == "13" ){
            codeAddress();
            e.preventDefault();
        }else{
            if( e.key == "Enter" ){
                codeAddress();
                e.preventDefault();
            }
        }*/
     });
});
     
function initialize() {
    lat = "23.634501";
    lng = "-102.552784";
    geocoder = new google.maps.Geocoder();
    if(lat !='' && lng != ''){
        var latLng = new google.maps.LatLng(lat,lng);
    }
    var myOptions = {
        center: latLng,
        zoom: 5,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    marker = new google.maps.Marker({
        map: map,
        position: latLng,
        draggable: true
    });

    google.maps.event.addListener(marker, 'dragend', function(){
        updatePosition(marker.getPosition());
    });
}
 
function codeAddress(zoom) {
	if( zoom == undefined ){
		zoom = 5;
	}
    var estado = jQuery('[name="rc_estado"] option:selected').text();
    var delegacion = jQuery('[name="rc_municipio"] option:selected').text();
    var address = document.getElementById("rc_direccion").value;
    
    address = estado+"+"+delegacion+"+"+address;

    console.log( address );

    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            marker.setPosition(results[0].geometry.location);
            map.setZoom(zoom);
            updatePosition(results[0].geometry.location);
            google.maps.event.addListener(marker, 'dragend', function(){
                updatePosition(marker.getPosition());
            });
        } else {
            alert("No podemos encontrar la dirección\nPero no te preocupes, puedes ubicarla directamente en el mapa moviendo el pin.");
        }
    });
  }
   
function updatePosition(latLng) {
   jQuery('#lat').val(latLng.lat());
   jQuery('#long').val(latLng.lng());
}

(function(d, s){
    map = d.createElement(s), e = d.getElementsByTagName(s)[0];
    map.async=!0;
    map.setAttribute("charset","utf-8");
    map.src="//maps.googleapis.com/maps/api/js?key=AIzaSyBdswYmnItV9LKa2P4wXfQQ7t8x_iWDVME&sensor=true&callback=initialize";
    map.type="text/javascript";
    e.parentNode.insertBefore(map, e);
})(document,"script");