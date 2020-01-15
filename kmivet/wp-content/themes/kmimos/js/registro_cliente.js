/*MODAL SHOW*/

var CERRAR_MODAL = true;

jQuery(document).on('click', '.modal_show' ,function(e){
    modal_show(this);
});

function modal_show(element){
    var modal = jQuery(element).data('modal');
    jQuery('.modal').modal('hide');
    jQuery(modal).modal("show");
}

jQuery( document ).ready( function(){
	jQuery(".terminos_container").load(HOME+"/terminos_HTML.php");
});

function cerrar_modal(){
	location.reload();
}

var globalData = "";
jQuery(document).on("click", '[data-target="#popup-registrarte"]' ,function(e){
	e.preventDefault();
	jQuery('[data-error="auth"]').fadeOut("fast");
	jQuery(".popup-registrarte-1").css("display", 'block');
	jQuery(".popup-registrarte-nuevo-correo").css("display", 'none');
	jQuery(".popup-registrarte-final").css('display', 'none');
	jQuery('#form_nuevo_cliente')[0].reset();
	jQuery( jQuery(this).data('target') ).modal('show');
});

jQuery("#popup-registrarte-datos-mascota").ready(function(){
    jQuery("#nombre").blur(function(){
		if(jQuery("#nombre").val().length < 3){
			jQuery("#nombre").parent('div').css('color','red');
			jQuery("#nombre").after('<span name="sp-nombre">Ingrese su Nombre</span>').css('color','red');
			jQuery("#nombre").focus(function() { jQuery("[name='sp-nombre']").remove(); });
		}else{
			jQuery("#nombre").css('color','green');
			jQuery("#nombre").parent('div').css('color','green');
			jQuery('[name="sp-nombre"]').remove();
		}
	});

	jQuery("#apellido").blur(function(){
		if(jQuery("#apellido").val().length < 3){
			jQuery("#apellido").parent('div').css('color','red');
			jQuery("#apellido").after('<span name="sp-apellido">Ingrese su apellido</span>').css('color','red');
			jQuery("#apellido").focus(function() { jQuery("[name='sp-apellido']").remove(); });
		}else{
			jQuery("#apellido").css('color','green');
			jQuery("#apellido").parent('div').css('color','green');
			jQuery('[name="sp-apellido"]').remove();
		}
	});

	jQuery(".verify_mail").blur(function(){

		var verify = jQuery(this).closest('.verify');
		var verify_mail = jQuery(this);
		var verify_result = jQuery(verify).find('.verify_result');
		var verify_data = verify_mail.data('verify');
		
		if(verify_mail.val().length == 0){
			verify_mail.parent('div').css('color','red');
			verify_mail.after('<span name="sp-email">Ingrese su email</span>').css('color','red');
			verify_mail.focus(function() { jQuery('[name="sp-email"]').remove(); });
			jQuery('.verify_mail').parent().find('.verify_result').html('');

		}else{
			verify_mail.css('color','green');
			verify_mail.parent('div').css('color','green');
			jQuery('[name="sp-email_1"]').remove();
			var email = verify_mail.val();
			var campo = {
				'email': email
			}

			jQuery.ajax({
	        data:  campo,
	        url:   HOME+'/procesos/login/main.php',
	        type:  'post',
	        beforeSend: function () {
				verify_result.html("Procesando, espere por favor...");
				verify_result.css('color','green');
	        },
	        success:  function (response) {
                if (response == 'SI') {
					verify_result.html("Este E-mail ya esta en uso");
					verify_result.css('color','red');
					verify_mail.parent('div').css('color','red');
					verify_mail.css('color','red');
					verify_mail.removeClass('correctly');

					if(verify_data=='noactive'){
						verify_result.html("E-mail correcto!");
						verify_result.css('color','green');
						verify_mail.addClass('correctly');
					}

                }else if (response == 'NO'){
					verify_result.html("E-mail disponible!");
					verify_result.css('color','green');
					verify_mail.addClass('correctly');

					if(verify_data=='noactive'){
						verify_result.html("Este E-mail no existe");
						verify_result.css('color','red');
						verify_mail.removeClass('correctly');
					}

                }else if (response == 'NO_MAIL'){
					verify_result.html("E-mail no es correcto!");
					verify_result.css('color','red');
					verify_mail.parent('div').css('color','red');
					verify_mail.css('color','red');
					verify_mail.removeClass('correctly');
				}
	        }
	    }); 
		}
	});

	jQuery("#pass").blur(function(){
		if(jQuery("#pass").val().length == 0){		
			jQuery("#pass").parent('div').css('color','red');
			jQuery("#pass").after('<span name="sp-pass">Ingrese su Contraseña</span>').css('color','red');
			jQuery("#pass").focus(function() { jQuery('[name="sp-pass"]').remove(); });
		}else{
			jQuery("#pass").css('color','green');
			jQuery("#pass").parent('div').css('color','green');
			jQuery('[name="sp-pass"]').remove();
		}
	});

	jQuery("#movil").blur(function(){
		switch(jQuery("#movil").val().length) {
			case 0:
				jQuery("#movil").parent('div').css('color','red');
				jQuery("#movil").after('<span name="sp-movil">Debe ingresar su movil</span>').css('color','red');
				jQuery("#movil").focus(function() { jQuery('[name="sp-movil"]').remove(); });
			break;
			case 10:
					jQuery("#movil").css('color','green');
					jQuery("#movil").parent('div').css('color','green');
					jQuery('[name="sp-movil"]').remove();
			break;
			default:
				jQuery("#movil").parent('div').css('color','red');
				jQuery("#movil").after('<span name="sp-movil">Su movil debe contener 10 dígitos</span>').css('color','red');
				jQuery("#movil").focus(function() { jQuery('[name="sp-movil"]').remove(); });
			break;
		}
	});

	jQuery("#genero").blur(function(){
		if(jQuery("#genero").val().length == 0){		
			jQuery("#genero").parent('div').css('color','red');
			jQuery("#genero").after('<span name="sp-genero">Debe Seleccionar una opcion</span>').css('color','red');
			jQuery("#genero").focus(function() { jQuery('[name="sp-genero"]').remove(); });
		}else{
			jQuery("#genero").css('color','green');
			jQuery("#genero").parent('div').css('color','green');
			jQuery('[name="sp-genero"]').remove();
		}
	});

	jQuery("#edad").blur(function(){
		if(jQuery("#edad").val().length == 0){		
			jQuery("#edad").parent('div').css('color','red');
			jQuery("#edad").after('<span name="sp-edad">Debe Seleccionar una opcion</span>').css('color','red');
			jQuery("#edad").focus(function() { jQuery('[name="sp-edad"]').remove(); });
		}else{
			jQuery("#edad").css('color','green');
			jQuery("#edad").parent('div').css('color','green');
			jQuery('[name="sp-edad"]').remove();
		}
	});

	jQuery("#btn_si_acepto").on("click", function(e){
		if( !jQuery( "#btn_si_acepto" ).hasClass("btn_disable") || jQuery( "#btn_si_acepto" ).hasClass("btn_disable_2") ){
			if( !jQuery( "#btn_si_acepto" ).hasClass("btn_disable_2") ){

				jQuery( "#btn_si_acepto" ).addClass("btn_disable");
				jQuery( "#btn_si_acepto" ).addClass("btn_disable_2");

				jQuery(".popup-condiciones").css("display", "none");
				jQuery(".popup-registrarte-final").css("display", "block");

				jQuery("#btn_cerrar").on("click", function(e){
					finalizar_proceso();
				});

				jQuery("#popup-registrarte").on('hidden.bs.modal', function () {
					finalizar_proceso();
			    });

				var nombre = jQuery('#form_nuevo_cliente [name="nombre"] ').val();
					apellido = jQuery("#apellido").val(),
				 	email = jQuery("#email_1").val(), 
				 	pass = jQuery("#pass").val(), 
				 	movil = jQuery("#movil").val(), 
				 	genero = jQuery("#genero").val(), 
				 	edad = jQuery("#edad").val(), 
				 	fumador = jQuery("#fumador").val(),
					referido = jQuery("#referido").val(),
					img_profile = jQuery("#img_profile").val();

			 	var campos = [nombre,apellido,"",email,pass,movil,genero,edad,fumador,referido,img_profile];

				var datos = {
					'name': campos[0],
					'lastname': campos[1],
					'email': campos[3],
					'password': campos[4],
					'movil': campos[5],
					'gender': campos[6],
					'age': campos[7],
					'smoker': campos[8],
					'referido': campos[9],
					'img_profile': campos[10],
					'social_facebook_id': jQuery('#facebook_cliente_id').val(),
					'social_google_id': jQuery('#google_cliente_id').val()
				};

				jQuery.post( HOME+'/procesos/login/registro.php', datos, function( data ) {
					if( data > 0 ){
						globalData = data;
						jQuery("#km-datos-foto").css("background-image", "url("+jQuery("#km-datos-foto").attr("data-init-img")+")" );
						jQuery("#img_pet").val( "" );
						jQuery("body").scrollTop(0);

						evento_google("nuevo_registro_cliente");
						evento_fbq("track", "traking_code_nuevo_registro_cliente"); 

						if( wlabel == "petco" ){
							window.adf&&adf.ClickTrack(this,1453019,'MX_Kmimos_RegistoTYP_180907',{});
							//registro campaing monitor
							jQuery.post( 
			                    RAIZ+"campaing/suscribir.php",{
			                        "email": datos['email'],
			                        "list": 'petco_registro'
			                    }, 
			                    function( data ) {
			                        console.log( data );
			                        console.log("Suscripción enviadas");
			                    }
			                );
						}
					}
					jQuery('.km-btn-popup-registrarte-nuevo-correo').html('SIGUIENTE');
				});
			}
		}else{
			alert("Debe leer los terminos y condiciones primero.");
		}
	});

	jQuery("#btn_no_acepto").on("click", function(e){
		location.reload();
	});

	jQuery( "#popup-registrarte .popup-condiciones .terminos_container" ).scroll(function() {
		if( jQuery( ".popup-condiciones .terminos_container" )[0].scrollHeight <= ( parseInt( jQuery( ".popup-condiciones .terminos_container" ).scrollTop() ) + 700  ) ){
			jQuery( "#btn_si_acepto" ).removeClass("btn_disable");
		}
	});

	jQuery(document).on("click", '.popup-registrarte-nuevo-correo .km-btn-popup-registrarte-nuevo-correo', function ( e ) {
		e.preventDefault();

		jQuery('#siguiente').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> GUARDANDO');

		// var nombre = jQuery("#nombre").val();
		var nombre = jQuery('#form_nuevo_cliente [name="nombre"]').val(),
			apellido = jQuery("#apellido").val(),
			ife = jQuery("#ife").val(),
		 	email = jQuery("#email_1").val(), 
		 	pass = jQuery("#pass").val(), 
		 	movil = jQuery("#movil").val(), 
		 	genero = jQuery("#genero").val(), 
		 	edad = jQuery("#edad").val(), 
		 	fumador = jQuery("#fumador").val(),
			referido = jQuery("#referido").val(),
			img_profile = jQuery("#img_profile").val();

	 	var campos = [nombre,apellido,ife,email,pass,movil,genero,edad,fumador,referido,img_profile];
	 	
	 	var fields = [
			'nombre',
			'apellido',
			'ife',
			'email_1',
			'pass',
			'movil',
			'genero',
			'edad',
			'fumador',
			'referido'
	 	];
		km_cliente_validar(fields);
		
		jQuery("#km-datos-foto-profile").css('border', "0px solid transparent");

		if (nombre.length > 2 && 
			apellido.length > 2 && 
			email.length > 2 && 
			pass.length > 0 && 
			movil.length > 2 && 
			genero != "" && 
			edad != ""
			) {

			jQuery("#popup-registrarte .modal-content > div").css("display", "none");
			jQuery(".popup-condiciones").css("display", "block");

		}else {
			jQuery('.km-btn-popup-registrarte-nuevo-correo').html('SIGUIENTE');
         	alert("Revise sus datos por favor, debe llenar todos los campos");
        }
	});
});

