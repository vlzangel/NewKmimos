<?php
	$CONTENIDO = '
		<h1 class="theme_tite theme_table_title">Administración de disponibilidad</h1>

		<div class="fechas_box">
			<div class="fechas_item">
				<SELECT name="servicio">
					<OPTION>Hospedaje</OPTION>
					<OPTION>Guardería</OPTION>
				</SELECT>
	        </div>

			<div class="fechas_item">
				<i class="icon-calendario embebed"></i>
		        <input type="text" id="inicio" name="inicio" class="fechas" placeholder="Inicio" readonly>
	        </div>

			<div class="fechas_item">
				<div class="icono"><i class="icon-calendario embebed"></i></div>
		        <input type="text" id="fin" name="fin" class="fechas" placeholder="Fin" readonly disabled>
	        </div>
        </div>

	';
?>