<?php
include("cfg.php");
function UpdateCart($id_prod, $ile_sztuk, $wielkosc){
    // Ustawienie licznika iloœci produktów w koszyku
    if (!isset($_SESSION['count']))
    {
        $_SESSION['count'] = 1;
    }
    else
    {
        $_SESSION['count']++;
    }

    $nr = $_SESSION['count']; // Nadanie numeru dla produktu w koszyku

    $prod[$nr]['id_prod']   = $id_prod;
    $prod[$nr]['ile_sztuk'] = $ile_sztuk;
    $prod[$nr]['wielkosc']  = $wielkosc;
    $prod[$nr]['data']      = time();

    // Stworzenie dwuwymiarowej numeracji — dla jednowymiarowej tablicy
    $nr_0 = $nr.'_0';
    $nr_1 = $nr.'_1';
    $nr_2 = $nr.'_2';
    $nr_3 = $nr.'_3';
    $nr_4 = $nr.'_4';

    // Zapisanie w tablicy sesji danych produktów
    $_SESSION[$nr_0] = $nr;
    $_SESSION[$nr_1] = $prod[$nr]['id_prod'];
    $_SESSION[$nr_2] = $prod[$nr]['ile_sztuk'];
    $_SESSION[$nr_3] = $prod[$nr]['wielkosc'];
    $_SESSION[$nr_4] = $prod[$nr]['data'];
}

//skompiluj koszyk
function CompileCart($database){
       

    //na wypadek gdyby sesja z jakiegoœ powodu siê zresetowa³a
    if(isset($_SESSION['count'])){
	    
        $cumulative_price = 0;
        echo "<table class='products'><tr><th>tytul</th><th>cena brutto jednostkowa</th><th>ilosc</th><th>data dodania do koszyka</th>
		</tr>";
        //pomin jesli index sesji nie istnieje
        for($i=1;$i<=$_SESSION['count'];$i++){
            if(!isset($_SESSION[$i.'_0'])){
                continue;
            }

            //wyci¹gnij dane o produkcie z bazy danych
            $product=GetProductInfo($database, $_SESSION[$i.'_1']);
            $cumulative_price = $cumulative_price+$product['vat']*$_SESSION[$i.'_2'];
            echo "<tr>";
            echo "<th>".$product['tytul']."</th>";
            echo "<th>".$product['vat']."</th>";
		    echo "<th>".$_SESSION[$i.'_2']."</th>";
		    echo "<th>".$_SESSION[$i.'_4']."</th>";
            echo "<th><form method='POST'><button type='submit' name='deletefromcart' value='".$_SESSION[$i.'_0']."'>Usun z koszyka</button></form></th>";
		    echo "</tr>";
        }
        echo "<tr><th>Cena calego zamowienia</th><th>".$cumulative_price."</th></tr>";
        echo "</table>";
	}
}

function GetProductInfo($database, $productid){
        $query = "SELECT * FROM products WHERE id=?";

		$stmt = $database->prepare($query);
		$stmt->bind_param("i", $product);
		$product=$productid;
		$stmt->execute();
		$result = $stmt->get_result();

		$stmt->close();
		return mysqli_fetch_assoc($result);
}

function RemoveFromCart($database){
    unset($_SESSION[$_POST['deletefromcart'].'_0']);
    unset($_SESSION[$_POST['deletefromcart'].'_1']);
    unset($_SESSION[$_POST['deletefromcart'].'_2']);
    unset($_SESSION[$_POST['deletefromcart'].'_3']);
    unset($_SESSION[$_POST['deletefromcart'].'_4']);
    ob_clean();
    CompileCart($database);
}
?>