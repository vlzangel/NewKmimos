
var globalData = "";

$("#popup-registrarte-datos-mascota").ready(function(){

    $("#nombre").blur(function(){
		
		if($("#nombre").val().length == 0){	
			$("#nombre").parent('div').css('color','red');
			$("#nombre").after('<span name="sp-name">Ingrese su Nombre</span>').css('color','red');
			$("#nombre").focus(function() { $("[name='sp-name']").hide(); });
		}else{
			$("#nombre").css('color','green');
			$("#nombre").parent('div').css('color','green');
			$("[name='sp-name']").hide();
		}
	});

	$("#apellido").blur(function(){
		
		if($("#apellido").val().length == 0){		
			$("#apellido").parent('div').css('color','red');
			$("#apellido").after('<span name="sp-lastname">Ingrese su apellido</span>').css('color','red');
			$("#apellido").focus(function() { $("[name='sp-lastname']").hide(); });
		}else{
			$("#apellido").css('color','green');
			$("#apellido").parent('div').css('color','green');
			$("[name='sp-lastname']").hide();
		}
		
	});

	$("#ife").blur(function(){
		switch($("#ife").val().length) {
		case 0:
			$("#ife").parent('div').css('color','red');
			$("#ife").after('<span name="sp-ife">Debe ingresar su ife</span>').css('color','red');
			$("#ife").focus(function() { $("[name='sp-ife']").hide(); });
			break;
		case 11:
				$("#ife").css('color','green');
				$("#ife").parent('div').css('color','green');
				$("[name='sp-ife']").hide();
			break;
		default:
			$("#ife").parent('div').css('color','red');
			$("#ife").after('<span name="sp-ife">Su ife debe contener 11 dígitos</span>').css('color','red');
			$("#ife").focus(function() { $("[name='sp-ife']").hide(); });
		}
	});

	$("#email_1").blur(function(){
		if($("#email_1").val().length == 0){		
			$("#email_1").parent('div').css('color','red');
			$("#email_1").after('<span name="sp-email">Ingrese su email</span>').css('color','red');
			$("#email_1").focus(function() { $("[name='sp-email']").hide(); });
		}else{
			$("#email_1").css('color','green');
			$("#email_1").parent('div').css('color','green');
			$("[name='sp-email']").hide();
			var email = $("#email_1").val();
			var campo = {
				'email': email
			}
			$.ajax({
	        data:  campo, //datos que se envian a traves de ajax
	        url:   HOME+'/procesos/login/main.php',//HOME+"/procesos/login/validate_email.php", //archivo que recibe la peticion
	        type:  'post', //método de envio
	        beforeSend: function () { // carga mientras va hacer la consulta
	                $("#resultado").html("Procesando, espere por favor...");
	                $("#resultado").css('color','green');
	        },
	        success:  function (response) { //una vez que el archivo recibe el request lo procesa y lo devuelve
	                if (response == 'SI') {
	                    $("#resultado").html("Este E-mail ya esta en uso");
	                    $("#resultado").css('color','red');
	                    $("#email_1").parent('div').css('color','red');
	                    $("#email_1").css('color','red');
	                }else{
	                    $("#resultado").html("E-mail disponible!");
	                    $("#resultado").css('color','green');
	                }
	        }
	    }); 
		}
	});

	$("#pass").blur(function(){
		
		if($("#pass").val().length == 0){		
			$("#pass").parent('div').css('color','red');
			$("#pass").after('<span name="sp-pass">Ingrese su Contraseña</span>').css('color','red');
			$("#pass").focus(function() { $("[name='sp-pass']").hide(); });
		}else{
			$("#pass").css('color','green');
			$("#pass").parent('div').css('color','green');
			$("[name='sp-pass']").hide();
		}
	});

	$("#movil").blur(function(){
		
		switch($("#movil").val().length) {
			case 0:
				$("#movil").parent('div').css('color','red');
				$("#movil").after('<span name="sp-movil">Debe ingresar su movil</span>').css('color','red');
				$("#movil").focus(function() { $("[name='sp-movil']").hide(); });
				break;
			case 11:
					$("#movil").css('color','green');
					$("#movil").parent('div').css('color','green');
					$("[name='sp-movil']").hide();
				break;
			default:
				$("#movil").parent('div').css('color','red');
				$("#movil").after('<span name="sp-movil">Su movil debe contener 11 dígitos</span>').css('color','red');
				$("#movil").focus(function() { $("[name='sp-movil']").hide(); });
		}
	});


	$("#genero").blur(function(){
		
		if($("#genero").val().length == 0){		
			$("#genero").parent('div').css('color','red');
			$("#genero").after('<span name="sp-genero">Debe Seleccionar una opcion</span>').css('color','red');
			$("#genero").focus(function() { $("[name='sp-genero']").hide(); });
		}else{
			$("#genero").css('color','green');
			$("#genero").parent('div').css('color','green');
			$("[name='sp-genero']").hide();
		}
	});

	$("#edad").blur(function(){
		
		if($("#edad").val().length == 0){		
			$("#edad").parent('div').css('color','red');
			$("#edad").after('<span name="sp-edad">Debe Seleccionar una opcion</span>').css('color','red');
			$("#edad").focus(function() { $("[name='sp-edad']").hide(); });
		}else{
			$("#edad").css('color','green');
			$("#edad").parent('div').css('color','green');
			$("[name='sp-edad']").hide();
		}
	});

	$("#fumador").blur(function(){
		
		if($("#fumador").val().length == 0){		
			$("#fumador").parent('div').css('color','red');
			$("#fumador").after('<span name="sp-fumador">Debe Seleccionar una opcion</span>').css('color','red');
			$("#fumador").focus(function() { $("[name='sp-fumador']").hide(); });
		}else{
			$("#fumador").css('color','green');
			$("#fumador").parent('div').css('color','green');
			$("[name='sp-fumador']").hide();
		}
	});

	$(document).on("click", '.popup-registrarte-nuevo-correo .km-btn-popup-registrarte-nuevo-correo', function ( e ) {
		e.preventDefault();
		var nombre = $("#nombre").val(); 
			apellido = $("#apellido").val(),
			ife = $("#ife").val(),
		 	email = $("#email_1").val(), 
		 	pass = $("#pass").val(), 
		 	movil = $("#movil").val(), 
		 	genero = $("#genero").val(), 
		 	edad = $("#edad").val(), 
		 	fumador = $("#fumador").val();
		 	var campos = [nombre,apellido,ife,email,pass,movil,genero,edad,fumador];
		if (nombre != "" && apellido != "" && ife != "" && email !="" && pass != "" && movil != ""
		       	&& genero != "" && edad != "" && fumador !="") {
				$(".popup-registrarte-nuevo-correo").hide();
				$(".popup-registrarte-datos-mascota").fadeIn("fast");
				var datos = {
					'name': campos[0],
					'lastname': campos[1],
					'idn': campos[2],
					'email': campos[3],
					'password': campos[4],
					'movil': campos[5],
					'gender': campos[6],
					'age': campos[7],
					'smoker': campos[8]};

			// globalData = getGlobalData('/procesos/login/registro.php','post', datos); 
			// console.log(globalData);
		}else {
         	alert("Revise sus datos por favor, debe llenar todos los campos");
        }
	});
});


