<?php
require('cfg.php');
include('showpage.php');
include('../contact.php');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

function FormularzLogowania(){
	$wynik='
	<div class="logowanie">
	<h1 class="heading">Panel CMS:</h1>
		<div class="logowanie">
			<form method="POST" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
				<table class="logowanie">
					<tr><td class="log4_t">[email]</td><td><input type="test" name="login_email" class="logowanie" /></td></tr>
					<tr><td class="log4_t">[haslo]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
					<tr><td>&nbsp; </td><td><input type="submit" name="x1_submit" class="logowanie" value="Zaloguj" /></td></tr>
					<tr><td>&nbsp; </td><td><input type="submit" name="x2_submit" class="logowanie" value="Zapomnialem hasla" /></td></tr>
				</table>
			</form>
		</div>
	</div>
';
	return $wynik;
}

function ListaPodstron($database){
	$query = 'SELECT * FROM page_list ORDER BY id ASC LIMIT 100';
	$result = mysqli_query($database, $query);

	echo '<form method="POST" name="subpages_count">';
	while($row = mysqli_fetch_array($result)){
		echo $row['id'].' '.$row['page_title'].'<br />
		<button type="submit" name="edit_submit" value="'.$row['id'].'">edytuj</button>
		<button type="submit" name="del_submit" value="'.$row['id'].'">usu�</button><br/>';
	}
		echo '</form>';
}

function EdytujPodstrone($database, $subpage_id){
	ob_clean();

	$query = 'SELECT * FROM page_list WHERE id='.$subpage_id;
	$result = mysqli_query($database, $query);
	$page = mysqli_fetch_array($result);
	if($page == null) return;

	print('
	<form method="POST" name="editing_form">
		<input type="text" name="tytul" value='.$page['page_title'].'></input><br/>
		<textarea name="html">'.$page['page_content'].'</textarea><br/>
		<input type="checkbox" name="is_active">Czy ta strona ma by� stron� aktywn�?</input><br/>
		<button type="submit" name="end_edit" value="'.$subpage_id.'">Zapisz zmiany</button>
	</form>
');

	$_SESSION['edited_subpage'] = $subpage_id;
}

function ZakonczEdytowanie($database,$subpage_id,$new_title,$new_html){
	$query = "UPDATE page_list SET page_title = ?, page_content = ? WHERE id = ?";

    if ($stmt = mysqli_prepare($database, $query)) {
        mysqli_stmt_bind_param($stmt, "ssi", $new_title, $new_html, $subpage_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "B��d przygotowania zapytania: " . mysqli_error($database);
    }
}
function UsunPodstrone($database, $subpage_id){
	$query = "DELETE FROM page_list WHERE id= ?";

	if($stmt = mysqli_prepare($database, $query)){
		mysqli_stmt_bind_param($stmt, 'i', $subpage_id);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
	}else{
		echo "B��d zapytania: ".mysqli_error($database);
	}
}

//system logowania
session_start();
if(isset($_COOKIE["login"])){
	ob_clean();
	ListaPodstron($link);
	}else{

	print(FormularzLogowania());
	//wypelnienie formularza logowania
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
	//formularz przypomnienia hasla
	if(isset($_POST["x2_submit"])){
		ob_clean();
		print(KontaktHaslo());
		if(isset($_POST['email'])){
			PrzypomnijHaslo($_POST['email'],$pass);
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

//funkcjonalność maili kontaktowych
print('
<form method="get">
	<button type="submit" name="contact">skontaktuj się z nami</button>
</form>
');
if(isset($_GET['contact'])){
	ob_clean();
	PokazKontakt();
	if(isset($_POST['wyslij'])){
		WyslijMailNaKontakt($_POST['email']);
	}
}

$nr_indeksu = "169246";
$nrGrupy = "2";
echo "Autor: Cezary Ignaszewski ".$nr_indeksu." grupa ".$nrGrupy."<br/><br/>";
?>