<?php
session_start();
$_SESSION['i'];
$_SESSION['n'];
unset($_SESSION['i']);
unset($_SESSION['n']);
session_destroy();
header('Location: index.php');
?>