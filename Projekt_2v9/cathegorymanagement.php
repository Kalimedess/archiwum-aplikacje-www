<?php
class CathegoryManagement{
	private $link;
	private $pagelimit=100;

	function __construct($linkage){
		$this->link=$linkage;
	}
	function DodajKategorie($matka, $nazwa){
		$cathegories = $this->ZnajdzIDKategorii($matka,$nazwa);
		if(!empty($cathegories)){
			echo "b³¹d dodawania kategorii, nazwa i matka jest taka sama jak ju¿ istniej¹ca kategoria";
			return;
		}

		$query = "INSERT INTO cathegories (matka, nazwa) VALUES (?,?)";

		$stmt = $this->link->prepare($query);
		$stmt->bind_param("ss", $mother, $name);

		$mother = $matka;
		$name = $nazwa;
		$stmt->execute();

		$stmt->close();
		

	}
	function UsunKategorie($id){
		$query = "DELETE FROM cathegories WHERE id=?";

		$stmt = $this->link->prepare($query);
		$stmt->bind_param("s", $identifier);

		$identifier = $id;
		$stmt->execute();

		$stmt->close();
	}
	function EdytujKategorie($nazwa, $matka, $id){
		$query = "UPDATE cathegories SET nazwa=?, matka=? WHERE id=?";

		$identyfikator = $id;

		$stmt = $this->link->prepare($query);
		$stmt->bind_param("sss", $name, $mother, $identifier);

		$mother = $matka;
		$name = $nazwa;
		$identifier=$identyfikator;
		$stmt->execute();

		$stmt->close();
	}
	function PokazWszystkieKategorie($page){
		$offset=$page*100;

		$query = "SELECT * FROM cathegories LIMIT ? OFFSET ?";

		$stmt = $this->link->prepare($query);
		$stmt->bind_param("ii", $limit, $pageoffset);
		$limit = $this->pagelimit;
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

	function ZliczKategorie(){
		$total_query = "SELECT COUNT(*) AS total FROM cathegories";
		$total_result = $this->link->query($total_query);
		$total_row = $total_result->fetch_assoc();
		$total_products = $total_row['total'];
		return $total_products;
	}
	function GetPageLimit(){
		return $this->pagelimit;
	}
	function ZnajdzIDKategorii($matka, $nazwa){
		$query = "SELECT id FROM cathegories WHERE nazwa=? AND matka=?";

		$stmt = $this->link->prepare($query);
		$stmt->bind_param("ss", $name, $mother);

		$name=$nazwa;
		$mother=$matka;
		$stmt->execute();
		$result = $stmt->get_result();

		$stmt->close();

		return mysqli_fetch_row($result);
	}
	function ZnajdzNazweMatki($idmatki){
		$query = "SELECT nazwa FROM cathegories WHERE id=?";

		$stmt = $link->prepare($query);
		$stmt->bind_param("s",$id);

		$id=$idmatki;
		$result=$stmt->execute();

		$stmt->close();

		return mysqli_fetch_row($result);
	}
}
?>