$("#popup-registrarte-datos-mascota").ready(function(){
	
	$(".km-datos-foto").on('click', function(){
		$("#carga_foto").trigger("click");
		document.addEventListener('change',cargaImagen, false);
	});
	
	$("#nombre_mascota").blur(function(){
		if($("#nombre_mascota").val().length == 0){		
			$("#nombre_mascota").parent('div').css('color','red');
			$("#nombre_mascota").after('<span name="sp-name">Ingrese el nombre de su mascota</span>').css('color','red');
			$("#nombre_mascota").focus(function() { $("[name='sp-name']").hide(); });
		}else{
			$("#nombre_mascota").css('color','green');
			$("#nombre_mascota").parent('div').css('color','green');
			$("[name='sp-name']").hide();
		}
	});

	$("#tipo_mascota").blur(function(){
		
		switch($("#tipo_mascota").val()) {
			case "0":
				$("#tipo_mascota").parent('div').css('color','red');
				$("#tipo_mascota").before('<span name="sp-tipo_mascota">Debe seleccionar un tipo</span>').css('color','red');
				$("#tipo_mascota").focus(function() { $("[name='sp-tipo_mascota']").hide(); });
				break;
			case "2605":
					$("#tipo_mascota").css('color','green');
					$("#tipo_mascota").focus(function() { $("[name='sp-tipo_gato']").hide(); });
					listarAjax();
				break;
			case "2608":
					$("#tipo_mascota").css('color','green');
					$("#tipo_mascota").focus(function() { $("[name='sp-tipo_perro']").hide(); });
					$('#raza_mascota').html("<option id='select_mascota' value='1'>Gato</option>").fadeIn();
				break;
			default:
				$("#tipo_mascota").parent('div').css('color','red');
				$("#tipo_mascota").before('<span name="sp-tipo_mascota">Su movil debe contener 11 dígitos</span>').css('color','red');
				$("#tipo_mascota").focus(function() { $("[name='sp-tipo_mascota']").hide(); });
		}
	});

	$("#raza_mascota").blur(function(){
		if($("#raza_mascota").val() == 0){		
			$("#raza_mascota").parent('div').css('color','red');
			$("#raza_mascota").after('<span name="sp-color">Seleccione la raza de su mascota</span>').css('color','red');
			$("#raza_mascota").focus(function() { $("[name='sp-color']").hide(); });
		}else{
			$("#raza_mascota").css('color','green');
			$("#raza_mascota").parent('div').css('color','green');
			$("[name='sp-color']").hide();
		}
	});

	$("#color_mascota").blur(function(){
		if($("#color_mascota").val().length == 0){		
			$("#color_mascota").parent('div').css('color','red');
			$("#color_mascota").after('<span name="sp-color">Ingrese el color de su mascota</span>').css('color','red');
			$("#color_mascota").focus(function() { $("[name='sp-color']").hide(); });
		}else{
			$("#color_mascota").css('color','green');
			$("#color_mascota").parent('div').css('color','green');
			$("[name='sp-color']").hide();
		}
	});

	$("#date_from").blur(function(){
		if($("#date_from").val() == 0){		
			$("#date_from").parent('div').css('color','red');
			$("#date_from").after('<span name="sp-date_from">Por favor ingrese una fecha</span>').css('color','red');
			$("#date_from").focus(function() { $("[name='sp-date_from']").hide(); });
		}else{
			$("#date_from").css('color','green');
			$("#date_from").parent('div').css('color','green');
			$("[name='sp-date_from']").hide();
		}
	});

	$("#genero_mascota").blur(function(){
		console.log($("#genero_mascota").val());
		if($("#genero_mascota").val().length == 0){		
			$("#genero_mascota").parent('div').css('color','red');
			$("#genero_mascota").after('<span name="sp-genero_mascota">Seleccione una opcion por favor</span>').css('color','red');
			$("#genero_mascota").focus(function() { $("[name='sp-genero_mascota']").hide(); });
		}else{
			$("#genero_mascota").css('color','green');
			$("#genero_mascota").parent('div').css('color','green');
			$("[name='sp-genero_mascota']").hide();
		}
	});

	$('.km-opcion').on('click', function(e) {
	
		$('#select_1').on('click', function(){
			$("#select_2, #select_3, #select_4").removeClass("km-opcionactivo");
		});
		$('#select_2').on('click', function(){
			$("#select_1, #select_3, #select_4").removeClass("km-opcionactivo");
		});
		
		$('#select_3').on('click', function(){
			$("#select_2, #select_1, #select_4").removeClass("km-opcionactivo");
		});	
		$('#select_4').on('click', function(){
			$("#select_2, #select_3, #select_1").removeClass("km-opcionactivo");
		});
		$(this).toggleClass('km-opcionactivo');
	    // $(this).children("input:checkbox").prop("checked", !$(this).children("input").prop("checked"));
	});

	$("#km-check-1").on('click', function() {
		if($("#km-check-1").val() == "0"){
			$("#km-check-1").attr('value','1');
		}else{
			$("#km-check-1").attr('value','0');
		}
	});

	$("#km-check-2").on('click', function() {
		if($("#km-check-2").val() == "0"){
			$("#km-check-2").attr('value','1');
		}else{
			$("#km-check-2").attr('value','0');
		}
	});

	$("#km-check-3").on('click', function() {
		if($("#km-check-3").val() == "0"){
			$("#km-check-3").attr('value','1');
		}else{
			$("#km-check-3").attr('value','0');
		}
	});

	$("#km-check-4").on('click', function() {
		if($("#km-check-4").val() == "0"){
			$("#km-check-4").attr('value','1');
		}else{
			$("#km-check-4").attr('value','0');
		}
	});

	$(document).on("click", '.popup-registrarte-datos-mascota .km-btn-popup-registrarte-datos-mascota', function ( e ) {
		e.preventDefault();
		var valor;
		var sizes =[
            {'ID':0,'name':'Pequeñas','desc':'Menos de 25.4cm'},
            {'ID':1,'name':'Medianas','desc':'Más de 25.4cm y menos de 50.8cm'},
            {'ID':2,'name':'Grandes','desc':'Más de 50.8cm y menos de 76.2cm'},
            {'ID':3,'name':'Gigantes','desc':'Más de 76.2cm'}
        ];
    	
		if ($("#select_1").hasClass("km-opcionactivo")) {
			valor = sizes[0].name+" "+sizes[0].desc;
			
		}else if($("#select_2").hasClass("km-opcionactivo")){
			valor = sizes[1].name+" "+sizes[1].desc;
		} else if ($("#select_3").hasClass("km-opcionactivo")){
			valor = sizes[2].name+" "+sizes[2].desc;
		}else if ($("#select_4").hasClass("km-opcionactivo")){
			valor = sizes[3].name+" "+sizes[3].desc;
		}else{
			console.log("La variable Valor esta vacia");
		}

		var nombre_mascota = $("#nombre_mascota").val(),
			tipo_mascota =$("#tipo_mascota").val(),
			raza_mascota = $("#raza_mascota").val(),
			color_mascota = $("#color_mascota").val(),
			date_from = $("#date_from").val(),
			genero_mascota = $("#genero_mascota").val(),
			tamano_mascota = valor,
			pet_sterilized = $("#km-check-1").val(),
			pet_sociable = $("#km-check-2").val(),
			aggresive_humans = $("#km-check-3").val(),
			aggresive_pets = $("#km-check-4").val();
	
		var campos_pet =[nombre_mascota,tipo_mascota,raza_mascota,color_mascota,
					date_from,genero_mascota,tamano_mascota,pet_sterilized,
					pet_sociable,aggresive_humans,aggresive_pets];
		if (nombre_mascota != "" && tipo_mascota != "" && raza_mascota != "" && color_mascota !="" 
        	&& date_from != "" && genero_mascota != "" && tamano_mascota != "") {
        		$(".popup-registrarte-datos-mascota").hide();
				$(".popup-registrarte-final").fadeIn("fast");
        		var datos = {
		      		'name_pet': campos_pet[0],
		            'type_pet': campos_pet[1],
		            'race_pet': campos_pet[2],
		            'colour_pet': campos_pet[3],
		            'date_birth': campos_pet[4],
		            'gender_pet': campos_pet[5],
		            'size_pet': campos_pet[6],
		            'pet_sterilized': campos_pet[7],
		            'pet_sociable': campos_pet[8],
		            'aggresive_humans': campos_pet[9],
		            'aggresive_pets': campos_pet[10],
		            'userid': globalData
		        };
		    // console.log(datos);  
		    getGlobalData('/procesos/login/registro_pet.php','post', datos);
        }else {
        	alert("Revise sus datos por favor, debe llenar todos los campos");
        }
	});
});

