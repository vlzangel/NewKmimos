jQuery( document ).ready(function() {
	jQuery("#close_login").on("click", function(e){
        close_login_modal();
    });

    jQuery("#login").on("click", function(e){
        show_login_modal("login");
    });

	jQuery("#login_movil").on("click", function(e){
        show_login_modal("login");
    });

	jQuery("#recuperar").on("click", function(e){
        show_login_modal("recuperar");
    });

	jQuery("#login_submit").on("click", function(e){
		logear();
		e.preventDefault();
    });

    jQuery("#form_login").submit(function(e){ 
    	logear(); 
   	});
});

function logear(){
    jQuery.post( 
        HOME+"/procesos/login/login.php", 
        {
            usu: jQuery("#form_login #usuario").val(),
            clv: jQuery("#form_login #clave").val()
        },
        function( data ) {
            if( data.login ){
                location.reload();
            }else{
                alert( data.mes );
            }
        },
        "json"
    );
}

function show_login_modal(seccion){
	switch(seccion){
        case "login":
            jQuery("#modal_login form").css("display", "none");
            jQuery("#form_login").css("display", "block");
            jQuery("#modal_login").css("display", "table");
        break;
		case "servicios":
			jQuery("#modal_servicio").css("display", "table");
		break;
		case "recuperar":
			jQuery(".modal_login form").css("display", "none");
			jQuery("#form_recuperar").css("display", "block");
            jQuery("#modal_login").css("display", "table");
		break;
	}
}

function close_login_modal(){
    jQuery(".modal_login").hide();
}

function postJSON(FORM, URL, ANTES, RESPUESTA){
	jQuery("#"+FORM).submit(function( event ) {
	  	event.preventDefault();
        if( validarAll(FORM) ){
            ANTES();
            // jQuery.post(URL, jQuery("#"+FORM).serialize(), RESPUESTA, 'json');
            jQuery.post(URL, jQuery("#"+FORM).serialize(), RESPUESTA);
        }
	});
}

function subirImg(evt){
	var files = evt.target.files;
    var padre = jQuery(this).parent().parent();
    getRealMime(this.files[0]).then(function(MIME){
        if( MIME.match("image.*") ){
            padre.children('.vlz_img_portada_cargando').css("display", "block");
            var reader = new FileReader();
            reader.onload = (function(theFile) {
                return function(e) {
                    redimencionar(e.target.result, function(img_reducida){
                        //var img_pre = Cookies.get("img_temp");
                        jQuery.post( RUTA_IMGS+"/procesar.php", {img: img_reducida, previa: ""}, function( url ) {
                            //Cookies.set("img_temp", url);
                            padre.children('.vlz_img_portada_fondo').css("background-image", "url("+RUTA_IMGS+"/Temp/"+url+")");
                            padre.children('.vlz_img_portada_normal').css("background-image", "url("+RUTA_IMGS+"/Temp/"+url+")");
                            padre.children('.vlz_img_portada_cargando').css("display", "none");
                            padre.siblings('.vlz_img_portada_valor').val(url);
                            padre.children('.vlz_cambiar_portada').children('input').val("");
                        });
                    });      
                };
           })(files[0]);
           reader.readAsDataURL(files[0]);
        }else{
            padre.children('.vlz_cambiar_portada').children('input').val("");
            padre.children('.vlz_img_portada_cargando').css("display", "none");
            alert("Solo se permiten imagenes");
        }
    }).catch(function(error){
        padre.children('.vlz_cambiar_portada').children('input').val("");
        padre.children('.vlz_img_portada_cargando').css("display", "none");
        alert("Solo se permiten imagenes");
    });  	
}

function getRealMime(file) {
    return new Promise((resolve, reject) => {
        if (window.FileReader && window.Blob) {
            let slice = file.slice(0, 4);
            let reader = new FileReader();
          
            reader.onload = () => {
                let buffer = reader.result;
                let view = new DataView(buffer);
                let signature = view.getUint32(0, false).toString(16);
                let mime = 'unknown';

                switch ( String(signature).toLowerCase() ) {
                    case "89504e47":
                        mime = "image/png";
                    break;
                    case "47494638":
                        mime = "image/gif";
                    break;
                    case "ffd8ffe0":
                        mime = "image/jpeg";
                    break;
                    case "ffd8ffe1":
                        mime = "image/jpeg";
                    break;
                    case "ffd8ffe2":
                        mime = "image/jpeg";
                    break;
                    case "ffd8ffe3":
                        mime = "image/jpeg";
                    break;
                    case "ffd8ffe8":
                        mime = "image/jpeg";
                    break;
                }

                resolve(mime);

            }
            reader.readAsArrayBuffer(slice);
        } else {
            reject(new Error('Usa un navegador moderno para una mejor experiencia'));
        }
    });
}

function initImg(id){
    document.getElementById(id).addEventListener("change", subirImg, false);
}  


/* Procesado de imagenes */

