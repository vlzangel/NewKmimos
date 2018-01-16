<?php
include('_header.php')
?>

<div class="familia">
	<div class="master">
		<div class="logo"></div>
		<div class="inscribete1">LA RED CANINA MÁS GRANDE<br><font color="#ffffff">DE DONANTES DE SANGRE</font></div>
		<div class="mensaje">La familia está<br>cuando más lo necesitas<br>
			<div class="punto"></div><div class="punto"></div><div class="punto"></div>
		</div>
		<div class="contenido">
			Hasta ahora existía un solo banco de sangre en todo México.<br><br>
			Por eso, nace Blood Brothers la primera red digital gratuita de donantes caninos de México realizada con los miembros de nuestra comunidad.
			<br><br>
			<span>Al inscribirte</span>, te unes a la primera red de voluntarios caninos de México listos para ayudar cuando un perrito necesite un donante de sangre
			<div class="inscribete">¡ÚNETE Y SALVA VIDAS<br>DE PERRHIJOS!</div>
		</div>
		<a class="salva" href="send.php">SALVA VIDAS AQUÍ</a>
		<div class="back1"></div>
		<div class="back1-1"></div>
		<!-- <div class="back2"></div> -->

	</div>
</div>

<div class="somos">
	<div class="master">
		<div class="texto1">
			<span>BLOOD BROTHERS</span><br>
			Está en el proceso de construcción de nuestra base de donantes
			<div class="dog1"><img src="img/dog1.png"></div>
		</div>
	</div>
</div>
<div class="conteo">
	<div class="back3"></div>
	<div class="back3-1"></div>
	<div class="master">
		<div class="contando">
			<div class="numero">0</div>
			<?php 
			$buscar = $db->get_results('SELECT id_formulario FROM formulario');
			$q = count($buscar) + 527;
			$cadena = $q;
			$matriz1 = str_split($cadena); 
			for($i=0;$i<count($matriz1);$i++){
				echo '<div class="numero active">'.$matriz1[$i].'</div>';
			}

			?>
		</div>
		<div class="texto2">
			HERMANOS FORMAN NUESTRA FAMILIA<br>
			<div class="punto p1"></div><div class="punto p1"></div><div class="punto p1"></div>
			<span>¡Ayúdanos a hacer crecer esta red!</span>
			<br>
			<div class="punto p1"></div><div class="punto p1"></div><div class="punto p1"></div>
			<br>
			Estaremos operativos<br>en noviembre
		</div>
	</div>
</div>
<div class="ayudanos">
	<div class="back4"></div>
	<div class="master">
		<div class="texto3">
			<div class="mensaje">
			AYUDARNOS<br>
			<span>ESTÁ EN LAS VENAS</span>
			</div>
				<div class="item">Si tienes un perro que pueda ser donador, regístrate en Blood Brothers.<div class="mas20"></div></div>

				<div class="item">Al necesitar un donante, puedes buscarlo en nuestra base de datos.<div class="mas20anos"></div></div>

				<div class="item pp">Notifica tu necesidad y el Blood Brother recibirá un correo o SMS.</div>

				<div class="item pp">Pronto se pondrá en contacto contigo y juntos salvarán la vida de un perrito.</div>

			<div class="dog2"></div>
		</div>
	</div>
</div>
<div class="descubre">
	<div class="master">
		<div class="titulo">
			DESCUBRE MÁS<br>
			<span>SOBRE BLOOD BROTHERS</span><br>
			<div class="punto p2"></div><div class="punto p2"></div><div class="punto p2"></div>
		</div>
		<div class="video"><img src="img/video.jpg"><div class="play"></div></div>
	</div>
</div>

<?php
include('_bottom.php');
?>