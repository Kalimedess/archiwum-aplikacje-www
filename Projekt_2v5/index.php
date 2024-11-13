	
<?php
require('cfg.php');
include('showpage.php');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

if($_GET["idp"] == "") $strona = '3';
if($_GET["idp"] == "historia") $strona = '4';
if($_GET["idp"] == "galeria") $strona = '2';
if($_GET["idp"] == "kontakt") $strona = '5';
if($_GET["idp"] == "opis") $strona = '6';
if($_GET["idp"] == "poznaj") $strona = '7';
if($_GET["idp"] == "filmy") $strona = '1';

$wbpg = PokazPodstrone($strona, $link);
if(!is_null($wbpg)){
    print($wbpg);
}else{
    print("błąd przy wczytywaniu strony");
}


$nr_indeksu = "169246";
$nrGrupy = "2";
echo "Autor: Cezary Ignaszewski ".$nr_indeksu." grupa ".$nrGrupy."<br/><br/>";
?>
