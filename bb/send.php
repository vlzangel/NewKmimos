<?php
include('_header.php');
$open=0;
if(isset($_GET['open']) && !empty($_GET['open'])){
	$open=addslashes(trim($_GET['open']));
}
?>


<div class="envio">
	<div class="mainlogo"><div class="log"></div></div>
	<form action="sending.php?f=1" enctype="multipart/form-data" method="post" class="contenedorEnvio" id="formularioMain1" style="<?php if($open!=0){echo 'display:none;';}?>">
		
		<div class="contenido1">
			Vamos a pedirte algunos datos sobre ti y tu héroe, solo te tomará 5 minutos. Es un pequeño sacrificio de tiempo para salvar más vidas.
			<br>
			<div class="punto p3"></div><div class="punto p3"></div><div class="punto p3"></div>
		</div>

		<div class="paso">
			PASO 1/3
		</div>

		<div class="titulo">
			CUÉNTANOS SOBRE TI
		</div>

		<div class="bloque">
			<input type="text" class="input" placeholder="Nombre" name="nombre" autocomplete="off"><input type="text" class="input r" placeholder="Apellido" name="apellido" autocomplete="off">
		</div>

		<div class="bloque pre">
			<input type="text" class="input" placeholder="Teléfono" autocomplete="off" id="telefono1"><input type="email" class="input r" placeholder="Correo electrónico" autocomplete="off" id="correo1">
		</div>

		<div class="bloquelabel nomovil">
			<div class="label">Confirma tu teléfono</div><div class="label r">Confirma tu correo electrónico</div>
		</div>
		<div class="bloquelabel nopc">
			<div class="label">Confirma tu teléfono y correo</div>
		</div>

		<div class="bloque">
			<input type="text" class="input" placeholder="Teléfono" name="telefono" autocomplete="off" id="telefono2"><input type="email" class="input r" placeholder="Correo electrónico" name="correo" autocomplete="off" id="correo2">
		</div>

		<div class="contenido3">
			<div class="tip"></div>Blood Brothers es una iniciativa del brazo social de Kmimos realizada para ayudar a todos los perritos de México, todos los términos y condiciones y la política de privacidad de Kmimos aplican. 
		</div>

		<input type="submit" class="input submit1" name="exe" autocomplete="off" value="GUARDAR Y CONTINUAR">


	</form>
	<form action="sending.php?f=2" enctype="multipart/form-data" method="post" class="contenedorEnvio" id="formularioMain2" style="<?php if($open==2){echo 'display:block;';} ?>">
		<input type="hidden" name="id_formulario" value="<?php echo addslashes(trim($_GET['id'])); ?>">

		<div class="contenido1">
			Vamos a pedirte algunos datos sobre ti y tu héroe, solo te tomará 5 minutos. Es un pequeño sacrificio de tiempo para salvar más vidas.
			<br>
			<div class="punto p3"></div><div class="punto p3"></div><div class="punto p3"></div>
		</div>

		<div class="paso">
			PASO 2/3
		</div>

		<div class="titulo">
			TAN SOLO FALTA UN PASO
		</div>

		<div class="bloquesub">
			¿Dónde vives?
		</div>

		<div class="bloque">
			<select name="estado" class="select" id="estado">
				<option value="" data-edo="0" selected="selected">Estado</option>
				<option value="Aguascalientes" data-edo="1">Aguascalientes</option>
				<option value="Baja California" data-edo="2">Baja California</option>
				<option value="Baja California Sur" data-edo="3">Baja California Sur</option>
				<option value="Campeche" data-edo="4">Campeche</option>
				<option value="Chiapas" data-edo="5">Chiapas</option>
				<option value="Chihuahua" data-edo="6">Chihuahua</option>
				<option value="Ciudad de México" data-edo="7">Ciudad de México</option>
				<option value="Durango" data-edo="8">Durango</option>
				<option value="Estado de México" data-edo="9">Estado de México</option>
				<option value="Guanajuato" data-edo="10">Guanajuato</option>
				<option value="Guerrero" data-edo="11">Guerrero</option>
				<option value="Hidalgo" data-edo="12">Hidalgo</option>
				<option value="Jalisco" data-edo="13">Jalisco</option>
				<option value="Michoacán" data-edo="14">Michoacán</option>
				<option value="Morelos" data-edo="15">Morelos</option>
				<option value="Nayarit" data-edo="16">Nayarit</option>
				<option value="Nuevo León" data-edo="17">Nuevo León</option>
				<option value="Oaxaca" data-edo="18">Oaxaca</option>
				<option value="Puebla" data-edo="19">Puebla</option>
				<option value="Queretaro" data-edo="20">Queretaro</option>
				<option value="Quintana" Roo data-edo="21">Quintana Roo</option>
				<option value="San Luis Potosi" data-edo="22">San Luis Potosi</option>
				<option value="Sinaloa" data-edo="23">Sinaloa</option>
				<option value="Sonora" data-edo="24">Sonora</option>
				<option value="Tabasco" data-edo="25">Tabasco</option>
				<option value="Tamaulipas" data-edo="26">Tamaulipas</option>
				<option value="Tlaxcala" data-edo="27">Tlaxcala</option>
				<option value="Veracruz de Ignacio de la Llave" data-edo="28">Veracruz de Ignacio de la Llave</option>
				<option value="Yucatan" data-edo="29">Yucatan</option>
				<option value="Zacatecas" data-edo="30">Zacatecas</option>		


				</select><span id="mimunicipio"><select name="municipio" class="select r unactive" id="municipio">
				<option value="" selected="selected">Municipio/Delegación</option>
				<option value="">Seleccione el Estado</option>
			</select></span>
		</div>

		<div class="titulo sec">
			CUÉNTANOS SOBRE TU PERRHIJO
		</div>
		<div class="bloquesub">
			¿Es cachorro o adulto?
		</div>

		<div class="bloque pre1">
			<div class="input radio">Cachorro
				<div class="mark"></div>
				<input type="radio" name="desarrollo" value="Cachorro" class="radiobtn">
			</div><div class="bloquelabelsub nopc">
				<div class="label">Hasta el primer año</div>
			</div><div class="input r radio">Adulto
				<div class="mark"></div>
				<input type="radio" name="desarrollo" value="Adulto" class="radiobtn">
			</div><div class="bloquelabelsub nopc">
				<div class="label">De 1 año en adelante</div>
			</div>
		</div>
		<div class="bloquelabelsub nomovil">
			<div class="label">Hasta el primer año</div><div class="label r">De 1 año en adelante</div>
		</div>
		<div class="bloquesub">
			¿Cuál es su raza?
		</div>
		<div class="bloque center">
			<select name="raza" class="select">
				<option value="" selected="selected">Selecciona de la lista</option>
				<option value="Affenpinscher">Affenpinscher</option>
				<option value="Airedale terrier">Airedale terrier</option>
				<option value="Akita Inu">Akita Inu</option>
				<option value="Akita Americano">Akita Americano</option>
				<option value="Alano español">Alano español</option>
				<option value="Alaskan malamute">Alaskan malamute</option>
				<option value="American Hairless terrier">American Hairless terrier</option>
				<option value="American Staffordshire Terrier">American Staffordshire Terrier</option>
				<option value="Antiguo Perro Pastor Inglés">Antiguo Perro Pastor Inglés</option>
				<option value="Appenzeller">Appenzeller</option>
				<option value="Australian Cattle Dog">Australian Cattle Dog</option>
				<option value="Australian Silky Terrier">Australian Silky Terrier</option>
				<option value="Azawakh">Azawakh</option>
				<option value="Bardino (Perro majorero)">Bardino (Perro majorero)</option>
				<option value="Basenji">Basenji</option>
				<option value="Basset hound">Basset hound</option>
				<option value="Beagle">Beagle</option>
				<option value="Beauceron">Beauceron</option>
				<option value="Bichón frisé">Bichón frisé</option>
				<option value="Bichón maltés">Bichón maltés</option>
				<option value="Bobtail">Bobtail</option>
				<option value="Bloodhound">Bloodhound</option>
				<option value="Border collie">Border collie</option>
				<option value="Borzoi">Borzoi</option>
				<option value="Boston terrier">Boston terrier</option>
				<option value="Bóxer">Bóxer</option>
				<option value="Braco alemán de pelo corto">Braco alemán de pelo corto</option>
				<option value="Braco alemán de pelo duro">Braco alemán de pelo duro</option>
				<option value="Braco de Auvernia">Braco de Auvernia</option>
				<option value="Braco francés">Braco francés</option>
				<option value="Braco húngaro">Braco húngaro</option>
				<option value="Braco italiano">Braco italiano</option>
				<option value="Braco tirolés">Braco tirolés</option>
				<option value="Braco de Saint Germain">Braco de Saint Germain</option>
				<option value="Braco de Weimar">Braco de Weimar</option>
				<option value="Bull Terrier">Bull Terrier</option>
				<option value="Bulldog americano">Bulldog americano</option>
				<option value="Bulldog francés">Bulldog francés</option>
				<option value="Bulldog inglés">Bulldog inglés</option>
				<option value="Bullmastiff">Bullmastiff</option>
				<option value="Can de palleiro">Can de palleiro</option>
				<option value="Caniche">Caniche</option>
				<option value="Cão da Serra da Estrela">Cão da Serra da Estrela</option>
				<option value="Cão da Serra de Aires">Cão da Serra de Aires</option>
				<option value="Cão de Agua Português">Cão de Agua Português</option>
				<option value="Cão de Castro Laboreiro">Cão de Castro Laboreiro</option>
				<option value="Cão de Fila de São Miguel">Cão de Fila de São Miguel</option>
				<option value="Chesapeake Bay Retriever">Chesapeake Bay Retriever</option>
				<option value="Chihuahueño">Chihuahueño</option>
				<option value="Crestado Chino">Crestado Chino</option>
				<option value="Chow chow">Chow chow</option>
				<option value="Clumber spaniel">Clumber spaniel</option>
				<option value="Cocker spaniel americano">Cocker spaniel americano</option>
				<option value="Cocker spaniel inglés">Cocker spaniel inglés</option>
				<option value="Collie">Collie</option>
				<option value="Bearded collie">Bearded collie</option>
				<option value="Dachshund">Dachshund</option>
				<option value="Dálmata">Dálmata</option>
				<option value="Dandie Dinmont Terrier">Dandie Dinmont Terrier</option>
				<option value="Deerhound">Deerhound</option>
				<option value="Dobermann">Dobermann</option>
				<option value="Dogo alemán">Dogo alemán</option>
				<option value="Dogo argentino">Dogo argentino</option>
				<option value="Dogo de burdeos">Dogo de burdeos</option>
				<option value="Dogo del Tíbet">Dogo del Tíbet</option>
				<option value="Dogo guatemalteco">Dogo guatemalteco</option>
				<option value="English springer spaniel">English springer spaniel</option>
				<option value="Entlebucher">Entlebucher</option>
				<option value="Epagneul bretón">Epagneul bretón</option>
				<option value="Epagneul francés">Epagneul francés</option>
				<option value="Epagneul papillón">Epagneul papillón</option>
				<option value="Eurasier">Eurasier</option>
				<option value="Fila Brasileiro">Fila Brasileiro</option>
				<option value="Flat-Coated Retriever">Flat-Coated Retriever</option>
				<option value="Fox Terrier">Fox Terrier</option>
				<option value="Galgo español">Galgo español</option>
				<option value="Galgo húngaro">Galgo húngaro</option>
				<option value="Galgo inglés">Galgo inglés</option>
				<option value="Galgo italiano">Galgo italiano</option>
				<option value="Golden retriever">Golden retriever</option>
				<option value="Gran danés">Gran danés</option>
				<option value="Gegar colombiano">Gegar colombiano</option>
				<option value="Greyhound">Greyhound</option>
				<option value="Grifón belga">Grifón belga</option>
				<option value="Hovawart">Hovawart</option>
				<option value="Husky siberiano">Husky siberiano</option>
				<option value="Jack Russell Terrier">Jack Russell Terrier</option>
				<option value="Keeshond">Keeshond</option>
				<option value="Kerry blue terrier">Kerry blue terrier</option>
				<option value="Komondor">Komondor</option>
				<option value="Kuvasz">Kuvasz</option>
				<option value="Labrador">Labrador</option>
				<option value="Lakeland Terrier">Lakeland Terrier</option>
				<option value="Laekenois">Laekenois</option>
				<option value="Landseer">Landseer</option>
				<option value="Lebrel afgano">Lebrel afgano</option>
				<option value="Leonberger">Leonberger</option>
				<option value="Perro lobo de Saarloos">Perro lobo de Saarloos</option>
				<option value="Lhasa apso">Lhasa apso</option>
				<option value="Lowchen">Lowchen</option>
				<option value="Maltés">Maltés</option>
				<option value="Malinois">Malinois</option>
				<option value="Manchester terrier">Manchester terrier</option>
				<option value="Mastín afgano">Mastín afgano</option>
				<option value="Mastín del Pirineo">Mastín del Pirineo</option>
				<option value="Mastí­n español">Mastí­n español</option>
				<option value="Mastín inglés">Mastín inglés</option>
				<option value="Mastín napolitano">Mastín napolitano</option>
				<option value="Mastín tibetano">Mastín tibetano</option>
				<option value="Mucuchies">Mucuchies</option>
				<option value="Mudi">Mudi</option>
				<option value="Nova Scotia Duck Tolling Retriever">Nova Scotia Duck Tolling Retriever</option>
				<option value="Ovejero magallánico">Ovejero magallánico</option>
				<option value="Pastor alemán">Pastor alemán</option>
				<option value="Pastor belga">Pastor belga</option>
				<option value="Pastor blanco suizo">Pastor blanco suizo</option>
				<option value="Pastor catalán">Pastor catalán</option>
				<option value="Pastor croata">Pastor croata</option>
				<option value="Pastor garafiano">Pastor garafiano</option>
				<option value="Pastor holandés">Pastor holandés</option>
				<option value="Pastor peruano Chiribaya">Pastor peruano Chiribaya</option>
				<option value="Pastor de los Pirineos">Pastor de los Pirineos</option>
				<option value="Pastor leonés">Pastor leonés</option>
				<option value="Pastor mallorquín">Pastor mallorquín</option>
				<option value="Pastor maremmano-abrucés">Pastor maremmano-abrucés</option>
				<option value="Pastor vasco">Pastor vasco</option>
				<option value="Pekinés">Pekinés</option>
				<option value="Pembroke Welsh Corgi">Pembroke Welsh Corgi</option>
				<option value="Pequeño Lebrel Italiano">Pequeño Lebrel Italiano</option>
				<option value="Perdiguero francés">Perdiguero francés</option>
				<option value="Perdiguero portugués">Perdiguero portugués</option>
				<option value="Perro cimarrón uruguayo">Perro cimarrón uruguayo</option>
				<option value="Perro de agua americano">Perro de agua americano</option>
				<option value="Perro de agua español">Perro de agua español</option>
				<option value="Perro de agua irlandés">Perro de agua irlandés</option>
				<option value="Perro de agua portugués">Perro de agua portugués</option>
				<option value="Perro dogo mallorquín">Perro dogo mallorquín</option>
				<option value="Perro esquimal canadiense">Perro esquimal canadiense</option>
				<option value="Perro de Montaña de los Pirineos">Perro de Montaña de los Pirineos</option>
				<option value="Perro fino colombiano">Perro fino colombiano</option>
				<option value="Perro pastor de las islas Shetland">Perro pastor de las islas Shetland</option>
				<option value="Perro peruano sin pelo">Perro peruano sin pelo</option>
				<option value="Phalí¨ne">Phalí¨ne</option>
				<option value="Pinscher alemán">Pinscher alemán</option>
				<option value="Pinscher miniatura">Pinscher miniatura</option>
				<option value="Pitbull">Pitbull</option>
				<option value="Podenco canario">Podenco canario</option>
				<option value="Podenco ibicenco">Podenco ibicenco</option>
				<option value="Podenco portugués">Podenco portugués</option>
				<option value="Pointer">Pointer</option>
				<option value="Pomerania">Pomerania</option>
				<option value="Presa canario">Presa canario</option>
				<option value="Pudelpointer">Pudelpointer</option>
				<option value="Pug">Pug</option>
				<option value="Puli">Puli</option>
				<option value="Pumi">Pumi</option>
				<option value="Rafeiro do Alentejo">Rafeiro do Alentejo</option>
				<option value="Ratonero bodeguero andaluz">Ratonero bodeguero andaluz</option>
				<option value="Ratonero mallorquín">Ratonero mallorquín</option>
				<option value="Ratonero valenciano">Ratonero valenciano</option>
				<option value="Rhodesian Ridgeback">Rhodesian Ridgeback</option>
				<option value="Rottweiler">Rottweiler</option>
				<option value="Saluki">Saluki</option>
				<option value="Samoyedo">Samoyedo</option>
				<option value="San Bernardo">San Bernardo</option>
				<option value="Schnauzer estándar">Schnauzer estándar</option>
				<option value="Schnauzer gigante">Schnauzer gigante</option>
				<option value="Schnauzer miniatura">Schnauzer miniatura</option>
				<option value="Staffordshire Bull Terrier">Staffordshire Bull Terrier</option>
				<option value="Setter inglés">Setter inglés</option>
				<option value="Setter irlandés">Setter irlandés</option>
				<option value="Shar Pei">Shar Pei</option>
				<option value="Shiba Inu">Shiba Inu</option>
				<option value="Shih Tzu">Shih Tzu</option>
				<option value="Siberian husky">Siberian husky</option>
				<option value="Skye terrier">Skye terrier</option>
				<option value="Spitz enano">Spitz enano</option>
				<option value="Spitz grande">Spitz grande</option>
				<option value="Spitz mediano">Spitz mediano</option>
				<option value="Spitz japonés">Spitz japonés</option>
				<option value="Sussex spaniel">Sussex spaniel</option>
				<option value="Teckel">Teckel</option>
				<option value="Terranova">Terranova</option>
				<option value="Terrier alemán">Terrier alemán</option>
				<option value="Terrier australiano">Terrier australiano</option>
				<option value="Terrier brasileño">Terrier brasileño</option>
				<option value="Terrier chileno">Terrier chileno</option>
				<option value="Terrier escocés">Terrier escocés</option>
				<option value="Terrier galés">Terrier galés</option>
				<option value="Terrier irlandés">Terrier irlandés</option>
				<option value="Terrier ruso negro">Terrier ruso negro</option>
				<option value="Terrier tibetano">Terrier tibetano</option>
				<option value="Tervueren">Tervueren</option>
				<option value="Weimaraner">Weimaraner</option>
				<option value="West Highland White Terrier">West Highland White Terrier</option>
				<option value="Whippet">Whippet</option>
				<option value="Wolfsspitz">Wolfsspitz</option>
				<option value="Xoloitzcuintle">Xoloitzcuintle</option>
				<option value="Yorkshire terrier">Yorkshire terrier</option>
				<option value="Mestizo">Mestizo</option>
				<option value="Pastor Brie/Briard">Pastor Brie/Briard</option>
				<option value="Poodle">Poodle</option>
				<option value="French Poodle">French Poodle</option>
				<option value="Afgano">Afgano</option>
				<option value="Pastor Ganadero Australiano">Pastor Ganadero Australiano</option>
				<option value="Pastor Ganadero Australiano">Pastor Ganadero Australiano</option>
				<option value="Pettit Basset Grison  Vendeano ">Pettit Basset Grison  Vendeano</option>
				<option value="Zuchon ">Zuchon</option>
				<option value="Gato / Gata">Gato / Gata</option>
				<option value="Harrier">Harrier</option>
				<option value="Gato">Gato</option>
				<option value="Cavalier">Cavalier</option>
				<option value="Bernes de la Montaña">Bernes de la Montaña</option>
			</select>
		</div>
		<div class="bloquesub">
			¿Cuál es su tamaño?
		</div>

		<div class="bloque pre1">
			<div class="input radio">Pequeños
				<div class="mark"></div>
				<input type="radio" name="tamano" value="Pequeño" class="radiobtn">
			</div><div class="bloquelabelsub nopc">
				<div class="label">0.0 cm - 25.0 cm</div>
			</div><div class="input r radio">Medianos
				<div class="mark"></div>
				<input type="radio" name="tamano" value="Mediano" class="radiobtn">
			</div><div class="bloquelabelsub nopc">
				<div class="label">26.0 cm - 58.0 cm</div>
			</div>
			<div class="bloquelabelsub sec nomovil">
				<div class="label">0.0 cm - 25.0 cm</div><div class="label r">26.0 cm - 58.0 cm</div>
			</div>

			<div class="input radio">Grandes
				<div class="mark"></div>
				<input type="radio" name="tamano" value="Grande" class="radiobtn">
			</div><div class="bloquelabelsub nopc">
				<div class="label">59.0 cm - 73.0 cm</div>
			</div><div class="input r radio">Gigantes
				<div class="mark"></div>
				<input type="radio" name="tamano" value="Gigante" class="radiobtn">
			</div><div class="bloquelabelsub nopc">
				<div class="label">74.0 cm - 200.0 cm</div>
			</div>
			<div class="bloquelabelsub nomovil">
				<div class="label">59.0 cm - 73.0 cm</div><div class="label r">74.0 cm - 200.0 cm</div>
			</div>
		</div>

		<div class="bloquesub">
			¿Cual es su peso?
		</div>

		<div class="bloque" style="margin-bottom: 40px;">
			<div class="input radio"><span class="menos">-</span> 20 Kg
				<div class="mark"></div>
				<input type="radio" name="peso" value="Menos 20Kg" class="radiobtn">
			</div><div class="input r radio"><span class="menos">+</span> 20 Kg
				<div class="mark"></div>
				<input type="radio" name="peso" value="Mas 20Kg" class="radiobtn">
			</div>
		</div>

		<input type="submit" class="input submit1" name="exe" autocomplete="off" value="GUARDAR Y CONTINUAR">

	</form>
	<form action="sending.php?f=3" enctype="multipart/form-data" method="post" class="contenedorEnvio" id="formularioMain3" style="<?php if($open==3){echo 'display:block;';} ?>">

		<input type="hidden" name="id_formulario" value="<?php echo addslashes(trim($_GET['id'])); ?>">
		<div class="contenido1">
			Vamos a pedirte algunos datos sobre ti y tu héroe, solo te tomará 5 minutos. Es un pequeño sacrificio de tiempo para salvar más vidas.
			<br>
			<div class="punto p3"></div><div class="punto p3"></div><div class="punto p3"></div>
		</div>

		<div class="paso">
			PASO 3/3
		</div>

		<div class="titulo last">
			¡YA ES LA ÚLTIMA PARTE!
		</div>


		<div class="titulo sec">
			HÁBLANOS DE SU HISTORIAL MÉDICO
		</div>
		<div class="bloquesub long">
			¿Tu perrito ha padecido alguna de estas enfermedades?
		</div>

		<div class="bloque pre2">
			<center><div class="input check inputcheck" id="norecuerdo">No lo recuerdo
				<div class="mark"></div>
				<input type="checkbox" name="norecuerdo" value="1" class="checkbtn">
			</div></center>
			<div class="input check inputcheck" id="brucelosis">Brucelosis
				<div class="mark"></div>
				<input type="checkbox" name="brucelosis" value="1" class="checkbtn">
			</div><div class="input r check inputcheck" id="ehrlichiosis">Ehrlichiosis
				<div class="mark"></div>
				<input type="checkbox" name="ehrlichiosis" value="1" class="checkbtn">
			</div>
			<div class="input check inputcheck" id="hemobartonelosis">Hemobartonelosis
				<div class="mark"></div>
				<input type="checkbox" name="hemobartonelosis" value="1" class="checkbtn">
			</div><div class="input r check inputcheck" id="leishmaniasis">Leishmaniasis
				<div class="mark"></div>
				<input type="checkbox" name="leishmaniasis" value="1" class="checkbtn">
			</div>
			<div class="input check inputcheck" id="babesiosis">Babesiosis
				<div class="mark"></div>
				<input type="checkbox" name="babesiosis" value="1" class="checkbtn">
			</div><div class="input r check inputcheck" id="filariasis">Filariasis
				<div class="mark"></div>
				<input type="checkbox" name="filariasis" value="1" class="checkbtn">
			</div>
			<div class="input check inputcheck" id="toxoplasmosis">Toxoplasmosis
				<div class="mark"></div>
				<input type="checkbox" name="toxoplasmosis" value="1" class="checkbtn">
			</div><div class="input r check inputcheck" id="anaplasma">Anaplasma
				<div class="mark"></div>
				<input type="checkbox" name="anaplasma" value="1" class="checkbtn">
			</div>
			<div class="input check full inputcheck" id="ninguna">Ninguna de las mencionadas
				<div class="mark"></div>
				<input type="checkbox" name="ninguna" value="1" class="checkbtn">
			</div>
			
			<div class="sino nomovil"><font color="#FFFFFF"><font size="+1">*</font></font> Si no lo recuerdas, te contactaremos para entrar en detalles</div>
			<div class="sino nopc"><font color="#FFFFFF"><font size="+3">*</font></font> Si no lo recuerdas, te contactaremos para entrar en detalles</div>
		</div>

		<div class="bloquesub long">
			¿Tiene sus vacunas al día contra estas enfermedades?
		</div>

		<div class="bloque pre1">
			<div class="input check">Moquillo
				<div class="mark"></div>
				<input type="checkbox" name="moquillo" value="1" class="checkbtn">
			</div><div class="input r check">Hepatitis
				<div class="mark"></div>
				<input type="checkbox" name="hepatitis" value="1" class="checkbtn">
			</div>
			<div class="input check">Parvovirus
				<div class="mark"></div>
				<input type="checkbox" name="parvovirus" value="1" class="checkbtn">
			</div><div class="input r check">Parainfluenza
				<div class="mark"></div>
				<input type="checkbox" name="parainfluenza" value="1" class="checkbtn">
			</div>
			<div class="input check">Rabia
				<div class="mark"></div>
				<input type="checkbox" name="rabia" value="1" class="checkbtn">
			</div><div class="input r check">Leptospirosis
				<div class="mark"></div>
				<input type="checkbox" name="leptospirosis" value="1" class="checkbtn">
			</div>
		</div>
		<div class="bloquesub">
			¿Está desparasitado?
		</div>
		<div class="bloque">
			<center><div class="input radio tiny">SI
				<div class="mark"></div>
				<input type="radio" name="desparasitado" value="Desparasitado" class="radiobtn">
			</div><div class="input r radio tiny">NO
				<div class="mark"></div>
				<input type="radio" name="desparasitado" value="No Desparasitado" class="radiobtn">
			</div></center>
		</div>

		<div class="contenido2">
			<div class="tit">¿POR QUÉ PEDIMOS ESTA INFORMACION?</div>
			<div class="txt">
				Para garantizar que tu perrito sea donante ideal, necesitamos conocer su historial médico. Si tu compañero/a ha sufrido alguna enfermedad sanguínea, <span class="no">no puede ser donador</span>, pero puede ser beneficiado por la hermandad Blood Brothers
			</div>
		</div>

		<div class="casi">
			¡YA CASI!<br>
			<span>UNA COSA MÁS</span>
		</div>


		<div class="bloque center">
			<input type="text" class="input nombre" placeholder="Nombre de la mascota" name="nombremascota" autocomplete="off"><br>
		</div>
		<input type="submit" class="input submit1 last" name="exe" autocomplete="off" value="ACEPTO">

		<div class="contenido4">
			Al firmar esto, acepto  unirme a una hermandad de campeones. Acepto como hermanos a todos los perritos de nuestra familia. Me comprometo en apoyarlos cuando más lo necesiten.<br><br>
		</div>


	</form>
</div>


<?php
if(isset($_GET['ex']) && addslashes($_GET['ex'])==1){
	$nombremascota = addslashes(trim($_GET['mascota']));
	?>
	<div class="modal" id="modal">
		<div class="cont ac" id="modal-cont">
			<div class="nombre"><?php echo $nombremascota; ?></div>
		</div>
	</div>
	<?php
}
include('_bottom.php')
?>