function km_cliente_validar( fields ){
	var primer_error = '';
	var status = true;
	if( fields.length > 0 ){
		jQuery.each( fields, function(id, val){
			var m = '';
			var valor = '';
			/*validar vacio*/
			if( jQuery('#'+val).val() == '' ){
				valor = jQuery('#'+val).val();
				m = 'Este campo no puede estar vacio';
			}else if( val == 'tipo_mascota' && jQuery('#tipo_mascota').val() < 1){
				m = 'Este campo no puede estar vacio';
			}else if( val == 'raza_mascota'  && jQuery('#raza_mascota').val() < 1){
				m = 'Este campo no puede estar vacio';
			}else if( val == 'email' ){
				jQuery("#km-datos-foto-profile").css('border', "1px solid red");
			}else if( val == 'tamano_mascota' ){
				if (jQuery("#select_1").hasClass("km-opcionactivo")) {
					valor = jQuery("#select_1").attr('value');
				}else if(jQuery("#select_2").hasClass("km-opcionactivo")){
					valor = jQuery("#select_2").attr('value');
				} else if (jQuery("#select_3").hasClass("km-opcionactivo")){
					valor = jQuery("#select_3").attr('value');
				}else if (jQuery("#select_4").hasClass("km-opcionactivo")){
					valor = jQuery("#select_4").attr('value');
				}
				if( valor == '' ){
					m = 'Este campo no puede estar vacio';
				}
			}

			if( m == ''){
				status = true;
				jQuery('[name="sp-'+jQuery('#'+val).attr('name')+'"]').remove();
			}else{
				jQuery('#'+val).parent('div').css('color','red');
				jQuery('[name="sp-'+jQuery('#'+val).attr('name')+'"]').remove();
				jQuery('#'+val).after('<span name="sp-'+jQuery('#'+val).attr('name')+'">'+m+'</span>').css('color','red');
				status = false;
				if(primer_error==''){
					primer_error = val;
				}
			}
		});
	   // jQuery('html, body').animate({ scrollTop: jQuery("#"+primer_error).offset().top-180 }, 2000);
	}
	return status;
}

