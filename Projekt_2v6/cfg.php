<?php
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $baza = 'moja_strona';
    $login = 'phpadmin';
    $pass = '1234';

    $link = mysqli_connect($dbhost,$dbuser,$dbpass);
    if(!$link) echo 'Przerwane połączenie';
    if(!mysqli_select_db($link, $baza)) echo 'nie wybrano bazy';

    ?>