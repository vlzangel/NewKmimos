//////////////////////////////////
//////////////////////////////////
////////////////////////////////// HERRAMIENTAS MOVILES
//////////////////////////////////
//////////////////////////////////
var w = window.screen.width;
var urlActual = location.pathname;

if(w<769)
{
	//// PANEL DE USUARIO 
	///////////////////////////////////////
	///////////////////////////////////////

	$('body div#menu').delegate('#usuario', 'click', function(){

		if(this.className=='')
		{
			this.className='active'
			this.parentNode.className='activoMovil';
			$(this).parent().children('#panelUsuario').show();
			$('body #menu #menuOpen').attr('class', '');$('body div#menuMain').removeClass('active');
			$('body #menu #buscar').attr('class', '');$('body div#panelBuscar').removeClass('active');		
		}
		else{
			this.className='';
			this.parentNode.className='';
			$(this).parent().children('#panelUsuario').hide(); 
		}

		return false;
	});


}

function number_format(number, decimals, dec_point, thousands_sep) {

  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}

//// ABRIR MENU
///////////////////////////////////////
///////////////////////////////////////

function cerrar_menu(){
	$('body div#menu #menuOpen').removeClass('preActive');
	$('body div#menuMain, body div#menuMainSecundario, body div#menuMainTerciario').removeClass('active');
	$('body div#menuMain .item, body div#menuMainSecundario .item, body div#menuMainTerciario .item').removeClass('active');
}

$('body div#menu').delegate('#menuOpen','click', function(){

	if(!$(this).hasClass('preActive'))
	{
		$(this).addClass('preActive');
		$('body div#menuMain').addClass('active');
		// Cerrar "Buscar"
		$('body #menu #buscar').attr('class', '');$('body div#panelBuscar').removeClass('active');		
		if(w<769)
		{
			$('body #menu #usuario').attr('class', '');$('body div#panelUsuario').hide();$('body #menu').attr('class', '')
		}
		// Opacar Home si está activo

	}else{
		cerrar_menu();
		// ReActivar Boton Home si el sistema se encuenta en Home
	}

	return false;
});


//// ABRIR PANEL DE BUSQUEDA
///////////////////////////////////////
///////////////////////////////////////

$('body div#menu').delegate('#buscar','click', function(){

	if(this.className=='')
	{
		this.className='preActive';
		$('body div#panelBuscar').addClass('active');
		$('body #panelBuscar').find('#buscando').focus();
		// Cerrar Menu
		$('body #menu #menuOpen').attr('class', '');
		$('body div#menuMain, body div#menuMainSecundario, body div#menuMainTerciario').removeClass('active');	
		$('body div#menuMain, body div#menuMainSecundario, body div#menuMainTerciario').find('.item').removeClass('active');
		if(w<769)
		{
			$('body #menu #usuario').attr('class', '');$('body div#panelUsuario').hide();$('body #menu').attr('class', '')
		}
	}else{
		this.className='';
		$('body div#panelBuscar').removeClass('active');
	}

	return false;
});

// SELECCIONAR OPCIONES DE BUSQUEDA

$('body #panelBuscar #panelOpcionesBusqueda').delegate('.seleccion', 'click', function(){

	var id = this.id;

	// TODOS
	if(id==1){
		$('body #panelBuscar #panelOpcionesBusqueda .seleccion').removeClass('activa');
	}
	// LOS DEMAS
	else{
		$('body #panelBuscar #panelOpcionesBusqueda #1').removeClass('activa');
	}
	$(this).addClass('activa');

	return false;
});


//// CLICK EN ITEM DE MENU
///////////////////////////////////////
///////////////////////////////////////

