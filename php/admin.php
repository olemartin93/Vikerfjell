<?php
	session_start();
	include("db.php");
	include("meny.php");
	include("lagehtml.php");
	include("sessionTimeout.php");
  if(!$_SESSION['bruker']){
    header("location:logginn.php");
    die('Kunne ikke koble til databasen' . mysqli_connect_error());
  }
  
	
	$melding = "";
	$_SESSION['melding']=$melding;
?>
<!DOCTYPE html>
<html>
  <head>
    <!-- laget av: Ole, Kontrollert av Gabriel -->
    <meta name="author" content="GOTC ~ Gruppe 1"> <!--Forfatter -->
    <meta name="keywords" content="Vikerfjell, høyfjell, Hønefoss, vestre Ådal, Buskerud"> <!--søkeord -->
    <meta http-equiv="content-type" content="text/html; charset=utf-8"> <!--Spesifiserer bokstav-kode type som er brukt -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><!--Sørger for responsivt design -->
	<link rel="stylesheet" href="VisitVikerfjell/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../CSS/admin.css"><!--Linker til CSS-dokumentet -->
    <!--
	**faviconer for forskjellige størrelser under.
	-->
	<link rel="icon" type="image/png" href="../Bilder/cog-0.png" sizes="16x16">  
	<link rel="icon" type="image/png" href="../Bilder/cog-3.png" sizes="32x32">  
	<link rel="icon" type="image/png" href="../Bilder/cog-5.png" sizes="96x96">
    <title>Visit Vikerfjell</title> <!--Logonavn på browser-tabben -->
  </head>
   <body>
    <!-- laget av: Tobias, kontrollert av Christian -->
    <header>
      <!--bildene under er header-bildet og logen som ligger på det. -->
      <div id="logginn">
				<nav id="nav" role="navigation">
					<ul>
						<li><a href="admin.php">Sider</a></li>
						<li><a href="adminmeny.php">Meny</a></li>
						<li><a href="adminbruker.php">Brukere</a></li>
						<li><a href="adminbilder.php">Bilder</a></li>
						<div id="bruker">
						<button onclick="window.location='../php/loggut.php'"><i class="fa fa-sign-out" aria-hidden="true"></i> Logg ut</button>
						<button onclick="window.location='../php/admin.php'"><i class="fa fa-user" aria-hidden="true"></i> Bruker: <?php echo($_SESSION['bruker']); ?></button>
						</div>
					</ul>
				</nav>
    	</div>
  	</header>
		<div class="aside-div">
			<aside>
				<h2 id="overskiftmeny">Sider</h2>
				<ul class = "sidemeny">
				<?php
					adminRediger($db);
				?>
				</ul>
			</aside>
	</div>

    <div class="siderContent">
	<div class="wrapper_main">

	<button onclick="window.location='admin.php?nyttInnlegg'" type="submit" form="form1" value="Submit" name="nyttInnlegg">Lag nytt innlegg</button>
	<button onclick="window.location='admin.php'" type="submit" form="form1" value="Submit" name="skjulInnlegg">Close</button>
	<p class='<?php echo($_SESSION['class']);?>'> <?php echo($_SESSION['melding']); ?></p>

  </div>
<?php
	global $nyttInnlegg;
	$nyttInnlegg = false;


	if (isset($_GET['nyttInnlegg'])) {
		$nyttInnlegg = true;

	}
	if(isset($_GET['skjulInnlegg'])){
		$nyttInnlegg = false;
	}
	if (!($db = new vikerfjell())) {
		die("Ingen forbindelse til databasen");
	}
	$i = 0;

