var table; var fotos_array = []; var indices = 0;
jQuery(document).ready(function() {

	jQuery("#fotos").on("change", function(e){
		// if( fotos_array.length < 6 ){
			jQuery.each(e.target.files, function(i, d){
				getRealMime(d).then(function(MIME){
			        if( MIME.match("image.*") ){
			            var reader = new FileReader();
			            reader.onload = (function(theFile) {
			                return function(e) {
			                    redimencionar(e.target.result, function(img_reducida){
			                    	// if( fotos_array.length < 6 ){
			                    		var HTML = '<div id="foto_'+indices+'" style="background-image: url('+img_reducida+');"> <i class="fa fa-times" onclick="removeFoto(jQuery(this))" data-index="'+indices+'" ></i> </div>';
			                    		jQuery(".galeria_container").append( HTML );
			                    		fotos_array.push( [indices, img_reducida] );
			                    		indices++;
			                    	// }
			                    	// jQuery(".galeria_container").css("display", "block");
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
		// }
	});

    jQuery("#close_modal").on("click", function(e){
        cerrar(e);
    });
 
    jQuery("#form").on("submit", function(e){
		e.preventDefault();
		if( !jQuery("#submit").hasClass("disable") ){
    		jQuery("#submit").addClass("disable");
			var confirmed = confirm("Esta seguro de enviar el correo.?");
	    	if (confirmed == true) {

	    		var ANEXOS = '';
	    		jQuery.each(fotos_array, function(i, d){
	    			ANEXOS += '<input type="hidden" name="anexos[]" value="'+d[1]+'" />';
	    		});
	    		jQuery("#imagenes_anexos").html( ANEXOS );
				
				jQuery.post(
					TEMA+'/admin/backend/'+MODULO+'/ajax/enviar.php',
					jQuery(this).serialize(),
					function(data){

						// console.log( data );

						/*
						jQuery(".modal > div > div").html( data.html );
						jQuery(".modal").css("display", "block");
						*/

						
						if( data.error == "" ){
							alert( data.respuesta );
						}else{
							alert( data.error );
						}
												

						jQuery("#submit").removeClass("disable");
					}, 'json'
				);

			}else{
				jQuery("#submit").removeClass("disable");
			}
		}
    });

	jQuery(".cuidadores_list").on("scroll", function() {
		var margen = 
			parseInt( jQuery("#lab_sug").height() ) +
			parseInt( jQuery("#camp_sug").height() );
	    var hTotal = parseInt( jQuery(".cuidadores_list > div").height() );
	    var scrollPosition =  parseInt( jQuery(".cuidadores_list").height() ) + parseInt( jQuery(".cuidadores_list").scrollTop() );
	    if ( ( hTotal <= scrollPosition ) && CARGAR_RESULTADOS ) {
    		CARGAR_RESULTADOS = false;
	        if( TOTAL_PAGE > (PAGE+1) ){
	        	PAGE = PAGE + 1;
	        	show_results();
	        	jQuery(".cargando_list").css("display", "block");
	        }else{
	        	jQuery(".cargando_list").css("display", "none");
	        }
	    }
	});

});

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

function abrir_link(e){
	init_modal({
		"titulo": e.attr("data-titulo"),
		"modulo": "seguimiento",
		"modal": e.attr("data-modal"),
		"info": {
			"ID": e.attr("data-id")
		}
	});
}

function buscar( campo ){
	jQuery(".cargando_list").css("display", "block");
	jQuery.post(
		HOME+"/procesos/busqueda/buscar.php",
		jQuery("#form").serialize(),
		function(respuesta){
			// console.log( respuesta );
			if( respuesta != false ){
				TOTAL_PAGE = Math.ceil(respuesta.length/10);
			}
			PAGE = 0;
			jQuery(".cuidadores_list").scrollTop(0);
			show_results();
		}, 'json'
	);
}

var PAGE = 0;
var TOTAL_PAGE = 0;

function show_results(){
	jQuery.post(
		HOME+"/NEW/resultados_admin.php",
		{ 
			page: PAGE,
			sugerencias: jQuery("#sugerencias").val()
		},
		function(html){
			if( PAGE == 0 ){
				jQuery(".cuidadores_list > div").html( html );
			}else{
				jQuery(".cuidadores_list > div").append( html );
			}
			jQuery('html, body').animate({ scrollTop: 0 }, 1000);
			CARGAR_RESULTADOS = true;
			jQuery(".cargando_list").css("display", "none");
		}
	);
}

var sugerencias = [];
var sugerencias_names = [];

function seleccionar_sugerencia(_this){
	console.log( _this.prop("checked") );
	if( _this.prop("checked") !== true ){
		quitar_sugerencia( _this.data("id") );
		jQuery("#sugerencias").val( sugerencias.join(",") );
		jQuery("#sugerencias_txt").val( sugerencias_names.join(" - ") );
	}else{
		if( sugerencias.length < 4 ){
			sugerencias.push( _this.data("id") );
			sugerencias_names.push( _this.data("name") );

			console.log( sugerencias.join(",") );

			jQuery("#sugerencias_ids").val( sugerencias.join(",") );
			jQuery("#sugerencias_txt").val( sugerencias_names.join(" - ") );
		}else{
			_this.prop("checked", false);
			alert("Solo se permite un máximo de 4 sugerencias");
		}
	}
}

function quitar_sugerencia(id){
	var temp_1 = [];
	var temp_2 = [];
	jQuery.each(sugerencias, function(i, d){
		if( d != id ){
			temp_1.push( sugerencias[i] );
			temp_2.push( sugerencias_names[i] );
		}
	});

	sugerencias = temp_1;
	sugerencias_names = temp_2;
}








 