function listarAjax() {
	__ajax(HOME+"/procesos/login/mascota.php", "")
	.done(function(info){
		$('#raza_mascota').html(info).fadeIn();
	});
}

function __ajax(url, data){
	var ajax = $.ajax({
		"method": "POST",
		"url": url,
		"data": data
	})
	return ajax;
}

function getGlobalData(url,method, datos){
	return $.ajax({
		data: datos,
		type: method,
		url: HOME+url,
		async:false,
		success: function(data){
            $("#guardando").html("Este dato se guardo "+data);
            $("#guardando").css('color','blue');
			return data;
		}
	}).responseText;
}

$('[data-charset]').on({
    keypress : function(e){
            var tipo= $(this).attr('data-charset');
            if(tipo!='undefined' || tipo!=''){
                var cadena = "";

                if(tipo.indexOf('alf')>-1 ){ cadena = cadena + "abcdefghijklmnopqrstuvwxyzáéíóúñüÁÉÍÓÚÑÜ"; }
                if(tipo.indexOf('xlf')>-1 ){ cadena = cadena + "abcdefghijklmnopqrstuvwxyzáéíóúñüÁÉÍÓÚÑÜ "; }
                if(tipo.indexOf('num')>-1 ){ cadena = cadena + "1234567890"; }
                if(tipo.indexOf('cur')>-1 ){ cadena = cadena + "1234567890,."; }
                if(tipo.indexOf('esp')>-1 ){ cadena = cadena + "-_.$%&@,/()"; }
                if(tipo.indexOf('cor')>-1 ){ cadena = cadena + "@"; }
                if(tipo.indexOf('rif')>-1 ){ cadena = cadena + "vjegi"; }

                var key = e.which,
                    keye = e.keyCode,
                    tecla = String.fromCharCode(key).toLowerCase(),
                    letras = cadena;
                if(letras.indexOf(tecla)==-1 && keye!=9&& (key==37 || keye!=37)&& (keye!=39 || key==39) && keye!=8 && (keye!=46 || key==46) || key==161){
                    e.preventDefault();
                }
            }   
        }
	});



