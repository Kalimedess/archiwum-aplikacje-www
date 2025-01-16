<?php
	require('cfg.php');
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
					<input type="submit" name="wyslij" value="Napisz do nas!">
					<a href="admin.php">Powrót na stronę główną</a>
				</form>
				
			</body>
		</html>
		';
	}
	function WyslijMailNaKontakt($odbiorca){
		//sprawdzenie czy formularz został w wypełniony
		if(empty($_POST['email']) || empty($_POST['tytul']) || empty($_POST['tresc'])){
			ob_clean();
			print('<div class="denial" style="text-align:center; font-size: 20px;">
				email nie zostal uzupelniony
			</div>
			');
			PokazKontakt();
		}else{
			$header = "From: formularz kontaktowy <". $_POST['email'].">\n";
			$header .= "Return-Path: <".$_POST['email'].">\n";

			mail($odbiorca, $_POST['tytul'], $_POST['tresc']);
			print('<div class="acceptance" style="text-align:center; font-size: 20px;">
				wiadomosc wyslana.
			</div></br>
			');
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
				<a href="admin.php">Powrót na stronę główną</a>
			</body>
		</html>
		';
	}

	function PrzypomnijHaslo($odbiorca,$haslo){

		mail($odbiorca, "Przypomnienie hasla panelu administratora", "Twoje haslo to: ".$haslo);
	}
?>