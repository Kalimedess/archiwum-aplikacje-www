	
<?php
require('cfg.php');
include('showpage.php');
include('contact.php');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

//System ładowania podstron przez witrynę
//trzeba pamiętać o wrzucaniu id podstrony jaką ma w bazie danych w value przycisków nawigacyjnych które mają do niej prowadzić
if(isset($_GET["idp"])){
    $wbpg = PokazPodstrone($_GET["idp"], $link);
}else{
    $wbpg = PokazPodstrone('3',$link);
}

if(!is_null($wbpg)){
    print($wbpg);
}else{
    print("błąd przy wczytywaniu strony");
}


print("<div class='author'
Autor: Cezary Ignaszewski ".$nr_indeksu." grupa ".$nrGrupy."<br/><br/>
</div>");
?>
