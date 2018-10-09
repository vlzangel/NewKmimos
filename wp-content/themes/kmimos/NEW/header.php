<?php
	wp_enqueue_style( 'generales_css', get_recurso("css")."generales.css", array(), "1.0.0" );
	wp_enqueue_style( 'generales_responsive_css', get_recurso("css")."responsive/generales.css", array(), "1.0.0" );

	wp_head();

	$HTML = '
		<nav>
			<table>
				<tr>
					<td class="nav_left">
						<img class="logo" src="'.get_recurso("img").'PNG/logo.png" />
						<img class="logo logo_negro" src="'.get_recurso("img").'PNG/logo-negro.png" />
						<a href="#" id="" class="boton">
							<img class="lupa" src="'.get_recurso("img").'PNG/Buscar.png" /> 
							<img class="lupa_negra" src="'.get_recurso("img").'PNG/Buscar_negro.png" /> 
							Buscar Cuidador
						</a>
						<a href="#" id="" class="boton boton_morado"> <img src="'.get_recurso("img").'PNG/Ser_cuidador.png" /> Quiero ser Cuidador</a>
					</td>
					<td class="nav_right">
						<a href="#" id=""> Iniciar Sesión </a> |
						<a href="#" id=""> Registrarme </a>
					</td>
				</tr>
			</table>
		</nav>
	';

	echo comprimir($HTML);
?>