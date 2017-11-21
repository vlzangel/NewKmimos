<?php
// Tipo
$sub=1; 
// Header / Functions
include('_header.php'); 
include('_functions.php'); 
// Titulo
$titulo = array('file.png', 'SUSCRITOS'); // Icono, 
tituloMain($titulo);
$procesando=0;
//////////////////////////////////////
////////////////////////////////////// CODE

///// QUERY DE BUSQUEDA
$query = 'SELECT * FROM formulario ORDER BY id_formulario DESC';


//////////////////////////////////////
//////////////////////////////////////
//////////////////////////////////////
?>
<style type="text/css">
		.nro			{width: 50px !important;}
		.fecha		{width: 150px !important;}
		.nombre			{width: 300px !important;}
		.nombremascota 		{width: 200px !important;}
		.telefono 		{width: 150px !important;}
		.correo 		{width: 200px !important;}
		.raza 		{width: 250px !important;}
		.std 		{width: 200px !important;}
		.tit .std{
		    text-align: center;
		    text-transform: uppercase;
		}

		.nombre.txt 	{text-align: left !important;padding-left: 10px;font-weight: 600;}

	
</style>

<div class="listado">
	<?php
	$listado = $db->get_results($query);
		/*echo "<pre>";
			print_r($listado);
		echo "</pre>";*/
	$q_listado = count($listado);
	?>

	<!-- BUSCADOR / RESULTADOS / FILTROS  -->
	<!-- /////////////// -->

	<div class="filtros">

		<!-- DESCARGAS --><div onclick="javascript:descargar('xls.php');" class="descargarHistorial">DESCARGAR</div><!-- BUSCADOR -->
	</div>

	<!-- TITULO DE ITEMS -->
	<!-- /////////////// -->
	
	<div class="tit">
		<div class="tituloItem nro">#</div><div class="tituloItem fecha">FECHA</div><div class="tituloItem nombre">NOMBRE / APELLIDO</div><div class="tituloItem nombremascota">NOMBRE MASCOTA</div><div class="tituloItem telefono">TELEFONO</div><div class="tituloItem correo">CORREO</div><div class="std">estado</div><div class="std">municipio</div><div class="std">desarrollo</div><div class="std">raza</div><div class="std">tamano</div><div class="std">peso</div><div class="std">norecuerdo</div><div class="std">brucelosis</div><div class="std">ehrlichiosis</div><div class="std">hemobartonelosis</div><div class="std">leishmaniasis</div><div class="std">babesiosis</div><div class="std">filariasis</div><div class="std">toxoplasmosis</div><div class="std">anaplasma</div><div class="std">ninguna</div><div class="std">moquillo</div><div class="std">hepatitis</div><div class="std">parvovirus</div><div class="std">parainfluenza</div><div class="std">rabia</div><div class="std">leptospirosis</div><div class="std">desparasitado</div>

	</div>

	<!-- LISTADO DE ITEMS -->
	<!-- /////////////// -->

	<?php
	$cont = $q_listado;
	foreach ($listado as $r)
	{	
		?>
		<div class="item">
			<div class="nro"><?php echo $cont; ?></div>
			<div class="fecha"><?php $pre = explode('-', $r->fecha); echo $pre[2].'-'.$pre[1].'-'.$pre[0]; ?></div><div class="nombre txt">- <?php echo utf8_decode(utf8_encode($r->nombre)).' '.utf8_decode(utf8_encode($r->apellido)); echo '('.$r->id_formulario.')'; ?></div><div class="nombremascota"><?php echo utf8_decode(utf8_encode($r->nombremascota)); ?></div><div class="telefono"><?php echo utf8_decode(utf8_encode($r->telefono)); ?></div><div class="correo"><?php echo utf8_decode(utf8_encode($r->correo)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->estado)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->municipio)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->desarrollo)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->raza)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->tamano)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->peso)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->norecuerdo)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->brucelosis)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->ehrlichiosis)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->hemobartonelosis)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->leishmaniasis)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->babesiosis)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->filariasis)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->toxoplasmosis)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->anaplasma)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->ninguna)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->moquillo)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->hepatitis)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->parvovirus)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->parainfluenza)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->rabia)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->leptospirosis)); ?></div><div class="std"><?php echo utf8_decode(utf8_encode($r->desparasitado)); ?></div>

		</div>
		<?php
		$cont--;
	}
	?>
</div>

	<!-- PAGINACION DE LISTADO -->
	<!-- ///////////////////// -->

    <div class="paginacion">
      <?php
      if(($pagina - 1) > 0) { echo "<a href='".$_SERVER['PHP_SELF']."?pagina=".($pagina-1).$paginacion."' class='inactiva l'></a> "; }
      for ($i=1; $i<=$total_paginas; $i++)
      {
        if($pagina == $i)
        { 
          echo " <span class='activa'><b>".$pagina."</b></span> "; 
        } 
        else 
        { 
          echo " <a href='".$_SERVER['PHP_SELF']."?pagina=$i$paginacion' class='inactiva'>$i</a> "; 
        }
      }
      if(($pagina + 1)<=$total_paginas) { echo " <a href='".$_SERVER['PHP_SELF']."?pagina=".($pagina+1).$paginacion."' class='inactiva r'></a>"; }
      ?>
    </div>

<?php include('_bottom.php'); ?>