// POPUP INICIAR SESIÓN
	$(document).on("click", '.popup-iniciar-sesion-1 .km-btn-contraseña-olvidada', function ( e ) {
		e.preventDefault();

		$(".popup-iniciar-sesion-1").hide();
		$(".popup-olvidaste-contrasena").fadeIn("fast");
	});
	$(document).on("click", '.popup-registrarte-1 .km-btn-popup-registrarte-1', function ( e ) {
		e.preventDefault();

		$(".popup-registrarte-1").hide();
		$(".popup-registrarte-nuevo-correo").fadeIn("fast");
	});
// FIN POPUP INICIAR SESIÓN

function cargaImagen(evt){
	var files = evt.target.files;

	// obtenemos la imagen del campo file
	for (var i = 0, f; f = files[i]; i++) {         
           //Solo admitimos imágenes.
           if (!f.type.match('image.*')) {
                continue;
           }
		 var reader = new FileReader();

		 reader.onload = (function(theFile){
		 	return function(e){
		 	//Creamos la imagen.
		 	$("#km-datos-foto").css("background-image", "url("+e.target.result+")");
		 	$("#km-datos-foto").addClass("img-circle");
		 	//document.getElementById("list").innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
		 	};
		 })(f);

		 reader.readAsDataURL(f);
	}
}