function d(s){ return jQuery(s)[0].outerHTML; }
function c(i){
   var e = document.getElementById(i);
   if(e && e.getContext){
      var c = e.getContext('2d');
      if(c){
         return c;
      }
   }
   return false;
}

function contenedor_temp(){
    if( jQuery("#kmimos_redimencionar_imagenes").html() == undefined ){
        var img = jQuery("<img>", {
            id: "kmimos_img_temp"
        })[0].outerHTML;

        var cont_canvas = jQuery("<span>", {
            id: "kmimos_canvas_temp"
        })[0].outerHTML

        var cont_general = jQuery("<div>", {
            id: "kmimos_redimencionar_imagenes",
            html: cont_canvas+img,
            style: "display: none;"
        })[0].outerHTML;

        return jQuery("body").append(cont_general);
    }else{
        var img = jQuery("<img>", {
            id: "kmimos_img_temp"
        })[0].outerHTML;

        var cont_canvas = jQuery("<span>", {
            id: "kmimos_canvas_temp"
        })[0].outerHTML

        var cont_general = jQuery("<div>", {
            id: "kmimos_redimencionar_imagenes",
            html: cont_canvas+img,
            style: "display: none;"
        })[0].outerHTML;

        jQuery("#kmimos_redimencionar_imagenes").html(cont_general);
    }
}

function rotar(){
    
}

function redimencionar(IMG_CACHE, CB){
    contenedor_temp();
    var ximg = new Image();
    ximg.src = IMG_CACHE;

    ximg.onload = function(){
        jQuery("#kmimos_redimencionar_imagenes #kmimos_img_temp").attr("src", ximg.src);
        var rxi = jQuery("#kmimos_redimencionar_imagenes #kmimos_img_temp")[0];

        var rw = rxi.width;
        var rh = rxi.height;

        var w = 800;
        var h = 600;

        if( rw > rh ){
            h = Math.round( ( rh * w ) / rw );
        }else{
            w = Math.round( ( rw * h ) / rh );
        }
      
        CA = d("<canvas id='kmimos_canvas' width='"+w+"' height='"+h+"'>");
        jQuery("#kmimos_redimencionar_imagenes #kmimos_canvas_temp").html(CA);
        CA = jQuery("#kmimos_redimencionar_imagenes #kmimos_canvas_temp #kmimos_canvas");

        CTX = c("kmimos_canvas");
        if(CTX){
            CTX.drawImage(ximg, 0, 0, w, h);
            CB( CA[ 0 ].toDataURL("image/jpeg") );
        }else{
            return false;
        }
    }
}

/*  Validaciones */

function validar(id){
    var e = jQuery("#"+id);
    var validaciones = String(e.attr("data-valid")).split(",");

    var error = false;

    jQuery.each(validaciones, function( index, value ) {
        var validacion = value.split(":");
        switch(validacion[0]){
            case "requerid":
                if( e.val() == "" ){ error = true; break; }
            break;
            case "min":
                if( e.val().length < validacion[1] ){ error = true; break; }
            break;
            case "max":
                if( e.val().length > validacion[1] ){ error = true; break; }
            break;
            case "equalTo":
                if( e.val() != jQuery("#"+validacion[1]).val() ){ 
                    error = true; 
                    aplicar_error(validacion[1], true);
                    break; 
                }else{
                    aplicar_error(validacion[1], false);
                }
            break;
        }
    });

    aplicar_error(id, error);

    return error;
}

function pre_validar(elemento){
    if( elemento.attr("data-valid") != undefined ){
        var error = jQuery("<div class='no_error' id='error_"+( elemento.attr('id') )+"' data-id='"+( elemento.attr('id') )+"'></div>");
        var txt = elemento.attr("data-title");
        if( txt == "" || txt == undefined ){ txt = "Completa este campo."; }
        error.html( txt );
        elemento.parent().append( error );

        elemento.on("keyup", function(event){
            validar(event.target.id);
        }); 
        elemento.on("change", function(event){
            validar(event.target.id);
        }); 
            
    } 
}

function aplicar_error(id, error){
    if ( error  ) {
        if( jQuery("#error_"+id).hasClass( "no_error" ) ){
            jQuery("#error_"+id).removeClass("no_error");
            jQuery("#error_"+id).addClass("error");
        } 
    } else {
        if( jQuery("#error_"+id).hasClass( "error" ) ){
            jQuery("#error_"+id).removeClass("error");
            jQuery("#error_"+id).addClass("no_error");
        }
    }
}

function validarAll(Form){

    var submit = true;
    jQuery( "#"+Form+" [data-valid]" ).each(function( index ) {
        if( validar( jQuery( this ).attr("id") ) ){
            submit = false;
        }
    });

    if(!submit){
        var primer_error = ""; var z = true;
        jQuery( ".error" ).each(function() {
            if( jQuery( this ).css( "display" ) == "block" ){
                if( z ){
                    primer_error = "#"+jQuery( this ).attr("data-id"); z = false;
                }
            }
        });

        jQuery('html, body').animate({ scrollTop: jQuery(primer_error).offset().top-130 }, 2000);
    }

    return submit;
}