<?php
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';
    $login = 'phpadmin';
    $pass = '1234';
    $mail = 'kalimedesss@gmail.com';
    $persistentProjectPath = 'Projekt_2v8';
    $nr_indeksu = "169246";
    $nrGrupy = "2";

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $link = mysqli_connect($dbhost,$dbuser,$dbpass);
    if(!$link) echo 'Przerwane połączenie';
    if(!mysqli_select_db($link, $baza)) echo 'nie wybrano bazy';

    ?>