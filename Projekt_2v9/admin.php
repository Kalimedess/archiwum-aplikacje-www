<?php
require('cfg.php');
include('showpage.php');
include('contact.php');
include('cathegorymanagement.php');
include('productmanagement.php');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

$cathegories = new CathegoryManagement($link);
$products = new ProductManagement($link);

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
	$lastquery = 'SELECT * FROM page_list ORDER BY id DESC LIMIT 1';
	$lastresult = mysqli_query($database, $lastquery);


	echo '<form method="POST" name="subpages_count">';
	while($row = mysqli_fetch_array($result)){
		echo $row['id'].' '.$row['page_title'].'<br />
		<button type="submit" name="edit_submit" value="'.$row['id'].'">edytuj</button>
		<button type="submit" name="del_submit" value="'.$row['id'].'">usu�</button><br/>';
	}
		$lastresultindex = mysqli_fetch_assoc($lastresult)["id"]+1;
		echo "<button type='submit' name='add_submit' value='".$lastresultindex."'>dodaj podstrone</button>";
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
		<input type="checkbox" name="is_active" value="1">Czy ta strona ma by� stron� aktywn�?</input><br/>
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

function DodajPodstrone($database, $subpage_id){
	print('
	<form method="POST" name="editing_form">
		<input type="text" name="tytul" value=""></input><br/>
		<textarea name="html"></textarea><br/>
		<input type="checkbox" name="is_active" value="1">Czy ta strona ma by� stron� aktywn�?</input><br/>
		<button type="submit" name="end_pageadd" value="'.$subpage_id.'">Zapisz zmiany</button>
	</form>
	');

	$_SESSION['added_subpage'] = $subpage_id;
}
function ZakonczDodawanie($database, $page_title, $page_content, $activestatus) {
    $query = "INSERT INTO page_list (page_title, page_content, status) VALUES (?,?,?)";
	//sprawdź czy strona powinna być aktywna
	if(isset($activestatus)){
		$status = $activestatus;
	}else{
		$status=0;
	}

    if ($stmt = mysqli_prepare($database, $query)) {
        mysqli_stmt_bind_param($stmt, "ssi", $page_title, $page_content, $status);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        echo "Błąd przygotowania zapytania: " . mysqli_error($database);
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

//funkcjonalność dodawania podstron
if(isset($_COOKIE['login']) && isset($_POST['add_submit'])){
	ob_clean();
	DodajPodstrone($link, $_POST['add_submit']);
}
if(isset($_COOKIE['login']) && isset($_POST['end_pageadd'])){
	ZakonczDodawanie($link,$_POST['tytul'],$_POST['html'],$_POST['is_active']);
}


				
$sql = "SELECT * FROM cathegories";
$result = mysqli_query($link, $sql);

// spisanie kategorii w tablicę
$categories = [];
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

// drzewo kategorii
function buildCategoryTree(array $categories, $parentId = 0) {
    $branch = [];
    foreach ($categories as $cat) {
        if ((int)$cat['matka'] === (int)$parentId) {
            $children = buildCategoryTree($categories, $cat['id']);

            if ($children) {
                $cat['children'] = $children;
            }

            $branch[] = $cat;
        }
    }
    return $branch;
}

// rekurencyjnie wypisywanie drzewa
function printCategoryTree(array $tree, $level = 0) {
    foreach ($tree as $node) { 
        echo "<form method='POST'>".str_repeat("--", $level)."<a href=\"?cathegory=".$node['id']."\">".$node['nazwa']."</a><button name='catdel' value='".$node['id']."'>Usuń</button></form>";
        if (isset($node['children'])) {
            printCategoryTree($node['children'], $level + 1);
        }
    }
}

//edytowanie kategorii
if(isset($_COOKIE['login']) && isset($_GET['cathegory'])){
	$sql = "SELECT * FROM cathegories WHERE id =".$_GET['cathegory']." LIMIT 1";
    $result = mysqli_query($link, $sql);
    $kategoria = mysqli_fetch_assoc($result);
    
    if ($kategoria) {
        echo '<form method="POST">
            <label for="nazwa">Nazwa kategorii:</label><br>
            <input type="text" id="nazwa" name="kategoria_nazwa" 
                   value="'.htmlspecialchars($kategoria['nazwa']).'"><br><br>
            
            <label for="matka">ID kategorii nadrzędnej (matka):</label><br>
            <input type="text" id="matka" name="kategoria_matka" 
                   value="'.htmlspecialchars($kategoria['matka']).'"><br><br>
            
            <button type="submit" name="end_catedit" value="'.htmlspecialchars($kategoria['id']).'">Zapisz zmiany</button>
        </form>'; 

    } 
	else {
        echo "Kategoria o podanym id nie istnieje.";
    } 
}
//Zakonczenie edytowania kategorii
if(isset($_COOKIE['login']) && isset($_POST['end_catedit'])){
	$cathegories->EdytujKategorie($_POST['kategoria_nazwa'],$_POST['kategoria_matka'], $_POST['end_catedit']);
	header('Refresh: 0, url=admin.php');
	exit;
}

//Dodawanie kategorii
$lastquery = 'SELECT * FROM cathegories ORDER BY id DESC LIMIT 1';
$lastresult = mysqli_query($link, $lastquery);
$newindex = mysqli_fetch_assoc($lastresult)['id']+1;
echo "<form method='POST'>";
echo "<button name='catadd' value='".$newindex."'>Dodaj nową kategorię</button>";
echo "</form>";
if(isset($_COOKIE['login']) && isset($_POST['catadd'])){
    echo '<form method="POST">
        <label for="nazwa">Nazwa kategorii:</label><br>
        <input type="text" id="nazwa" name="kategoria_nazwa_add"><br><br>
            
        <label for="matka">ID kategorii nadrzędnej (matka):</label><br>
        <input type="text" id="matka" name="kategoria_matka_add"><br><br>
            
        <button type="submit" name="end_catadd" value="'.$newindex.'">Zapisz kategorię</button>
    </form>'; 
}

//Zakończenie dodawania kategorii
if(isset($_COOKIE['login']) && isset($_POST['end_catadd'])){
	$cathegories->DodajKategorie($_POST['kategoria_matka_add'],$_POST['kategoria_nazwa_add']);
	header('Refresh: 0, url=admin.php');
	exit;
}

//Usuwanie kategorii
if(isset($_COOKIE['login']) && isset($_POST['catdel'])){
	$cathegories->UsunKategorie($_POST['catdel']);
	header('Refresh: 0, url=admin.php');
	exit;
}

if(isset($_COOKIE['login']) && isset($_POST['cathegory'])){
	exit;
}



$tree = buildCategoryTree($categories, 0);
printCategoryTree($tree);

$nr_indeksu = "169246";
$nrGrupy = "2";
echo "Autor: Cezary Ignaszewski ".$nr_indeksu." grupa ".$nrGrupy."<br/><br/>";
?>