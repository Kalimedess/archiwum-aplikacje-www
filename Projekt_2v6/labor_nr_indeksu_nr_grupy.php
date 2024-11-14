<html>
<head>
</head>
<body>
<?php
$nr_indeksu = '169246';
$nrGrupy = 'ISI2';
echo 'Cezary Ignaszewski '.$nr_indeksu.' grupa '.$nrGrupy.'<br /><br />';
echo 'zastosowanie metody include() i require_once()<br />';
echo '<br/>';
echo 'include() kompiluje kod PHP z innego pliku przekazanego jako argument metody. istnienie pliku nie jest wymagane do kontynuowania działania aplikacji. <br/>
require_once() za to wymaga aby plik przekazywany jako argument istniał przed dalszą kompilacją strony (inaczej będzie rzucać wyjątek), co jest sprawdzane tylko raz (w odróżnieniu od require)';
include('trash.php');
echo '<br/>'.$trash;
require_once('trash2.php');
echo '<br/>'.$trash2;
echo '<br/><br/>if sprawdza czy warunek jest spełniony, jeśli tak (wartość true), wykonuje blok kodu powiązany z nim, jeśli nie (wartość false), wykonuje blok kodu powiązany z else';
$a = 10;
$b = 15;
if($a > $b){
	echo '<br/><br/>a jest większe od b';
}else{
	echo '<br/><br/>b jest większe od a';
}
echo '<br/> blok elseif pozwala na dodawanie większej ilości ifów, a sprawdzanie ich zachodzi od góry do dołu, gdzie jeśli którykolwiek zostanie spełniony zostanie wykonany ich blok wartości true';
$a = 15;
$b = 15;
if($a > $b){
	echo '<br/><br/>a jest większe od b';
}elseif($a==$b){
	echo '<br/><br/>a jest równe b';
}else{
	echo '<br/><br/>b jest większe od a';
}
echo '<br/>pętla while jest wykonywana tak długo dopóki warunek pętli zwraca wartość true<br/>';
$i = 1;
while ($i < 10) {
    echo $i++;
}
echo '<br/>typy zmiennych GET, POST i SESSION zawierają tablice asocjacyjne zmiennych przekazanych skryptowi poprzez parametry URL. W ten sposób można przekazać skryptowi PHP dane ze strony HTML(GET),
otworzyć zapisane już zmienne(POST), czy uzyskać dostęp do wszystkich zapisanych zmiennych w danej sesji PHP(SESSION)<br/>';
?>
</body>
</html>