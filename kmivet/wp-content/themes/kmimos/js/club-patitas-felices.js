var table;
var URL_SALIR;
jQuery(document).ready( function (){
	
	jQuery("[data-atras]").on('click', function(){
		mostrar_popup_sesion("popup-iniciar-sesion-1");
	});

	// jQuery("[login-group]").on('click', function(){
	// 	mostrar_popup_sesion("popup-iniciar-sesion-1");
	// });

	jQuery('#olvidaste-contrasena').on('click', function(){
		mostrar_popup_sesion("popup-olvidaste-contrasena");
	})

	jQuery('#show-iniciar-sesion').on('click', function(){
		mostrar_popup_sesion("popup-iniciar-sesion-1");
	})

	jQuery('#registrar-cpf').on('click', function(){
		jQuery('#form-registro input[name="nombre"]').focus();
		jQuery('html, body').animate({ scrollTop: jQuery('#form-registro').offset().top-140 }, 2000); 
		jQuery(document).scrollTop(0);
	});


	jQuery('#logo-white').attr('src', jQuery('#logo-black').attr('src') );

	jQuery('#compartir_now').on('click', function(e){
		var obj = jQuery('#redes-sociales');

		if( obj.hasClass('redes-sociales-hidden') ){
			obj.removeClass('redes-sociales-hidden');
			obj.css('display', 'none');
		}

		if( obj.css('display') == 'none' ){
			obj.css('display', 'block');
		}else{
			obj.css('display', 'none');
		}
	});

	jQuery('#form-registro').on('submit', function(e){
		e.preventDefault();
		var btn = jQuery('#form-registro button[type="submit"]');
		if( !btn.hasClass('disabled') ){
			btn.addClass('disabled');
			btn.html('<i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Procesando');
			jQuery.post(
			  HOME+'procesos/clubPatitasFelices/registro-usuario.php',
			  jQuery(this).serialize(),
			  function(d){
			       	console.log(d);
					if(d.sts == 1){
						evento_google_kmimos('CPF_Registro');
						location.href = RAIZ+'club-patitas-felices/compartir';
					}else{
						btn.html('Genera tu código aquí');
						btn.removeClass('disabled');
						mostrar_popup_sesion("popup-msg-registrado");
				   	}
				}, 
				'json'
			);
		}
	});

	jQuery('#form-registro button[type="submit"]').on('click', function(){
		club_validar();
	});
	
	jQuery('#form-registro input').on('blur', function(){
		club_validar();
	});
	
	total_generado();
	menuClub();

});

function mostrar_popup_sesion( id ){
	jQuery("[login-group]").addClass("popuphide");
	jQuery("[login-group]").css("display", 'none');
	jQuery("."+id).removeClass("popuphide");
	jQuery("."+id).css("display", 'block');
	jQuery("#popup-iniciar-sesion").modal("show");
}

function club_validar(){
	jQuery.each( jQuery('#form-registro input'), function(i){
		var input = jQuery(this);
		input.removeClass('info-error');
		if( input.val() == '' ){
			input.addClass('info-error');
		}
	});
}

function total_generado(){

	jQuery.post(
        HOME+'/procesos/clubPatitasFelices/ajax/creditos.php',
        {},
        function(d){
        	jQuery('#total_creditos').html( d.total );
	    }, 'json'
	);
}

function menuClub(){
	var menu = jQuery('nav.navbar');
	menu.css('background', 'transparent');
    menu.css('border', '0px');
    menu.css('box-shadow', '0px 0px 0px 0px');
    menu.css('min-height', '0px');
    menu.css('padding-top', '4px');

    var con = menu.find('.container');
    con.css('padding','0px');

    // URL Cerrar Sesion
    console.log( jQuery('[menu-id="salir"]').attr('href') );
    jQuery('[menu-id="salir"]').attr('href', URL_SALIR);
    console.log( jQuery('[menu-id="salir"]').attr('href') );
}

function downloadPDF(){
	jQuery.post(
        HOME+'procesos/clubPatitasFelices/ajax/pdf.php',
        {},
        function(d){
        	console.log(d);
	    }
	);
}

function loadTabla(){
 	 
	table = jQuery('#example').DataTable();
	table.destroy();

    table = jQuery('#example').DataTable({
    	"language": {
			"emptyTable":			"No hay datos disponibles en la tabla.",
			"info":		   			"Del _START_ al _END_ de _TOTAL_ ",
			"infoEmpty":			"Mostrando 0 registros de un total de 0.",
			"infoFiltered":			"(filtrados de un total de _MAX_ registros)",
			"infoPostFix":			" (actualizados)",
			"lengthMenu":			"Mostrar _MENU_ registros",
			"loadingRecords":		"Cargando...",
			"processing":			"Procesando...",
			"search":				"Buscar:",
			"searchPlaceholder":	"Dato para buscar",
			"zeroRecords":			"No se han encontrado coincidencias.",
			"paginate": {
				"first":			"Primera",
				"last":				"Última",
				"next":				"Siguiente",
				"previous":			"Anterior"
			},
			"aria": {
				"sortAscending":	"Ordenación ascendente",
				"sortDescending":	"Ordenación descendente"
			}
		},
		"dom": '<B><f><t>ip',
		"buttons": [
			{
			  extend: "csv",
			  className: "btn-sm"
			},
			{
			  extend: "excelHtml5",
			  className: "btn-sm"
			},
        ],
        "scrollX": true,
        "ajax": {
            "url": HOME+'/procesos/clubPatitasFelices/ajax/creditos.php',
            "type": "POST"
        }
	});
}
 
jQuery(window).scroll(function() {
	jQuery('#logo-white').attr('src', jQuery('#logo-black').attr('src') );
	if( jQuery(window).width() < 700 ){
		if( jQuery(this).scrollTop() > 10 ){
			jQuery('nav').css('position', 'relative');
		}else{
			jQuery('nav').css('position', 'fixed');
		}
	}
});