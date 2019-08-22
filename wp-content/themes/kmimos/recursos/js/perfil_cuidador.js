jQuery( document ).ready(function() {

	jQuery("#ocultar_msg").on('click', function(e){
		e.preventDefault();
		jQuery(".modal_msg").css("display", "none");
	});

	GetComments();
	jQuery(".km-btn-comentario").on("click", function(e){
		jQuery('.modal_info_comentar').css("display", "block");
		jQuery('.modal_comentario_enviado').css("display", "none");
		jQuery('.comments').css("display", "none");
		jQuery('.BoxComment').slideDown();
	});
	jQuery("#comentar").on("click", function(e){
		jQuery('.modal_info_comentar').css("display", "none");
		jQuery('.comments').slideDown();
	});
	jQuery(".servicio_item").on("click", function(e){
		if( jQuery(this).parent().hasClass("servicio_visible") ){
			jQuery(".servicio_item_box").removeClass("servicio_visible");
		}else{
			jQuery(".servicio_item_box").removeClass("servicio_visible");
			jQuery(this).parent().addClass("servicio_visible");
		}
	});

	jQuery(window).scroll( function () {
		if( parseInt( jQuery("body").width() ) > 768 ){
			if( (jQuery(window).scrollTop()+70) >= jQuery(".pc_seccion_2_der")[0].offsetTop ){
		    	jQuery(".pc_scroll_der").addClass("pc_scroll_fixed");
		    }else{
		    	jQuery(".pc_scroll_der").removeClass("pc_scroll_fixed");
		    }
	    }
    });

    jQuery("#servicios").on("click", function(e){
        show_login_modal("servicios");
    });

    jQuery(".pc_img").on("click", function(e){
        showImgGaleria( jQuery(this) );
    });

    jQuery("#cerrar_galeria").on("click", function(e){
        hideImgGaleria();
    });

    jQuery("#cerrar_galeria_2").on("click", function(e){
        hideImgGaleria();
    });

    jQuery(".galeria_container_fixed").on("click", function(e){
        hideImgGaleria();
    });

    jQuery("#btn_reservar_fixed").on("click", function(e){
        jQuery("#btn_reservar").click();
    });

    window.onload = function(){
    	CargarGaleria();
    	/*
		jQuery(".pc_galeria_box").html(GALERIA);
		jQuery(".pc_galeria_item").on("click", function(e){
			if( jQuery(this).hasClass("selected") ){
				jQuery(".pc_galeria_item").removeClass("selected");
			}else{
				jQuery(".pc_galeria_item").removeClass("selected");
				jQuery(this).addClass("selected");
				showImgGaleria( jQuery(this) );
			}
		});
		*/
    }

    jQuery(".ver_mas").on("click", function(e){
    	if( jQuery(".mas_info").html() == "..." ){
    		jQuery(".mas_info").html( jQuery(".mas_info").attr("data-info") );
    		jQuery(this).html("Ver menos");
    	}else{
    		jQuery(".mas_info").html( "..." );
    		jQuery(this).html("Ver más");
    	}
    });

});

/* GALERIA */

	function CargarGaleria(){
		var galeria_txt = "";
		jQuery.each(GALERIA, function( indice, foto ) {
			galeria_txt += "<div class='pc_galeria_item' data-img='"+RAIZ+"/wp-content/uploads/cuidadores/galerias"+foto+"'>";
			galeria_txt += 	"<div class='pc_galeria_img' style='background-image: url("+RAIZ+'/wp-content/uploads/cuidadores/galerias'+foto+");'></div>";
			galeria_txt += "</div>";
		});
		jQuery(".pc_galeria_box").html(galeria_txt);
		jQuery(".pc_galeria_item").unbind("click").bind("click", function(e){
			showImgGaleria( jQuery(this) );
		});
	}
	
	function showImgGaleria( _this ){
		var img = _this.attr("data-img");
		jQuery("body").css("overflow", "hidden");
		jQuery(".galeria_container_fixed img").attr("src", img);
		jQuery(".galeria_container_fixed").addClass("show_galeria");
	}
	
	function hideImgGaleria(){
		jQuery("body").css("overflow", "auto");
        jQuery(".galeria_container_fixed").removeClass("show_galeria");
	}

	function imgAnterior(_this){
		var actual = _this.parent().attr("data-actual");
		var total = _this.parent().attr("data-total");
		var h = parseInt(_this.parent().attr("data-paso"));

		if( actual == 0 ){
			actual = total-h;
		}else{
			actual--;
		}
		if( actual == 0 ){
			_this.addClass("Ocultar_Flecha");
		}
		if( actual != total-h ){
			_this.parent().find(".Flecha_Derecha").removeClass("Ocultar_Flecha");
		}

		h = (100/h);

		_this.parent().attr("data-actual", actual);
		_this.parent().find(".pc_galeria_box").animate({left: "-"+(actual*h)+"%"});
	}

	function imgSiguiente(_this){
		var actual = _this.parent().attr("data-actual");
		var total = _this.parent().attr("data-total");
		var h = parseInt(_this.parent().attr("data-paso"));

		if( actual == total-h ){
			actual = 0;
		}else{
			actual++;
		}
		if( actual == total-h ){
			_this.addClass("Ocultar_Flecha");
		}
		if( actual != 0 ){
			_this.parent().find(".Flecha_Izquierda").removeClass("Ocultar_Flecha");
		}

		h = (100/h);

		_this.parent().attr("data-actual", actual);
		_this.parent().find(".pc_galeria_box").animate({left: "-"+(actual*h)+"%"});
	}