$('body div#menuMain, body div#menuMainSecundario, body div#menuMainTerciario').delegate('.item','click', function(event){

	// ELEMENTO DE MENU QUE APERTURA UN SUBMENU (Div)
	if(this.tagName==='DIV'){

		var id = this.id;
		// ABRIR SUBMENU
		if($(this).hasClass('active')==false){

			// Desactivando los Hermanos
			$(this).parent().children('.item').removeClass('active');
			// Activando
			$(this).addClass('active');
			// Titulo Movil
			var tituloMovil = $(this).children('.txt').text();
			
			/*
				GENERAR SUBMENU
			*/
			function generar_submenu(items,nivel,tituloMovil){

				// Panel a abrir
				if(nivel==1){
					var panelAbrir = 'menuMainSecundario';
					// Cerrando el Panel Terceario porque se esta abriendo el Secundario.
					$('body div#menuMainTerciario').removeClass('active').html('');
				}
				if(nivel==2){var panelAbrir = 'menuMainTerciario';}


				// Añadiendo elementos a panel
				var add='';
				// Añadiendo retroceso si el sistema es Movil
				if(w<769)
				{
					add += '<div class="tituloMovilMenu" id="'+nivel+'"><div class="atrasMovilMenu">atr&aacute;s</div><div class="tituloMovilMenuTit"><img src="img/downMini.png"> '+tituloMovil+'</div></div>';
				}
				var l = items.length;
				var level, le;
				for(var i=0;i<l;i=i+1){
					le = '';
					if(items[i][3]==1){var le='level';}
					if(/.php$/.test(items[i][2])){

						add += '<a class="item '+le+'" href="'+items[i][2]+'"><span class="ico" style="background-image:url(img/'+items[i][0]+');"></span><span class="txt">'+items[i][1]+'</span></a>';	
					}else{
						add += '<div class="item next '+le+'" id="'+items[i][2]+'"><span class="ico" style="background-image:url(img/'+items[i][0]+');"></span><span class="txt">'+items[i][1]+'</span></div>';
					}
				}
				$('body div#'+panelAbrir).html(add);

				// Abriendo Panel
				$('body div#'+panelAbrir).addClass('active');	

				event.preventDefault();
			}
		}// CERRAR SUBMENU
		else{
			// Desactivo el boton
			$(this).removeClass('active');
			// Si es el primario, cierro el secundario y terciario
			if(this.parentNode.id=='menuMain'){
				$('body div#menuMainSecundario, body div#menuMainTerciario').removeClass('active').html('');
			}
			// Si es el secundario, cierro el terceario
			else{
				$('body div#menuMainTerciario ').removeClass('active').html('');	
			}
		}

		/*
			DEFINICION DE SUBMENU SEGUN SELECCION
		*/
		///// NIVEL 1
		if(id=='eventos'){generar_submenu(eventos,1,tituloMovil);}

		// RETROCEDIENDO EN MENU (MOVIL)
		if(w<769)
		{
			$('body').find('.tituloMovilMenu').delegate('.atrasMovilMenu', 'click', function(){
				var id = $(this).parent().attr('id');
				if(id=='1'){
					$('body div#menuMainSecundario').removeClass('active').html('');
					$('body div#menuMain .item').removeClass('active');
				}else{
					$('body div#menuMainTerciario').removeClass('active').html('');
					$('body div#menuMainSecundario .item').removeClass('active');
				}
			});
		}
		return false;
	}
});

//// CONFIRMACION ELIMINACION
///////////////////////////////////////
///////////////////////////////////////

function eliminar(url){

	var c = confirm("\u00bfEst\u00e1 seguro que desea Eliminar el item?");
	if(c!=false){
		location.href=url;
	}else{
		event.preventDefault();
		return false;
	}
}

//// CONFIRMACION EDICION
///////////////////////////////////////
///////////////////////////////////////

function editar(url){

	var c = confirm("\u00bfEst\u00e1 seguro que desea Editar el item?");
	if(c!=false){
		location.href=url;
	}else{
		event.preventDefault();
		return false;
	}
}


// DETECTANDO SCROLL EN SUBPAGINA PARA SOMBRA
if($('body div#menu div#menuOpen').hasClass('enter'))
{
	$(window).scroll(function(event) {
		if($(document).scrollTop()>10)
		{
			$('body div.tituloMain').addClass('active');
		}
		else{
			$('body div.tituloMain').removeClass('active');
		}
	});
}

// ACTIVACION GENERAL DE SELECT AL SELECCIONAR UNA OPTION
function selectores()
{
	$('body').delegate('select:not(".selector")','click change',function(){
        this.className="inputSelect active";
    });

    $('body').delegate('select:not(".selector")','blur',function(){
    	if(this.value===''){
        	this.className="inputSelect";
        }
    });
}
selectores();

