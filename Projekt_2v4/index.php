<html>
<head>
</head>
	
<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
if($_GET["idp"] == "") $strona = 'html/glowna.html';
if($_GET["idp"] == "historia") $strona = 'html/historia.html';
if($_GET["idp"] == "galeria") $strona = 'html/galeria.html';
if($_GET["idp"] == "kontakt") $strona = 'html/kontakt.html';
if($_GET["idp"] == "opis") $strona = 'html/opis.html';
if($_GET["idp"] == "poznaj") $strona = 'html/poznaj.html';
if($_GET["idp"] == "filmy") $strona = 'html/filmy.html';
if(file_exists($strona)){
	include($strona);
}else{
	echo "Nie znaleziono pliku";
}

$nr_indeksu = "169246";
$nrGrupy = "2";
echo "Autor: Cezary Ignaszewski ".$nr_indeksu." grupa ".$nrGrupy."<br/><br/>";
?>

</html>