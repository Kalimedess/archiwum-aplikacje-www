<?php
	
	function PokazKontakt(){
		echo '<html>
			<head>
				<link rel="stylesheet" href="css/contact.css">
			</head>
			<body>
				<form method="POST">
					<input type="text" name="email" placeholder="twoj email"></br>
					<input type="text" name="tytul" placeholder="tytul"></br>
					<input type="text" name="tresc" placeholder="tresc"></br>
				</form>
				<a href="/Projekt_2v7">Powrót na stronę główną</a>
			</body>
		</html>
		';
	}
	function WyslijMailNaKontakt($odbiorca){
		if(empty($_POST['email']) || empty($_POST['temat']) || empty($_POST['tresc'])){
			echo 'email nie zostal uzupelniony';
			PokazKontakt();
		}else{
			$header = "From: formularz kontaktowy <". $_POST['email'].">\n";
			$header .= "Return-Path: <".$_POST['email'].">\n";

			mail($odbiorca, $_POST['tytul'], $_POST['tresc']);
			echo "wiadomosc wyslana.";
		}
	}
	function KontaktHaslo(){
		echo '<html>
			<head>
				<link rel="stylesheet" href="css/contact.css">
			</head>
			<body>
				<form method="POST">
					<h1>Podaj swojego maila, wyślemy na niego hasło z nim powiązane</h1>
					<input type="text" name="email" placeholder="twoj email"></br>
					<input type="submit" name="przypomnijhaslo" value="Wyślij przypomnienie">
				</form>
				<a href="/Projekt_2v7">Powrót na stronę główną</a>
			</body>
		</html>
		';
	}

	function PrzypomnijHaslo($odbiorca,$haslo){

		mail($odbiorca, "Przypomnienie hasla panelu administratora", "Twoje haslo to: ".$haslo);
	}
?>