// BORRAR
$('body').delegate('input.formBorrar', 'click', function(){

	$(this).parent().find('select').attr('class','inputSelect');

});


//// FILTRAR POR...
///////////////////////////////////////
///////////////////////////////////////

function filtrarPor(url_default,filtro,event){
	var urlActual = location.href;
	if(event.value==-1){
		var buscar = urlActual.indexOf('?');
		if(buscar!=-1)
		{
			// Es el primero despues del "?"
			var inicial = urlActual.indexOf('?'+filtro+'=');
			if(inicial!=-1){
				var separarValor1 = urlActual.split(filtro+'=');
				var separarValor2 = separarValor1[1].split('&');
				// Hay elementos adelante
				if(separarValor2.length!=1)
				{
					var postUrl='';
					for(var i = 1;i<separarValor2.length;i++){
						if(i!=1){postUrl+='&';}
						postUrl += separarValor2[i];
					}
					var urlNueva = separarValor1[0]+postUrl;
					location.href=urlNueva;
				}else{
					var urlNueva = separarValor1[0].split('?');
					urlNueva = urlNueva[0];
					location.href=urlNueva;
				}
			}
			// Hay elementos atras
			else{
				var separarValor1 = urlActual.split('&'+filtro+'=');
				var separarValor2 = separarValor1[1].split('&');
				// Hay elementos adelante
				if(separarValor2.length!=1)
				{
					var postUrl='';
					for(var i = 1;i<separarValor2.length;i++){
						if(i!=1){postUrl+='&';}
						postUrl += separarValor2[i];
					}
					var urlNueva = separarValor1[0]+'&'+postUrl;
					location.href=urlNueva;
				}else{
					var urlNueva = separarValor1[0];
					location.href=urlNueva;
				}
			}
		}
		else{location.href=url_default;}
	}else{
		// Buscar si hay otros filtros activos
		var buscar = urlActual.indexOf('?');
		if(buscar!=-1)
		{
			// Buscar si dentro de los Filtros Activos está este que se está seleccionando.
			var buscarFiltroSeleccionado = urlActual.indexOf(filtro+'=');
			if(buscarFiltroSeleccionado!=-1){
				var separarValor1 = urlActual.split(filtro+'=');
				var separarValor2 = separarValor1[1].split('&');
				if(separarValor2.length!=1)
				{
					var postUrl='';
					for(var i = 1;i<separarValor2.length;i++){
						postUrl += '&'+separarValor2[i];
					}
					var urlNueva = separarValor1[0]+filtro+'='+event.value+postUrl;
					location.href=urlNueva;
				}else{
					var urlNueva = separarValor1[0]+filtro+'='+event.value;					
					location.href=urlNueva;
				}
			}else{location.href=urlActual+'&'+filtro+'='+event.value;}
		}else{location.href=url_default+'?'+filtro+'='+event.value;}
	}
}

//// SELECCION DE IMAGEN
///////////////////////////////////////
///////////////////////////////////////

$(".custom-input-file input:file").change(function(){
	$(this).parent().find(".archivo span").html($(this).val()).css('color','#2a2a2b');
	return false;
});

//// SELECTOR DE COLOR
///////////////////////////////////////
///////////////////////////////////////

/*$('#colorSelector').ColorPicker({
	color: '#ffffff',
	onShow: function (colpkr) {
		$(colpkr).fadeIn(500);
		return false;
	},
	onHide: function (colpkr) {
		$(colpkr).fadeOut(0);
		return false;
	},
	onChange: function (hsb, hex, rgb) {
		$('#color_seleccionado').val(hex);
		$('#colorSelector div').css('backgroundColor', '#'+hex);
	}
});*/

function setColor(id){
	var color = $('body input#color_seleccionado').val()
	location.href="proveedores_item.php?id="+id+'&setColor='+color;
}

//// DESCARGAR
///////////////////////////////////////
///////////////////////////////////////


function descargar(miurl){
	
	// Buscar parametros
	var url = location.href;
	var parametros = url.indexOf('?');
	// Hay Parametros
	if(parametros!=-1){

		var sep = url.split('.php?');
		var newURL = miurl+'?'+sep[1];

	// No hay Parametros
	}else{
		var newURL = miurl;
	}

	// Ir a URL construida
	location.href=newURL;
}

