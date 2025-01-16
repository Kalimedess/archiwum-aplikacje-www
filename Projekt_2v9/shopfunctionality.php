<?php
				include("cathegorymanagement.php");
				include("productmanagement.php");
				include("cfg.php");
				$cathegories = new CathegoryManagement($link);
				$products = new ProductManagement($link);
				
				$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
				$total_pages = ceil($cathegories->ZliczKategorie()/$cathegories->GetPageLimit());

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

				
				//Jeœli kategoria zosta³a wybrana, wyœwietl produkty z tej kategorii, w przeciwnym razie, wyœwietl wszystkie kategorie
				if(isset($cathegoryvalue)){
					$productarray=$products->PokazProdukty($page, $cathegoryvalue);
					echo "<table class='products'><tr><th></th><th>tytul</th><th>opis</th><th>data_utworzenia</th><th>data_modyfikacji</th>
					<th>data_wygasniecia</th><th>cena_netto</th><th>vat</th><th>ilosc_w_magazynie</th>
					<th>dostepnosc</th><th>kategoria</th><th>gabaryt</th><th>zdjecie_link</th>
					</tr>";
					foreach($productarray as $product){
					echo "<tr>";
					echo "<th><a href=''>Dodaj do koszyka</a></th>";
					echo "<th>".$product["tytul"]."</th>";
					echo "<th>".$product["opis"]."</th>";
					echo "<th>".$product["data_utworzenia"]."</th>";
					echo "<th>".$product["data_modyfikacji"]."</th>";
					echo "<th>".$product["data_wygasniecia"]."</th>";
					echo "<th>".$product["cena_netto"]."</th>";
					echo "<th>".$product["vat"]."</th>";
					echo "<th>".$product["ilosc_w_magazynie"]."</th>";
					echo "<th>".$product["dostepnosc"]."</th>";
					echo "<th>".$product["kategoria"]."</th>";
					echo "<th>".$product["gabaryt"]."</th>";
					echo "<th>".$product["zdjecie_link"]."</th>";
					echo "</tr>";
					}
				}else{
						$cathegoryarray=$cathegories->PokazWszystkieKategorie($page);
						echo "<table class='cathegories'><tr><th>id</th><th>matka</th><th>nazwa</th></tr>";
						foreach($cathegoryarray as $cathegory){
						echo "<tr>";
						echo "<th>".$cathegory["id"]."</th>";
						echo "<th>".$cathegory["matka"]."</th>";
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
				?>