<?php
session_start();
if(isset($_SESSION['i']))
{
  header('Location: inicio.php');
  exit;
}else{
?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Kmimos - Master</title>
	<meta name="robots" content="noindex, nofollow">
	<meta name="googlebot" content="noindex">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800,600,700,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="styles/stylesInitial.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0, user-scalable=no">
</head>
<body>

<br><br><div id="header"></div><div id="access">
<center>
	

	<form class="panelMain" action="logIn.php" enctype="multipart/form-data" method="post">
		<br><br>
		<div class="areaCampos">
			<?php if(isset($_GET['e'])){ ?><div class="errorLog">Datos de ingreso incorrectos.<br>Int&eacute;ntelo de nuevo.</div><?php } ?>
			<input type="text" class="campo" placeholder="Usuario" name="usuario" />
			<input type="password" class="campo" placeholder="Clave" name="clave" />
			<input type="submit" class="submit" value="ENTRAR" name="entrar" />
		</div>

	</form>

	<div id="copy">Derechos Reservados. Copyright &copy; 2017 </div>

</center>
</div>
</body>
</html>
<?php
}
?>