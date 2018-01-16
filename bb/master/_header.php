<?php
session_start();
if(!$_SESSION['i'])
{
  header('Location: index.php?e=1');
  exit;
}else{
  $id_u = $_SESSION['i'];
  $id_n = $_SESSION['n'];
  include('../conex.php');
}
// ME ENCUENTRO EN EL HOME
if($sub==0){
  $cssBody = 'CSSHome';
}
// ME ENCUENTRO EN UNA SUBPAGINA
else{
  $cssBody = 'CSSInner';
 }
?><!DOCTYPE html>
<html>
<head>
<title>Kmimos - Master</title>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800,600,700,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="styles/stylesMain.css" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0, user-scalable=no">
<link rel="icon" type="image/png" href="./img/favicon.png" /> 

</head>
<body>

  <div id="menu">
    
    <a class="atrasMovil" href="javascript:history.back(1);"></a><!--<div id="buscar"></div>--><a href="inicio.php" id="logo"></a><div id="usuario">
    <span class="av"><?php echo $id_n; ?></span></div><a href="salir.php" id="logOut"></a>


    <!-- HERRAMIENTAS MOVILES -->
    <div id="panelUsuario"><span class="av"><?php echo $id_n; ?></span><a href="salir.php" id="logOutMovil"></a></div>

  </div>
  <div id="menuMain" class="anFast">
    
      <div id="eventos" class="item"><span class="ico"></span><span class="txt">Eventos</span></div>


  </div>
  <div id="menuMainSecundario" class="anFast"></div><div id="menuMainTerciario" class="anFast"></div>


  <!--<div id="panelBuscar" class="anFast">
    
    <form action="#" method="post" id="buscarForm"><input type="text" name="buscando" id="buscando" placeholder="Buscar..." /><input type="submit" value="." name="buscarInit" id="buscarInit" /></form>

    <div id="panelOpcionesBusqueda">
      <div class="seleccion activa" id="1">Todos</div><div class="seleccion" id="2">Compras</div><div class="seleccion" id="3">Productos</div><div class="seleccion" id="4">Inventario</div><div class="seleccion" id="5">Ventas</div><div class="seleccion" id="6">Reportes</div>
    </div>

  </div>-->

<div class="contents <?php echo $cssBody; ?>">
