
<?php

	function lagSideMeny($db) {
		$stmt = $db->prepare("SELECT * FROM vikerfjell.meny");
		mysqli_set_charset($db, "UTF8");
		$stmt->execute();
		$result = $stmt->get_result();

		while($row = $result->fetch_assoc()) {
			$tekst = $row['tekst'];
			$idmeny = $row['idmeny'];

		}
	}

		$array = "";
		$idmenymeny ="";

	function adminRediger($db) {
		global $idmenymeny;
		global $array;
		global $arrayNavn;
		$array = array();
		$arrayNavn = array();
		$stmt = $db->prepare("SELECT * FROM vikerfjell.meny");
		mysqli_set_charset($db, "UTF8");
		$stmt->execute();
		$result = $stmt->get_result();
		$sub_id = '';
		while($row = $result->fetch_assoc()){
			$idmenymeny = $row['idmeny'];
			$kontroll = null;
			$tekst = $row['tekst'];
			$side = $row['side'];
			$array[] = $idmenymeny;
			$arrayNavn[] = $tekst;

			$_SESSION['idmenymeny'] = $idmenymeny;
			

			echo("<form method='POST'>");
			echo("<li><button name='knappenavn' value=$idmenymeny>$tekst</button>");
			echo("</li>"); /*
			$sub_stmt = $db->prepare("SELECT * from submeny WHERE meny_idmeny = $idmenymeny");
			$sub_stmt->execute();
			$sub_result = $sub_stmt->get_result(); */
			echo("</form>");
		}
	}
	function hentBilde($db) {
		global $idbildemeny;
		global $arrayB;
		global $arrayBNavn;
		$arrayB = array();
		$arrayNavn = array();
		$stmt = $db->prepare("SELECT * FROM vikerfjell.bilder");
		mysqli_set_charset($db, "UTF8");
		$stmt->execute();
		$result = $stmt->get_result();
		$sub_id = '';
		while($row = $result->fetch_assoc()){
			$idbildemeny = $row['idbilder'];
			$kontroll = null;
			$bildenavn = $row['hvor'];
			$arrayB[] = $idbildemeny;
			$arrayBNavn[] = $bildenavn;

			$_SESSION['idbildemeny'] = $idbildemeny;		
	}
}
?>