// //// BUSQUEDA DEPENDIENTE
// ///////////////////////////////////////
// ///////////////////////////////////////

// function setHijo(valor, hijo){

// 	var datos = 'accion=1&exe='+hijo+'&valor='+valor;
	
// 	$.ajax("_ajax.php",{
// 		cache:false,
// 		async: true,
// 		type: 'POST',
// 		data: datos,
// 		url: '_ajax.php',
// 		dataType: 'html',
// 		success: function(data) 
// 		{
// 			alert(data)
// 		}
// 	});
// }


// $('body').delegate('.hijo-inactivo','click',function(){

// 	var msj = $(this).data('msj');
// 	alert(msj)

// });

////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////// > CONSULTA GENERAL
////////////////////////////////////////////////////////////////


$('body').delegate('.agregarData', 'click', function(event){

	var padre = this.parentNode.id;

	// Busqueda Interna 
	if(padre=='clientes'){
		var accionInterna = 2;
	}
	if(padre=='productos'){
		var accionInterna = 3;
	}
	/////////////////////////////

	// Abrir Panel
	$('body div.panelBuscarDataExterna').show();

	// Id de Disparador de Panel
	var id = this.id;

	// Datos AJAX
	var datos = 'accion=1&exe='+id;

	// Cargando
	$('body div.panelBuscarDataExterna').html('<br><br><br><div class="inputBox"><center><font color="#999">Cargando...</font></center></div>');
	

	// AJAX
	$.ajax("_ajax.php",{
		cache:false,
		async: true,
		type: 'POST',
		data: datos,
		url: '_ajax.php',
		dataType: 'html',
		success: function(data) 
		{
			// Impresion de Datos
			$('body div.panelBuscarDataExterna').html(data);

			// Consultar Todos los Datos
			$('body div.panelBuscarDataExterna').delegate('div.verTodos', 'click', function(event){

				$('body div.panelBuscarDataExterna div.addContenidos').html('<br><br><div class="inputBox"><center><font color="#999">Buscando...</font></center></div>');

				// Busqueda Interna 
				if(this.id=='clientes'){
					var accionInterna = 2;
				}
				if(this.id=='productos'){
					var accionInterna = 3;
				}

				var datos = 'accion='+accionInterna+'&exe=1';
				$.ajax("_ajax.php",{
					cache:false,
					async: true,
					type: 'POST',
					data: datos,
					url: '_ajax.php',
					dataType: 'html',
					success: function(data) 
					{
						$('body div.panelBuscarDataExterna div.addContenidos').html(data);
						seleccionar();
						event.preventDefault();
						return false;

					}
				});
				event.preventDefault();
				return false;
			});

			// Consulta los Datos por Consulta
			$('body div.panelBuscarDataExterna form.addBusqueda').submit(function(event){

				var buscar = $('body div.panelBuscarDataExterna form.addBusqueda input.addBuscar').val();
				if(buscar!='')
				{
					$('body div.panelBuscarDataExterna div.addContenidos').html('<br><br><div class="inputBox"><center><font color="#999">Buscando...</font></center></div>');

					// Busqueda Interna 
					if(this.id=='clientes'){
						var accionInterna = 2;
					}
					if(this.id=='productos'){
						var accionInterna = 3;
					}

					var datos = 'accion='+accionInterna+'&exe=2&buscar='+buscar;
					$.ajax("_ajax.php",{
						cache:false,
						async: true,
						type: 'POST',
						data: datos,
						url: '_ajax.php',
						dataType: 'html',
						success: function(data) 
						{
							$('body div.panelBuscarDataExterna div.addContenidos').html(data);
							seleccionar();
							event.preventDefault();
							return false;
						}
					});
				}else{
					alert('Por favor coloque una Palabra Clave para iniciar la b\u00fasqueda')
				}
				event.preventDefault();
				return false;
			})

			cerrarPanelData();
			event.preventDefault();
			return false;
		}
	});

	function cerrar_panel(){
		// Cerrar Panel
		$('body div.panelBuscarDataExterna').hide();
		$('body div.panelBuscarDataExterna').html('');
		event.preventDefault();
		return false;

	}

	// Cerrar
	function cerrarPanelData()
	{
			$('body div.panelBuscarDataExterna').delegate('.addCerrar', 'click', function(event){

				cerrar_panel();
				event.preventDefault();
				return false;
			});
		event.preventDefault();
		return false;
	}

	function seleccionar(){

		$('body div.panelBuscarDataExterna div.addContenidos').delegate('div.addFila','click', function(event){

			var padre = $('body div.panelBuscarDataExterna input#elPadre').val()
			
			// Extrayendo datos de Cliente
			var datos = this.id;
			/////////////////////
			/////////////////////
			/////////////////////
			// AGREGANDO CLIENTE
			/////////////////////
			/////////////////////
			/////////////////////
			if(padre=='clientes'){
				var d = datos.split('/**/');
				var id = d[0];
				var nombre = d[1];
				var identificacion = d[2];
				cerrar_panel();
				$('body').find('div#'+padre).children('div.contenedorDataExterna').html('<div class="inputBox"><div class="inputText active">'+nombre+'</div></div><div class="inputBox right"><div class="inputText right active">'+identificacion+'</div></div><input type="hidden" value="'+id+'" name="id_cliente">');
			}
			if(padre=='productos'){

				// Cargando
				$('body div.panelBuscarDataExterna').html('<br><br><br><div class="inputBox"><center><font color="#999">Procesando...</font></center></div>');
				var datos = 'accion=4&id='+datos;
				$.ajax("_ajax.php",{
					cache:false,
					async: true,
					type: 'POST',
					data: datos,
					url: '_ajax.php',
					dataType: 'html',
					success: function(data) 
					{
						$('body div.panelBuscarDataExterna').html(data);
						cerrarPanelData();

						$('body div.panelBuscarDataExterna').delegate('div.listoSeleccion','click',function(event){

							var presentacion = $('body div.panelBuscarDataExterna').find('select#presentacion').val();
							if(presentacion!=-1){
								
								var sabor = $('body div.panelBuscarDataExterna').find('select#sabor').val(); 
								if(sabor!=-1){
									var costo = $('body div.panelBuscarDataExterna').find('select#costo').val(); 
									if(costo!=-1){

										// Otros Valores
										var id_producto = $('body div.panelBuscarDataExterna').find('input#id_producto').val(); 
										var codigo = $('body div.panelBuscarDataExterna').find('input#codigo').val(); 
										var nombre = $('body div.panelBuscarDataExterna').find('input#nombre').val(); 
										var cantidad = $('body div.panelBuscarDataExterna').find('input#cantidad').val(); 
										if(presentacion!=0){
											var pre_presentacion = presentacion.split('//');
											var id_presentacion = pre_presentacion[0];
											var nombre_presentacion = pre_presentacion[1];
										}else{
											var id_presentacion = 0;
											var nombre_presentacion ='--';
										}
										if(sabor!=0){
											var pre_sabor = sabor.split('//');
											var id_sabor = pre_sabor[0];
											var nombre_sabor = pre_sabor[1];
										}else{
											var id_sabor = 0;
											var nombre_sabor ='--';
										}


										// Generar valor aleatorio
										var aleatorio = Math.round(Math.random()*100000000);

										// Verificacion de existencia de Filas en Contenedor (Para borrar el Titulo Default)
										var q = $('body').find('div#'+padre+' div.contenedorDataExterna div.linea').length;
										
										// Impresion de Titulo
										var c='';

										if(q==0){
											$('body').find('div#'+padre).children('div.contenedorDataExterna').html('');
											c += '<div class="titData codigo noMovil">C&Oacute;DIGO</div><div class="titData nombre" style="text-align:center !important;">NOMBRE | PRESENTACION | SABOR</div><div class="titData cantidad">QT</div><div class="titData precio" style="text-align:center !important;padding-right:0px !important;">PRECIO U.</div><div class="titData preciot" style="text-align:center !important;padding-right:0px !important;">PRECIO T.</div>';
										}

										// Creacion de Fila
										c += '<div id="'+aleatorio+'" class="linea"><div class="itemData codigo noMovil">'+codigo+'</div><div class="itemData nombre">- '+nombre+' | '+nombre_presentacion+' | '+nombre_sabor+'</div><div class="itemData cantidad">'+cantidad+'</div><div class="itemData precio">$'+number_format(costo, '2', '.', ' ')+'</div><div class="itemData preciot" data-sub="'+(costo*cantidad)+'">$'+number_format((costo*cantidad), '2', ',', '.')+'</div><div class="itemData delete"><img src="img/eliminar.png" id="'+aleatorio+'" title="Borrar" class="eliminarFila"></div><input type="hidden" name="id_producto_'+aleatorio+'" value="'+id_producto+'"><input type="hidden" name="id_producto_presentacion_'+aleatorio+'" value="'+id_presentacion+'"><input type="hidden" name="id_producto_sabor_'+aleatorio+'" value="'+id_sabor+'"><input type="hidden" name="codigo_'+aleatorio+'" value="'+codigo+'"><input type="hidden" name="nombre_'+aleatorio+'" value="'+nombre+'"><input type="hidden" name="nombre_producto_presentacion_'+aleatorio+'" value="'+nombre_presentacion+'"><input type="hidden" name="nombre_producto_sabor_'+aleatorio+'" value="'+nombre_sabor+'"><input type="hidden" name="cantidad_'+aleatorio+'" value="'+cantidad+'"><input type="hidden" name="precio_'+aleatorio+'" value="'+number_format(costo, '2', '.', ' ')+'"></div>';


										// Impresion de Fila
										$('body').find('div#'+padre).children('div.contenedorDataExterna').append(c);

										// Aumentando Total
										var q = $('body').find('div#'+padre+' div.contenedorDataExterna div.linea').length;
										var qNew = q;
										var total = 0;
										for(var z = 0; z < qNew; z++){
											var pre = $('body').find('div#'+padre+' div.contenedorDataExterna div.linea:eq('+z+') div.preciot').data('sub');
											total += parseFloat(pre);
										}
										$('body').find('#totalPedido').html('$'+number_format(total,'2','.',' '));
										$('body').find('#precioTotal').val(number_format(total,'2','.',''));

										// Cerrar Panel
										cerrar_panel();

										// Eliminar Productos
										$('body div#'+padre+' div.contenedorDataExterna').delegate('div.delete','click', function(event){

											var costoT = $(this).parent().children('div.preciot').data('sub');
											var costoTT = $('body').find('#precioTotal').val();
											var ahora =  parseFloat(costoTT) - parseFloat(costoT);
											$('body').find('#totalPedido').html('$'+number_format(ahora,'2','.',' '));
											$('body').find('#precioTotal').val(number_format(ahora,'2','.',''));

											$(this).parent().remove();

											var q = $('body').find('div#'+padre+' div.contenedorDataExterna div.linea').length;

											if(q==0){
												$('body div#productos').find('div#'+padre).children('div.contenedorDataExterna').html('<font color="#999">Seleccione el Producto...</font>');
											}

											event.stopImmediatePropagation();
											event.preventDefault();
											return false;
										});



									}else{
										alert('Por favor seleccione el "Costo"');
									}
								}else{
									alert('Por favor seleccione el "Sabor"');
								}
							}else{
								alert('Por favor seleccione la "Presentaci\u00f3n"');
							}
							event.stopImmediatePropagation();
							event.preventDefault();
							return false;
						});
						event.stopImmediatePropagation();
						event.preventDefault();
						return false;
					}
				});

			}
			event.stopImmediatePropagation();
			event.preventDefault();
			return false;
		});
		event.preventDefault();
		return false;
	}
	event.preventDefault();
	return false;

});


// Eliminar Productos
$('body div.contenedorDataExterna').delegate('div.delete','click', function(event){

	var costoT = $(this).parent().children('div.preciot').data('sub');
	var costoTT = $('body').find('#precioTotal').val();
	var ahora =  parseFloat(costoTT) - parseFloat(costoT);
	$('body').find('#totalPedido').html('$'+number_format(ahora,'2','.',' '));
	$('body').find('#precioTotal').val(number_format(ahora,'2','.',''));
	$(this).parent().remove();
	var q = $('body').find('div.contenedorDataExterna div.linea').length;
	if(q==0){
		$('body div#productos').find('div.contenedorDataExterna').html('<font color="#999">Seleccione el Producto...</font>');
	}

	event.stopImmediatePropagation();
	event.preventDefault();
	return false;
});