function vlz_galeria_ver(url){
	// jQuery('.vlz_modal_galeria_interna').css('background-image', 'url('+url+')');
	// jQuery('.vlz_modal_galeria').css('display', 'table');
}
function vlz_galeria_cerrar(){
	// jQuery('.vlz_modal_galeria').css('display', 'none');
	// jQuery('.vlz_modal_galeria_interna').css('background-image', '');
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
		icon: "https://www.kmimos.com.mx/wp-content/themes/pointfinder/vlz/img/pin.png"
	});
}

(function(d, s){
	map = d.createElement(s), e = d.getElementsByTagName(s)[0];
	map.async=!0;
	map.setAttribute("charset","utf-8");
	map.src="//maps.googleapis.com/maps/api/js?v=3&key=AIzaSyD-xrN3-wUMmJ6u2pY_QEQtpMYquGc70F8&callback=initMap";
	map.type="text/javascript";
	e.parentNode.insertBefore(map, e);
})(document,"script");

var comentarios_cuidador = [];

function comentarios(pagina = 0){
	var comentario = '';
	jQuery.each(comentarios_cuidador, function( pagina, cuidador ) {
		comentario += '	<div class="km-comentario">';
		comentario += '			<div class="row">';
		comentario += '				<div class="col-xs-2">';
		comentario += '					<div class="km-foto-comentario-cuidador" style="background-image: url('+comentarios_cuidador[pagina]["img"]+');"></div>';
		comentario += '				</div>';
		comentario += '				<div class="col-xs-10">';
		comentario += '					<p>'+comentarios_cuidador[pagina]["contenido"]+'</p>';
		comentario += '					<p class="km-tit-ficha">'+comentarios_cuidador[pagina]["cliente"]+'</p>';
		comentario += '					<p class="km-fecha-comentario">'+comentarios_cuidador[pagina]["fecha"]+'</p>';
		comentario += '				</div>';
		comentario += '			</div>';
		comentario += '			<div class="row km-review-categoria">';
		comentario += '				<div class="col-xs-6 col-md-3">';
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
	});

	//console.log(comentario);

	jQuery("#comentarios_box").html( comentario );
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

jQuery( document ).ready(function() {
	jQuery.post(
		HOME+"/procesos/cuidador/comentarios.php",
		{
			servicio: SERVICIO_ID
		}, function(data){
			comentarios_cuidador = data;
			comentarios();
		}, "json"
	);

	function perfil_login(accion){
		//jQuery.cookie("POST_LOGIN", accion);
		jQuery("#login").click();
	}

	jQuery( document ).ready(function() {
	 //  	var POST_LOGIN = jQuery.cookie("POST_LOGIN");
		// if( POST_LOGIN != undefined ){
		// 	jQuery.removeCookie("POST_LOGIN");
		// 	document.getElementById(POST_LOGIN).click();
		// }
	});

    jQuery("#btn_reservar").on("click", function(e){
        perfil_login();
    });

    jQuery("#servicios").on("click", function(e){
        show_login_modal("servicios");
    });

});