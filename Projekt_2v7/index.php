	
<?php
require('cfg.php');
require('admin/admin.php');
include('showpage.php');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);


//system logowania
session_start();
if(isset($_COOKIE["login"])){
	ob_clean();
	ListaPodstron($link);
}else{
	print(FormularzLogowania());
	if(isset($_POST["x1_submit"])){
		if($_POST["login_email"] == $login && $_POST["login_pass"] == $pass){
			ob_clean();	
			setcookie("login", "admin", time() + (86400*30), "/"); //żywotność ciasteczka - 1 dzień
			ListaPodstron($link);
		}else{
			ob_clean();	
			echo "złe dane logowania, spróbuj ponownie<br/>";
			print(FormularzLogowania());
		}
	}
}

//funkcjonalność edytowania podstron
if(isset($_COOKIE['login']) && isset($_POST['edit_submit'])){
	ob_clean();
	EdytujPodstrone($link,$_POST['edit_submit']);
}
if(isset($_COOKIE['login']) && isset($_POST['end_edit'])){
	ZakonczEdytowanie($link,$_POST['end_edit'],$_POST['tytul'],$_POST['html']);
}
if(isset($_COOKIE['login']) && isset($_POST['del_submit'])){
	UsunPodstrone($link, $_POST['del_submit']);
}


$nr_indeksu = "169246";
$nrGrupy = "2";
echo "Autor: Cezary Ignaszewski ".$nr_indeksu." grupa ".$nrGrupy."<br/><br/>";


/* deprecated
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


	
*/
?>