function __ajax(url, data){
	var ajax = jQuery.ajax({
		"method": "POST",
		"url": url,
		"data": data
	})
	return ajax;
}

function getGlobalData(url,method, datos){
	return jQuery.ajax({
		data: datos,
		type: method,
		url: HOME+url,
		async:false,
		success: function(data){
			return data;
		}
	}).responseText;
}

jQuery( document ).on('change', '[data-change]', function(){
    var tipo = jQuery(this).attr('data-change');
    var str = jQuery(this).val();
    if(tipo != 'undefined' || tipo != ''){
    	var cadena= '';
        if(tipo.indexOf('alf')>-1 ){ cadena = cadena + 'a-zA-ZáéíóúñüÁÉÍÓÚÑÜ' }
        if(tipo.indexOf('xlf')>-1 ){ cadena = cadena + 'a-zA-ZáéíóúñüÁÉÍÓÚÑÜ ' }
        if(tipo.indexOf('mlf')>-1 ){ cadena = cadena + 'a-zA-Z' }
        if(tipo.indexOf('ylf')>-1 ){ cadena = cadena + 'A-Z' }
        if(tipo.indexOf('num')>-1 ){ cadena = cadena + '0-9' } 
        if(tipo.indexOf('cur')>-1 ){ cadena = cadena + '0-9,.' } 
        if(tipo.indexOf('esp')>-1 ){ cadena = cadena + '-_.%&@,/()' }
        if(tipo.indexOf('cor')>-1 ){ cadena = cadena + '.-_@0-9a-zA-Z' }
        if(tipo.indexOf('rif')>-1 ){ cadena = cadena + 'vjegi' }
        if(tipo.indexOf('dir')>-1 ){ cadena = cadena + ',' }
        cadena = eval("/[^"+cadena+"]+/g");
	    str = str.replace( cadena ,'');
    }
    jQuery(this).val(str);
});

