<?
/*

MEJORAS POR AÑADIR
--Gráficas
	-Quitar los [] de las fechas
	-Poner un mejor botón para las fechas
	-Mejorar interfaz de la bio
--Core
	-Analizar tweets (el horario de escritura).
	-Tweets faveados/retweeteados y veces.
	-Mentions	
*/

//Funciones
require_once("functions.php");


if($_GET || !$_POST) {
	echo "Error.";
	return;	
}

$user = $_POST['usuario'];

if($user=="" ) {
	echo "</style>Error. Missing data.";
	return;	
}

if(!comprobar_usuario($user)){
	echo "Username given does NOT exists.";
	return;
}

$protegido = comprobar_si_protegido($user);
if($protegido)
	echo "<u>Twitter account protected, so we're only able to get the Bio.</u>";


if($_POST['seleccion']=="fingerprinting")
	require_once("fingerprinting.php");
else
	require_once("analisis.php");
?>
