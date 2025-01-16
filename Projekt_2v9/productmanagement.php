<?php
class ProductManagement{
	private $link;
	private $pagelimit = 100;
	function __construct($link){
		$this->link=$link;
	}
	function DodajProdukt($tytul, $kategoria){
		$cathegories = ZnajdzIDKategorii($tytul,$kategoria);
		if(!empty($cathegories)){
			echo "b³¹d dodawania produktu, istnieje juz produkt z takim tytulem w tej kategorii";
			return;
		}

		$query = "INSERT INTO products (matka, nazwa) VALUES (?,?)";

		$stmt = prepare($query);
		$stmt->bind_param("ss", $mother, $name);

		$mother = $matka;
		$name = $nazwa;
		$stmt->execute();

		$stmt->close();

	}
	function UsunProdukt($tytul,$kategoria){
		$query = "DELETE FROM products WHERE tytul=?, kategoria=?";

		$stmt = prepare($query);
		$stmt->bind_param("ss", $mother, $name);

		$mother = $tytul;
		$name = $kategoria;
		$stmt->execute();

		$stmt->close();
	}
	function EdytujProdukt($nazwa, $matka){
		$query = "UPDATE products SET nazwa=?, matka=? WHERE id=?";

		$identyfikator = ZnajdzIDKategorii($matka, $nazwa);

		$stmt = prepare($query);
		$stmt->bind_param("sss", $mother, $name, $identifier);

		$mother = $matka;
		$name = $nazwa;
		$identifier=$identyfikator;
		$stmt->execute();

		$stmt->close();
	}
	function PokazProdukty($page, $kategoria){
		$offset=$page*100;

		$query = "SELECT * FROM products WHERE kategoria=? LIMIT ? OFFSET ?";

		$stmt = $this->link->prepare($query);
		$stmt->bind_param("iii", $cathegory,$limit, $pageoffset);
		$limit = $this->pagelimit;
		$cathegory=$kategoria;
		$pageoffset = $offset;
		$stmt->execute();
		$result = $stmt->get_result();

		$categories = [];
		while ($row = $result->fetch_assoc()) {
			$categories[] = $row;
		}

		$stmt->close();
		return $categories;
	}
	function ZliczProdukty(){
		$total_query = "SELECT COUNT(*) AS total FROM products";
		$total_result = $this->link->query($total_query);
		$total_row = $total_result->fetch_assoc();
		$total_products = $total_row['total'];
		return $total_products;
	}
	function GetPageLimit(){
		return $this->pagelimit;
	}
	function ZliczWKategorii($cathegoryid){
		$total_query = "SELECT COUNT(*) AS total FROM products WHERE kategoria=".$cathegoryid;
		$total_result = $this->link->query($total_query);
		$total_row = $total_result->fetch_assoc();
		$total_products = $total_row['total'];
		return $total_products;
	}
	function ZnajdzIDProduktu($nazwa, $kategoria){
		$query = "SELECT id FROM products WHERE tytul=?, kategoria=?";

		$stmt = prepare($query);
		$stmt->bind_param("ss",$name, $cathegory);

		$name=$nazwa;
		$cathegory=$kategoria;
		$result=$stmt->execute();

		$stmt->close();

		return mysqli_fetch_row($result);
	}
	function PokazProduktyZKategorii($kategoria){
		$query = "SELECT * FROM products WHERE kategoria=?";

		$stmt = prepare($query);
		$stmt->bind_param("s",$cathegory);
		
		$cathegory=$kategoria;
		$result=$stmt->execute();

		$stmt->close();

		return mysqli_fetch_array($result);
	}
}
?>