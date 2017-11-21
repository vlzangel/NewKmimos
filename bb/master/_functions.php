<?php
//////////////////////////////
////////////////////////////// tituloMain
//////////////////////////////

function tituloMain($titulo){
	$l = count($titulo);
	?><div class="tituloMain anNormal">
		<div class="iconoModulo" style="background-image:url(img/<?php echo $titulo[0]; ?>);"></div><div class="titModulo <?php if($l==2){echo 'active';}?>"><?php echo $titulo[1]; ?></div><?php if($l!=2){?><div class="titSubModulo <?php if($l==3){echo 'active';}?>"><?php echo $titulo[2]; ?></div><?php if($l==4){echo '<div class="titSubModulo active">'.$titulo[3].'</div>';} }?>
	</div><?php
}
//////////////////////////////
////////////////////////////// SANITIZANDO INPUT
//////////////////////////////
function in($valor)
{if(isset($valor)){return htmlentities($valor);}}
?>