var map_cuidador;
function initMap() {
	var latitud = lat;
	var longitud = lng;
	map_cuidador = new google.maps.Map(document.getElementById('mapa'), {
		zoom: 15,
		center:  new google.maps.LatLng(latitud, longitud), 
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		scrollwheel: false
	});
	marker = new google.maps.Marker({
		map: map_cuidador,
		draggable: false,
		animation: google.maps.Animation.DROP,
		position: new google.maps.LatLng(latitud, longitud),
		icon: HOME+"/js/images/n1.png"
	});

	map_cuidador_movil = new google.maps.Map(document.getElementById('mapa_movil'), {
		zoom: 15,
		center:  new google.maps.LatLng(latitud, longitud), 
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		scrollwheel: false
	});
	marker = new google.maps.Marker({
		map: map_cuidador_movil,
		draggable: false,
		animation: google.maps.Animation.DROP,
		position: new google.maps.LatLng(latitud, longitud),
		icon: HOME+"/js/images/n1.png"
	});
}

(function(d, s){
	map = d.createElement(s), e = d.getElementsByTagName(s)[0];
	map.async=!0;
	map.setAttribute("charset","utf-8");
	map.src="//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCLvX3VwG4eb4KjiCqKgYx1NfBTAuhVHmY&callback=initMap";
	map.type="text/javascript";
	e.parentNode.insertBefore(map, e);
})(document,"script");

(function(d, s){
    $ = d.createElement(s), e = d.getElementsByTagName(s)[0];
    $.async=!0;
    $.setAttribute('charset','utf-8');
    $.src='//www.google.com/recaptcha/api.js?hl=es';
    $.type='text/javascript';
    e.parentNode.insertBefore($, e)
})(document, 'script');

