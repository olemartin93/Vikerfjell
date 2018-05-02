<?php
// Funksjoner som blir brukt i adminmeny.php for å redigere menyen.
	function listmeny($db) {
		$stmt = $db->prepare("SELECT * FROM vikerfjell.meny");
		mysqli_set_charset($db, "UTF8");
		$stmt->execute();
		$result = $stmt->get_result();

		$idmeny = $row['idmeny'];

		while($row = $result->fetch_assoc()){
			$kontroll = null;
			$tekst = $row['tekst'];
			$side = $row['side'];
			$phpadd = "php/";

			echo("<li><a href=$phpadd$side>$tekst</a>");
		}
	}
	function visLeggtil($db){
		//$idmeny = mysqli_real_escape_string($db,$_POST['idmeny']);
		//$tekst = mysqli_real_escape_string($db,$_POST['tekst']);
		//$side = mysqli_real_escape_string($db,$_POST['side']);
		//$lsql = "INSERT INTO meny (tekst, side) VALUES ('$tekst', '$side')";
		//$ssql = mysqli_query($db,$lsql);

	}
	function visred($db) {
		$stmt = $db->prepare("SELECT * FROM vikerfjell.meny");
		mysqli_set_charset($db, "UTF8");
		$stmt->execute();
		$resultat = $stmt->get_result();

		while($row = $resultat->fetch_assoc()) {
			$tekst = $row['tekst'];
			$idmeny = $row['idmeny'];
			$rekke = $row['rekke'];
			
			echo("<form action='' method='POST' id='rForm' class='rForm'>");
			echo("<tr><td>$tekst");
			echo("<td><input type='text' class='w30' name='tekst' value = '$tekst'></td>\n");
			//echo("<input type='hidden' name='id' value=>")
			echo("<td><input type='number' class='w30' name='rekke' onkeypress='return isNumberKey(event)' value='$rekke'></td>\n");
			echo("<input type='hidden' name='gRekke' value='$rekke'>");
			echo("<td><button type='submit' name='send' value='$idmeny'>Oppdater</button></td>\n");
			echo("</tr></form>");
		}
	}
	function addBilde($db){
		$stmtmt = $db->prepare("SELECT * FROM vikerfjell.bilder");
		mysqli_set_charset($db, "UTF8");
		$stmtmt->execute();
		$resultat = $stmtmt->get_result();

		while($row = $resultat->fetch_assoc()){
			$idbilder = $row['idbilder'];
			$bildenavn = $row['hvor'];
			echo("<form action='' method='POST' id='rForm' class='rForm'>");
			echo("<tr><td>$bildenavn</td>");
			echo("<td><input type='text' class='w30' name='tekst'></td>\n");
			echo("<td><button type='submit' name='send' value='$idbilder'>Oppdater</button></td>\n");
			echo("</tr></form>");
			$message="Bilde lagt til";
		}

		echo("<p class='positiv'> $message </p>");
	}
	function visslett($db) {
		$stmt = $db->prepare("SELECT * FROM vikerfjell.meny");
		mysqli_set_charset($db, "UTF8");
		$stmt->execute();
		$resultat = $stmt->get_result();

		while($row = $resultat->fetch_assoc()) {
			$tekst = $row['tekst'];
			$idmeny = $row['idmeny'];
			$side = $row['side'];
			echo("<form action='' method='POST' id='sForm' class='sForm'>");
			echo("<tr><td class='w30' id='td1_1'>$tekst</td>\n");
			echo("<td class='w25' id='td2_1'>$side</td>\n");
			echo("<td><button type='submit' name='slett' value='$idmeny'>Slett</button></td>\n");

			echo("</tr>");
		}
	}
	function visslettBilde($db) {
		$stmt = $db->prepare("SELECT * FROM vikerfjell.bilder");
		mysqli_set_charset($db, "UTF8");
		$stmt->execute();
		$resultat = $stmt->get_result();

		while($row = $resultat->fetch_assoc()) {
			$tekst = $row['hvor'];
			$idbilde = $row['idbilder'];
			echo("<form action='' method='POST' id='sForm' class='sForm'>");
			echo("<tr><td class='w30' id='td1_1'>$tekst</td>\n");
			echo("<td><button type='submit' name='slett' value='$idbilde'>Slett</button></td>\n");
			echo("</tr>");
		}
	}
	function slettBilde($db, $id){
		$slsqlr = $db->prepare("DELETE FROM bilder WHERE bilder.idbilder = '$id'");
		$melding="";
		$class="";
		if(!$slsqlr->execute()){
			$melding = "Kan ikke slette et bilde som er koblet til et innlegg";
			$class ="negativ";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;
		} else {
			$melding = "Bilde er slettet";
			$class = "positiv";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;

		}
	}

	// Funksjon som sletter ett menyelement fra menylinjen
	function slettmeny($db,$id) {
			$melding="";
			$class ="";
			$slsql = $db->prepare("DELETE FROM meny WHERE meny.idmeny = '$id'
				AND meny.idmeny
				NOT IN (SELECT innhold.idmeny FROM innhold WHERE innhold.idmeny = '$id')");
			$slsql->execute();
			$teller = mysqli_affected_rows($db);
			if($teller==0){
				$melding = "kan ikke slette menyelement som har innhold";
				$class ="negativ";
				$_SESSION['melding'] = $melding;
				$_SESSION['class'] = $class;
			} else {
				$melding = "menyelement slettet";
				$class = "positiv";
				$_SESSION['melding'] = $melding;
				$_SESSION['class'] = $class;

		}

	}
	function slettBildet($db, $id){
		$melding="";
		$class ="";
		$sslsql = $db->prepare("DELETE FROM bilder WHERE bilder.idbilder = '$id'");
		$slsql->execute();
		$teller = mysqli_affected_rows($db);
		if($teller==0){
			$melding = "kan ikke slette bildet";
			$class ="negativ";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;
		} else {
			$melding = "bilde slettet";
			$class = "positiv";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;

	}
	}
	//slette filer
	function slettfil($db,$id) {
		// Funksjon som sletter filen og kaller på funksjonen slettmeny();
		$ssql = $db->prepare("SELECT * FROM meny WHERE idmeny = '$id'");
		$ssql->execute();
		$resultat = $ssql->get_result();
		$hovedside = "";
		while($row = $resultat->fetch_assoc()) {
			$sidenavn = $row['side'];
			$tittel = $row['tekst'];
			@unlink("../" . "sidene" . "/" . $tittel . "/" . $sidenavn);
			@unlink("../" . "sidene" . "/" . $sidenavn);
			@rmdir("../" . "sidene" . "/". $sidenavn);
		}
		slettmeny($db,$id);
	}
?>