jQuery( document ).on('keypress', '[data-charset]', function(e){
    var tipo= jQuery(this).attr('data-charset');
    if(tipo!='undefined' || tipo!=''){
        var cadena = "";
        if(tipo.indexOf('alf')>-1 ){ cadena = cadena + "abcdefghijklmnopqrstuvwxyzáéíóúñüÁÉÍÓÚÑÜ"; }
        if(tipo.indexOf('xlf')>-1 ){ cadena = cadena + "abcdefghijklmnopqrstuvwxyzáéíóúñüÁÉÍÓÚÑÜ "; }
        if(tipo.indexOf('mlf')>-1 ){ cadena = cadena + "abcdefghijklmnopqrstuvwxyz"; }
        if(tipo.indexOf('num')>-1 ){ cadena = cadena + "1234567890"; }
        if(tipo.indexOf('cur')>-1 ){ cadena = cadena + "1234567890,."; }
        if(tipo.indexOf('esp')>-1 ){ cadena = cadena + "-_.$%&@,/()"; }
        if(tipo.indexOf('cor')>-1 ){ cadena = cadena + ".-_@"; }
        if(tipo.indexOf('rif')>-1 ){ cadena = cadena + "vjegi"; }
        if(tipo.indexOf('dir')>-1 ){ cadena = cadena + ","; }
        var key = e.which,
            keye = e.keyCode,
            tecla = String.fromCharCode(key).toLowerCase(),
            letras = cadena;
        if(letras.indexOf(tecla)==-1 && keye!=9&& (key==37 || keye!=37)&& (keye!=39 || key==39) && keye!=8 && (keye!=46 || key==46) || key==161){
            e.preventDefault();
        }
    }
});

