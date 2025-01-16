<html>
<head>
	<link rel="stylesheet" href="/Projekt_2v8/css/css_shop.css">
	<meta charset='utf-8'></meta>
</head>
<body>
	<div class="background">
		<div class="navigationTable">
			<a href="index.php?idp=3">Strona startowa</a>
			<a href="index.php?idp=7">Poznaj nasze ¿ó³wie</a>
			<a href="index.php?idp=2">Galeria zdjêæ</a>
			<a href="index.php?idp=6">O nas</a>
			<a href="index.php?idp=5">Kontakt</a>
		</div>
		<div class="title">
			<h1><a href="?page=0">Nasz merch</a></h1>
		</div>
		<div class="content">
			<?php
				include("cathegorymanagement.php");
				include("productmanagement.php");
				include("cfg.php");
				include("cart.php");
				session_start();
				$cathegories = new CathegoryManagement($link);
				$products = new ProductManagement($link);
				
				//ZnajdŸ obecn¹ stronê, oblicz iloœæ wszystkich stron
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
				if(isset($_GET['cathegory'])){
					$total_pages = ceil($products->ZliczWKategorii($_GET['cathegory'])/$products->GetPageLimit());
				}else{
					$total_pages = ceil($cathegories->ZliczKategorie()/$cathegories->GetPageLimit());
				}

				//Wyci¹gnij uri strony, zamieñ na string i wyszukaj kategoriê w celu wrzucenia do adresu stron
				$requestUri = $_SERVER['REQUEST_URI'];
				$queryString = parse_url($requestUri, PHP_URL_QUERY);
				parse_str($queryString, $queryParams);
				if (!empty($queryParams)) {
					foreach ($queryParams as $key => $value) {
						if($key=='cathegory'){
							$cathegoryvalue=$value;
							echo $cathegoryvalue;
							break;
						}
						}
				}

				
				//Jeœli kategoria zosta³a wybrana, wyœwietl produkty z tej kategorii, w przeciwnym razie, wyœwietl wszystkie kategorie
				if(isset($cathegoryvalue)){
					$productarray=$products->PokazProdukty($page, $cathegoryvalue);
					echo "<table class='products'><tr><th></th><th>tytul</th><th>opis</th><th>data_utworzenia</th><th>data_modyfikacji</th>
					<th>data_wygasniecia</th><th>cena_netto</th><th>vat</th><th>ilosc_w_magazynie</th>
					<th>dostepnosc</th><th>kategoria</th><th>gabaryt</th><th>zdjecie_link</th>
					</tr>";
					foreach($productarray as $product){
					echo "<tr>";
					echo "<th><form method='POST'><button type='submit' name='addtocart'>Dodaj do koszyka</button><input type='number' name='productcount' value=1></th>";
					echo "<input type='hidden' name='productid' value='".$product['id']."'>";
					echo "<th>".$product["tytul"]."</th>";
					echo "<th>".$product["opis"]."</th>";
					echo "<th>".$product["data_utworzenia"]."</th>";
					echo "<th>".$product["data_modyfikacji"]."</th>";
					echo "<th>".$product["data_wygasniecia"]."</th>";
					echo "<th>".$product["cena_netto"]."</th>";
					echo "<input type='hidden' name='productprice' value='".$product['vat']."'>";
					echo "<th>".$product["vat"]."</th>";
					echo "<input type='hidden' name='productstock' value='".$product['ilosc_w_magazynie']."'>";
					echo "<th>".$product["ilosc_w_magazynie"]."</th>";
					echo "<input type='hidden' name='productavailable' value='".$product['dostepnosc']."'>";
					echo "<th>".$product["dostepnosc"]."</th>";
					echo "<th>".$product["kategoria"]."</th>";
					echo "<input type='hidden' name='productweight' value='".$product['gabaryt']."'>";
					echo "<th>".$product["gabaryt"]."</th>";
					echo "<th>".$product["zdjecie_link"]."</form></th>";
					echo "</tr>";
					}
				}else{
						$cathegoryarray=$cathegories->PokazWszystkieKategorie($page);
						echo "<table class='cathegories'><tr><th>nazwa kategorii</th></tr>";
						foreach($cathegoryarray as $cathegory){
						echo "<tr>";
						echo "<th><a href='?cathegory=".$cathegory["id"]."'>".$cathegory["nazwa"]."</th>";
						echo "</tr>";
					}
					echo "</table>";
				}


				//Zlicz kategorie, produkty i wyœwietl odnoœniki do kolejnych stron
				echo "<p>Kategorii ogó³em: ".$cathegories->ZliczKategorie()."</p>";
				echo "<p>Produktów ogó³em: ".$products->ZliczProdukty()."</p>";
				if(isset($cathegoryvalue)){
					echo "<p>Produktów w kategorii: ".$products->ZliczWKategorii($cathegoryvalue)."</p>";
				}
				echo "<div class='stats'>";
				for ($i = 0; $i < $total_pages; $i++) {
					if ($i == $page) {
						echo "<strong>$i</strong> ";
					} else {
						if (isset($cathegoryvalue)){
							echo "<a href='?page=$i&cathegory=".$cathegoryvalue."'>$i</a> ";
						}else{
							echo "<a href='?page=$i'>$i</a> ";
						}
					}
				}
				echo "</div>";
				//Zaktualizowanie koszyka po dodaniu produktów
				if(isset($_POST['addtocart'])){
					if($_POST['productcount']>$_POST['productstock']){
						echo "W magazynie nie ma tylu produktów";
					}else{
						UpdateCart($_POST['productid'],$_POST['productcount'],$_POST['productweight']);
					}
				}
				//Wyœwietlanie iloœci produktów w koszyku
				if(isset($_SESSION['count'])){
					echo "<form method='POST'>
					<button type='submit' name='cartcompilation'>Koszyk: ".$_SESSION['count']."</button>
					</form>";
				}
				//Skompilowanie koszyka
				if(isset($_POST['cartcompilation'])){
					ob_clean();
					CompileCart($link);
				}
				//Usuwanie rzeczy z koszyka
				if(isset($_POST['deletefromcart'])){
					RemoveFromCart($link);
				}
				?>
		</div>
	</div>
</body>
</html>