var comentarios_cuidador = [];
function comentarios(pagina = 0){
	var bond_total=0;
	var bond_porcent=0;
	var comentario = '';
	var cantidad_valoraciones = 0;
	jQuery.each(comentarios_cuidador, function( pagina, cuidador ) {
		var bond_testimony = 0;
		if(
			comentarios_cuidador[pagina]["cuidado"] 		> 0 &&
			comentarios_cuidador[pagina]["puntualidad"] 	> 0 &&
			comentarios_cuidador[pagina]["limpieza"] 		> 0 &&
			comentarios_cuidador[pagina]["confianza"] 		> 0
		){
			comentario += '	<div class="km-comentario">';
			comentario += '		<div class="row">';
			comentario += '			<div class="col-xs-2">';
			comentario += '				<div class="km-foto-comentario-cuidador" style="background-image: url('+comentarios_cuidador[pagina]["img"]+');"></div>';
			comentario += '			</div>';
			comentario += '			<div class="col-xs-9 pull-right">';
			comentario += '				<p class="km-tit-ficha">'+comentarios_cuidador[pagina]["cliente"]+'</p>';
			comentario += '				<p class="km-fecha-comentario">'+comentarios_cuidador[pagina]["fecha"]+'</p>';
			comentario += '			</div>';
			comentario += '		</div>';
			comentario += '		<div class="row">';
			comentario += '			<div class="col-md-12"><p>'+ comentarios_cuidador[pagina]["contenido"]+'</p></div>';
			comentario += '		</div>';
			comentario += '		<div class="row km-review-categoria">';
			comentario += '			<div class="col-xs-6 col-md-3">';
			comentario += '				<p>CUIDADO</p>';
			comentario += '				<div class="km-ranking">';
			comentario += 					get_huesitos(comentarios_cuidador[pagina]["cuidado"]);
			comentario += '				</div>';
			comentario += '			</div>';
			comentario += '			<div class="col-xs-6 col-md-3">';
			comentario += '				<p>PUNTUALIDAD</p>';
			comentario += '				<div class="km-ranking">';
			comentario += 					get_huesitos(comentarios_cuidador[pagina]["puntualidad"]);
			comentario += '				</div>';
			comentario += '			</div>';
			comentario += '			<div class="col-xs-6 col-md-3">';
			comentario += '				<p>LIMPIEZA</p>';
			comentario += '				<div class="km-ranking">';
			comentario += 					get_huesitos(comentarios_cuidador[pagina]["limpieza"]);
			comentario += '				</div>';
			comentario += '			</div>';
			comentario += '			<div class="col-xs-6 col-md-3">';
			comentario += '				<p>CONFIANZA</p>';
			comentario += '				<div class="km-ranking">';
			comentario += 					get_huesitos(comentarios_cuidador[pagina]["confianza"]);
			comentario += '				</div>';
			comentario += '			</div>';
			comentario += '		</div>';
			comentario += '	</div>';
			bond_testimony = bond_testimony+parseFloat(comentarios_cuidador[pagina]["confianza"]);
			bond_testimony = bond_testimony+parseFloat(comentarios_cuidador[pagina]["limpieza"]);
			bond_testimony = bond_testimony+parseFloat(comentarios_cuidador[pagina]["puntualidad"]);
			bond_testimony = bond_testimony+parseFloat(comentarios_cuidador[pagina]["cuidado"]);
			cantidad_valoraciones++;
			bond_total = bond_total+bond_testimony;
		}else{
			comentario += '	<div class="km-comentario">';
			comentario += '		<div class="row">';
			comentario += '			<div class="col-xs-2">';
			comentario += '				<div class="km-foto-comentario-cuidador" style="background-image: url('+comentarios_cuidador[pagina]["img"]+');"></div>';
			comentario += '			</div>';
			comentario += '			<div class="col-xs-9 pull-right">';
			comentario += '				<p class="km-tit-ficha">'+comentarios_cuidador[pagina]["cliente"]+'</p>';
			comentario += '				<p class="km-fecha-comentario">'+comentarios_cuidador[pagina]["fecha"]+'</p>';
			comentario += '			</div>';
			comentario += '		</div>';
			comentario += '		<div class="row">';
			comentario += '			<div class="col-md-12"><p>'+ comentarios_cuidador[pagina]["contenido"]+'</p></div>';
			comentario += '		</div>';
			comentario += '	</div>';
		}

	});

	if( bond_total > 0 ){
		bond_total=bond_total/(cantidad_valoraciones*4);
		bond_porcent=bond_total*(100/5);

		var bond = '<div class="km-ranking">';
			bond += get_huesitos(bond_total);
			bond += '</div>';

		jQuery("#comentarios_box").html( comentario );
		jQuery(".km-review .km-calificacion").html( comentarios_cuidador.length );
		jQuery(".km-review .km-calificacion-icono p").html( parseInt(bond_porcent)+'% Lo recomienda');
		jQuery(".km-review .km-calificacion-bond").html(bond);
	}else{
		var bond = '<div class="km-ranking">';
			bond += get_huesitos(bond_total);
			bond += '</div>';

		jQuery("#comentarios_box").html( comentario );
		jQuery(".km-review .km-calificacion").html( comentarios_cuidador.length );
		jQuery(".km-review .km-calificacion-icono p").html( bond_total+'% Lo recomienda');
		// jQuery(".km-review .km-calificacion-bond").html(bond);
	}
	
}

function get_huesitos(valor){
	var huesos = "";
	for (var i = 0; i < valor; i++) {
		huesos += '<a href="#" class="active"></a>';
	}
	for (var i = valor; i < 5; i++) {
		huesos += '<a href="#"></a>';
	}
	return huesos;
}