//hente form for å legge til innhold
	if ($nyttInnlegg) {

		echo("<form action='' method='POST'>");
	      echo("<fieldset>");
	        echo("<legend>skriv her</legend>");
					echo("<p>");
						echo("<p>Velg side innlegget skal lages på:<p>");
						echo("<select name='idnavn'>");
							adminRediger($db);
							for($i=0; $i<sizeof($array); $i++){
								echo("<option value=".$array[$i].">".$arrayNavn[$i]."</option>");
							}
						echo("</select>");
					echo("</p>");
	        echo("<p>");
	        echo("<label>Tittel</label>");
			echo("</p>");
	        echo("<input name='textoverskrift' type = 'text'
				id = 'txtOverskrift'
	            value = 'tittel'/>");
	        echo("<p>");
					echo("<label>Ingress</label>");
			echo("</p>");
	        echo("<input name='textIngress' type = 'text'
				id = 'txtIngress'
	            value = 'Ingress'/>");
	        echo("<p>");

	        echo("<label>Bilde</label>");
			echo("</p>");
			echo("<p>");
	        echo("<select name='fileToUpload'>");
							hentBilde($db);
							for($i=0;$i<sizeof($arrayB);$i++){
								echo("<option id ='bildetext' value=".$arrayB[$i].">".$arrayBNavn[$i]."</option>");
							}
					echo("</option>
							</select>");
				echo("<input type='hidden' name='idbilde' value='0'>");
	        echo("</p><br>");
	        echo("<p>");
	        echo("<label>Brødtekst</label>");
			echo("</p>");
	        echo("<textarea name='textbrødtekst'
				id = 'txtBrødtekst'
	            rows = '3'
	            cols = '80'>'brødtekst'</textarea><br>");
			echo("<button type = 'button' id = lenke onClick = 'leggLenke()'>Legg til lenke</button>");
			echo("<button name='knapplagre' type='submit' value='btnLagre'>Lagre</button>");
	      echo("</fieldset>");
	    echo("</form>");
		echo("");
	}

	//Legge til innhold
	if (isset($_POST['knapplagre'])) {


		$idmeny = mysqli_real_escape_string($db,$_POST['idnavn']);
		$tittel = mysqli_real_escape_string($db,$_POST['textoverskrift']);
		$tekst = mysqli_real_escape_string($db,$_POST['textbrødtekst']);
		$ingress= mysqli_real_escape_string($db,$_POST['textIngress']);
		$idbilder= mysqli_real_escape_string($db,$_POST['fileToUpload']);
		
		$side = str_replace(' ', '', $tittel);

		$stmtt = "INSERT INTO innhold (tittel, ingress, tekst, side, idmeny) VALUES('$tittel', '$ingress', '$tekst', '$side.html', '$idmeny' )";
		$ssql = mysqli_query($db,$stmtt);
		if(!$ssql){
			$melding = "Ett innhold med den tittelen eksisterer allerede, velg et annet";
			$class = "negativ";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;
			
			
			/*echo("<script>
			<window.location.href='admin.php'>;
			</script>
			");*/
		} else {
			$idinnhold = mysqli_insert_id($db);
			
			$melding = "Innlegg opprettet";
			$class = "positiv";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;
			

			//$istm = "INSERT INTO bilder (hvor) VALUES('$bildelink')";
			//$bsql = mysqli_query($db,$istm);
			//$idbilder = $db->insert_id;

			$sstmt = "INSERT INTO bilderinnhold (_idbilder,_idinnhold) VALUES('$idbilder','$idinnhold')";
			$ssqlq = mysqli_query($db,$sstmt);
			$nyttInnlegg = false;
			echo("<script>
			<window.location.href='admin.php'>;
			</script>
			");
			lagehtml($db);
		}	echo("<p class = '$class'> $melding <p>");
	}


// Hente innhold + det som hører til(bilder)
	if (@!$_POST['knappenavn']){
		$melding ="";
	} else{
		$melding ="";
		@$knappemann = $_POST['knappenavn'];
		$stmt = $db->prepare("SELECT innhold.*, bilder.hvor, bilder.idbilder FROM innhold LEFT OUTER JOIN bilderinnhold ON
			(innhold.idinnhold=bilderinnhold._idinnhold) LEFT OUTER JOIN bilder ON
			(bilderinnhold._idbilder=bilder.idbilder) WHERE innhold.idmeny=$knappemann");

		mysqli_set_charset($db, "UTF8");
		$stmt->execute();
		$result = $stmt->get_result();
		while($row = $result->fetch_assoc()){
			$tittel = $row['tittel'];
			$ingress = $row['ingress'];
			$id = $row['idinnhold'];
			$idbilder = $row['idbilder'];
			$brødtekst = $row['tekst'];
			$bilder = $row['hvor'];


	echo("<form action='' name='form".$id."' method='POST'>");
      echo("<fieldset>");
        echo("<legend>skriv her</legend>");
        echo("<p>");
        echo("<label>Tittel</label>");
		echo("</p>");
        echo("<input name='textoverskrift' type = 'text'
			id = 'txtOverskrift'
            value = '".$tittel."'/>");
				echo("<p>");
					 echo("<label>Ingress</label>");
			 	echo("</p>");
					 echo("<input name='textIngress' type = 'text'
				 id = 'txtIngress'
							 value = '".$ingress."'/>");
					 echo("<p>");
			  echo("<p>");
        echo("<label>Bilde</label>");
		echo("</p>");
		echo("<p>Nåværende bilde: ".$bilder."</p>");

		echo("<select name='bilde'>");
        hentBilde($db);
				for($i=0;$i<sizeof($arrayB);$i++){
					echo("<option name ='bilde' value=".$arrayB[$i].">".$arrayBNavn[$i]."</option>");

				}
				echo("<input type='hidden' name='idbilde' value='".$idbilder."'>");
		echo("</select>");

        echo("<p>");
        echo("<label>Brødtekst</label>");
		echo("</p>");
        echo("<textarea name='txtBrødtekst'
			id = 'txtBrødtekst".$id."'
            rows = '3'
            cols = '80'>".$brødtekst."</textarea><br>");
		echo("<button type = 'button' id = lenke".$id." onClick = 'leggLenke(".$id.")'>Legg til lenke</button>");
		echo("<button name='knappUppdate' type='submit' value=".$id.">Lagre</button>");
		echo("<button name='knappslette' type='submit' value=".$id.">Slett</button>");
      echo("</fieldset>");

    echo("</form>");
	}
 }
	// Skjer etter at man trykker på knappen lagre på et innhold som allerede eksisterer
		// Henter verdiene fra de ulikene feltene og bruker de til å oppdatere databasen
       if(isset($_POST['knappUppdate'])) {
			 	$teksten = mysqli_real_escape_string($db,$_POST['txtBrødtekst']);
				$tittel = mysqli_real_escape_string($db,$_POST['textoverskrift']);
		 		$ingress = mysqli_real_escape_string($db,$_POST['textIngress']);
		 		$bilde = mysqli_real_escape_string($db,$_POST['bilde']);
				$bildeid = mysqli_real_escape_string($db,$_POST['idbilde']);
				$id = mysqli_real_escape_string($db,$_POST['knappUppdate']);


				$sqsql = $db->prepare("SELECT bilder.* FROM bilder WHERE idbilder = '$bilde'");
				mysqli_set_charset($db, "UTF8");
				unset($_POST['knappUppdate']);

				lagehtml($db);
				$melding="";
				$class="";
				if(!$sqsql->execute()){
					$melding = "Innlegget kunne ikke oppdateres";
					$class ="negativ";
					$_SESSION['melding'] = $melding;
					$_SESSION['class'] = $class;
				} else {
					$result = $sqsql->get_result();
						while($row = $result->fetch_assoc()){
							$bildersID = $row['idbilder'];
						}
						$sqlUD ="DELETE FROM vikerfjell.bilderinnhold WHERE _idbilder = '$bildeid' AND _idinnhold = '$id'";
						$rsqlUD = mysqli_query($db,$sqlUD);

						$kobling = "INSERT INTO bilderinnhold(_idbilder, _idinnhold) VALUES ('$bildersID','$id')";
						$sqlK = mysqli_query($db, $kobling);

						$rsql = "UPDATE innhold SET tekst ='$teksten', tittel='$tittel', ingress='$ingress' WHERE idinnhold = '$id'";
						$srsql = mysqli_query($db,$rsql);
					//$melding = "Innlegg oppdatert";
					$class = "positiv";
					$_SESSION['melding'] = $melding;
					$_SESSION['class'] = $class;

			}
			}
		  	if(isset($_POST['knappslette'])) {
				$id = $_POST['knappslette'];
				$bildeid = $_POST['idbilde'];

				$sqlsql = "DELETE FROM vikerfjell.bilderinnhold WHERE _idbilder = '$bildeid' AND _idinnhold = '$id'";
				$sqld = mysqli_query($db, $sqlsql);
				$ssql = "DELETE FROM `vikerfjell`.`innhold` WHERE `idinnhold`='$id'";
				$sssql = mysqli_query($db,$ssql);
				unset($_POST['knappslette']);
				lagehtml($db);

			}

//function lagretext() {
//		$stmt = $db->prepare("UPDATE vikerfjell.innhold
//                          SET text ='".$_POST['textbrødtext']."', tittel = '".$_POST['texttittel']."'
//                          WHERE idinnhold=".$_POST['knapplagre'].";");
//		mysqli_set_charset($db, "UTF8");
//		$stmt->execute();
	//	}


?>
</div>
</body>
<script>
	function leggLenke(){
		var URL = prompt('Skriv inn adressen til siden du vil linke til (må ha full adresse http://www foran)','http://www.vikerfjell.no');
		if(URL == null || URL == ""){
		}else{
			var navn = prompt('Skriv navn du ønsker på lenke',URL);
			if(navn == null || navn == ""){
			} else {
				var tekstboks
				if(arguments[0] == null){
					tekstboks = "txtBrødtekst";
				} else {
					tekstboks = "txtBrødtekst" + arguments[0];
				}
				
				
				var streng = "<a href = '" + URL + "'>" + navn + "</a>";
				document.getElementById(tekstboks).value += streng;
				//document.forms['form947']['txtBrødtekst947'].value = 'HEI JEG FUNGERER';
			}
		}
	}
</script>
</html>