/*POPUP INICIAR SESIÓN*/
	jQuery(document).on("click", '.popup-iniciar-sesion-1 .km-btn-contraseña-olvidada', function ( e ) {
		e.preventDefault();
		jQuery(".popup-iniciar-sesion-1").css("display", "none");
		jQuery(".popup-olvidaste-contrasena").css("display", "block");
	});

	jQuery(document).on("click", '.popup-registrarte-1 .km-btn-popup-registrarte-1', function ( e ) {
		e.preventDefault();
		jQuery(".popup-registrarte-1").css("display", "none");
		jQuery(".popup-registrarte-nuevo-correo").css("display", "block");
	});
/*FIN POPUP INICIAR SESIÓN*/

/* Cargar imagen de la perfil */
function vista_previa_perfil(evt) {
	jQuery('[name="sp-img_profile"]').parent().css('color', '#000');
	jQuery('#img_profile').parent().css('color', '#000');
	jQuery('[name="sp-img_profile"]').remove();
	var files = evt.target.files;
	getRealMime(this.files[0]).then(function(MIME){
        if( MIME.match("image.*") ){
        	jQuery("#loading-perfil").css("display", "block");
            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    redimencionar(e.target.result, function(img_reducida){
                        var img_pre = jQuery(".vlz_rotar_valor").attr("value");
                        jQuery.post( RUTA_IMGS+"/procesar.php", {img: img_reducida, previa: img_pre}, function( url ) {
                           
                        	jQuery("#km-datos-foto-profile").css("background-image", "url("+RAIZ+"imgs/Temp/"+url+")");
							jQuery("#img_profile").val( url );
							jQuery(".kmimos_cargando").css("visibility", "hidden");
							jQuery("#loading-perfil").css("display", "none");

                            jQuery("#form_nuevo_cliente .btn_rotar").css("display", "block");
                        });
                    });      
                };
           })(files[0]);
           reader.readAsDataURL(files[0]);
        }else{
        	padre.children('#carga_foto_profile').val("");
            alert("Solo se permiten imagenes");
        }
    }).catch(function(error){
        padre.children('#carga_foto_profile').val("");
        alert("Solo se permiten imagenes");
    }); 
}      

jQuery("#km-datos-foto-profile").on('click', function(){
	jQuery("#carga_foto_profile").trigger("click");
});
document.getElementById("carga_foto_profile").addEventListener("change", vista_previa_perfil, false);
/* Cargar imagen de la perfil */

jQuery("#recovery-clave").on('click',function(){
	recuperar_clave(jQuery("#form_recuperar"));
});
jQuery("#form_recuperar").submit(function(){
	recuperar_clave(jQuery(this));
	return false;
});

function recuperar_clave(_this){
	_this.find(".response").html('');
	var mail = _this.find("#usuario");
	var data_email = mail.val();
	var obj = _this.find(".verify_result");
	var err = '';
	jQuery('#recovery-clave').html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> RESTAURANDO CLAVE');
	if(data_email == ""){
		err = 'Este campo no puede estar vacio';
	}else if( data_email.length<3 ){
		err = 'Formato de email invalido';
	}else if(!mail.hasClass('correctly')){
		err = 'Este E-mail no existe';
	}else{
		var datos = {'email': data_email};
		jQuery.post(HOME+'/procesos/login/recuperar.php', datos, function(_result){
			var r = jQuery.parseJSON(_result);
			_this.find(".response").html(r.msg);
			jQuery('#recovery-clave').html('ENVIAR CONTRASEÑA');
			if( r.sts == 1 ){
				setTimeout(function(){ jQuery('.modal').modal('hide'); },3600);
			}
		});
	}
	if( err != '' ){
		obj.css('color', 'red');
		obj.html(err);
		jQuery('#recovery-clave').html('ENVIAR CONTRASEÑA');
	}else{
		obj.html('');
	}
}

function finalizar_proceso(){
	jQuery.post(
		HOME+'procesos/login/enviar_mail_admin.php', 
		{ 'email' : jQuery("#email_1").val() }, 
		function(_result){
			location.reload();
		}
	);
}