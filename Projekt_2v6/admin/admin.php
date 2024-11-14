<?php
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

	echo '<form method="POST", name="subpages_count">';
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
	$page = mysqli_fetch_row($query);
	if($page == null) return;
	print('
	<form method="POST", name="editing_form">
	<input type="text" name="tytul">'.$page['page_title'].'</input><br/>
	<textarea name="html">'.$page['page_content'].'</textarea><br/>
	<input type="checkbox" name="is_active">Czy ta strona ma by� stron� aktywn�?</input><br/>
	<button type="submit" name="end_edit" value="'.$subpage_id.'">Zapisz zmiany</button>
	</form>
');
}

function ZakonczEdytowanie($database,$subpage_id,$new_title,$new_html){
	$query = 'UPDATE page_list SET page_title='.$new_title.', page_content='.$new_html.'WHERE id='.$subpage_id;
	mysqli_query($database, $query);
}
?>