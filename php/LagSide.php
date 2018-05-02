<?php
	// Funksjonene som lagehtml.php bruker for å lage sider i html

	// Funksjon for å lage header på siden
	function lagHeader($db,$urlfiks){
		//header('Content-Type: text/html; charset=utf-8');
		echo('<!DOCTYPE html>
		<html>
		  <head>
			<!-- laget av: Ole, Kontrollert av Gabriel -->
			<meta name="author" content="GOTC ~ Gruppe 1"> <!--Forfatter -->
			<meta name="keywords" content="Vikerfjell, høyfjell, Hønefoss, vestre Ådal, Buskerud"> <!--søkeord -->
			<meta http-equiv="content-type" content="text/html; charset=utf-8"> <!--Spesifiserer bokstav-kode type som er brukt -->
			<meta name="viewport" content="width=device-width, initial-scale=1.0"><!--Sørger for responsivt design -->
			<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.1/dist/leaflet.css"> <!-- responsivt kart i footeren -->
			<script src="https://unpkg.com/leaflet@1.0.1/dist/leaflet.js"></script> <!-- responsivt kart i footeren -->
			<link rel="stylesheet" type="text/css" href="'.$urlfiks.'../CSS/Stiler.css"><!--Linker til CSS-dokumentet -->
			<!--
			**faviconer for forskjellige størrelser under.
			**Kan også legges til som "applikasjon" hos Iphone,
			**slik ikonet vises på hjem-skjermen.
			-->
			<link rel="icon" type="image/png" href="'.$urlfiks.'../Bilder/icon2.png" sizes="16x16">
			<link rel="icon" type="image/png" href="'.$urlfiks.'../Bilder/icon2.png" sizes="32x32">
			<link rel="icon" type="image/png" href="'.$urlfiks.'../Bilder/icon2.png" sizes="96x96">
			<link rel="apple-touch-icon" href="'.$urlfiks.'../Bilder/icon2.png"> // 120px
			<link rel="apple-touch-icon" sizes="180x180" href="'.$urlfiks.'../Bilder/icon2.png">
			<link rel="apple-touch-icon" sizes="152x152" href="'.$urlfiks.'../Bilder/icon2.png">
			<link rel="apple-touch-icon" sizes="167x167" href="'.$urlfiks.'../Bilder/icon2.png">
			<link rel="stylesheet" href="'.$urlfiks.'../font-awesome-4.7.0/css/font-awesome.min.css">
			<title>Visit Vikerfjell</title>
		  </head>
		  <body>


			<!-- laget av: Tobias, kontrollert av Christian -->
			<header>
			  <div class="header-image" src="'.$urlfiks.'../Bilder/header.jpg" height="auto" width="auto">
			</div>
			</header>'
		);
	}
	// Funksjon for å lage menylinjen på sidene
  	function lagMeny($db,$sideID, $urlfiks,$svar_array) {
		echo(
			"<nav id='nav' role='navigation'>
			  <input id='Sookebar' class='Sookebar' type='text' name='search' placeholder='Søk..'>
				<i class='fa fa-search Sook' onclick='sok()' aria-hidden='true'></i>
					<ul class='ULmain'>
						<li><a class = 'bildelink' href='$urlfiks../sidene/default.html'><img class='bilde-logo' src='$urlfiks../Bilder/logo.png' height='180px' width='180px'></a>
						</li>");

		/*
		echo'<nav id="nav" role="navigation">';
		echo('<ul>');
		*/
		/*
		$stmt = $db->prepare("SELECT * FROM vikerfjell.meny");
		mysqli_set_charset($db, "UTF8");
		$stmt->execute();
		$result = $stmt->get_result();
		*/
		$sub_id = '';
		$index = 0;
		foreach($svar_array as $row) {
			$idmeny = $row['idmeny'];
			$kontroll = null;
			$tekst = $row['tekst'];
			$side = $row['side'];
			$sidene = "sidene/";

 			$side = str_replace(' ', '', $side);

      if(($sideID==$idmeny)){
           echo("<li><a href=# value='$idmeny' name='menyelementfocus' style=font-weight:Bold;>$tekst</a>");
			     echo("</li>");

      }else{

        echo("<li><a href=$urlfiks$side value='$idmeny' name='menyelement'>$tekst</a>");
        echo("</li>");
      }
		}

			echo("
				<li><img class='flaggNorge' height='50px' width='50px'></li>
				<li><img class='flaggUK' height='50px' width='50px'></li>


				    <script>
					  function sok(){
							var x =document.getElementById('Sookebar');
				        if(x.style.display === 'none'){
				        x.style.display = 'block';}
				        else{x.style.display = 'none';
				        }
							}
							</script>

			</ul>

				<!-- burger meny
				Vi har benyttet oss av en burger meny som krediteres til Erik Terwan, 24 November 2015. (også kreditert i kildelisten våres i rapporten)
				Vi har gjort enkelte endringer i HTML og CSS koden, der iblant slik meny elementene blir generert og listet opp i burgermenyen.
				-->

				<div class='ULburger' id='menuToggle'>


					<!-- en checkbox som blir brukt, så man kan bruke checked-selektor på den. -->

					<input class='burgerinput' type='checkbox' />

					<!-- 3 span-tagger som blir seendes ut som en hamburger -->

					<span></span>
					<span></span>
					<span></span>

					<ul id='menu'>
			");
			foreach($svar_array as $row){

				$idmeny = $row['idmeny'];
				$kontroll = null;
				$tekst = $row['tekst'];
				$side = $row['side'];
				$sidene = "sidene/";

				$side = str_replace(' ', '', $side);

				echo("
						<li class='menyVenstre'><a href=$urlfiks$side value='$idmeny' name='menyelement'>$tekst </a></li><br>
			");
			}
				echo("
					</ul>
				</div>
		</nav>");

		/*

		echo('</ul>');
		echo('</nav>');
		*/
	}
	function lagInnhold($db,$sidenavn,$idinnhold,$tittel,$ingress,$tekst,$bilde){
	/*	$isql = $db->prepare("SELECT innhold.*, bilder.hvor, bilder.idbilder FROM innhold LEFT OUTER JOIN bilderinnhold ON
	(innhold.idinnhold=bilderinnhold._idinnhold) LEFT OUTER JOIN bilder ON
	(bilderinnhold._idbilder=bilder.idbilder) WHERE innhold.idinnhold = '$idinnhold'");
	mysqli_set_charset($db, "UTF8");
	$isql->execute();
	$result = $isql->get_result(); */
	echo("<div class='mainContent'>
		<div class='content'>");
		/*
		while($row = $result->fetch_assoc()){
			$tittel =$row['tittel'];
			$tekst =$row['tekst'];
			$ingress= $row['ingress'];
			$bilde = $row['hvor'];
		*/
			if($bilde==NULL){
				$bilde='FinnerIkkeBilde.jpg';
			}

				echo("<div class='mainContent'>
					<div class='content'>
						<div class='content-wrapperInnhold'>
							<article class='leftContent'>
								<img class='bilde-innlegg' src='../../Bilder/$bilde' height='100%' width='100%'>
							</article>
							<article class='rightContent'>
								<h2>$tittel</h2>
								<p><strong>$ingress</strong></p>
								<p>$tekst</p>
							</article>
						 </div>
					 </div>
				 </div>");
			//}
			echo("</div>
			</div>");

	}
	function laginnholdOversikt($db,$sideID,$sidenavn,$innhold_array){
		if(@!$_POST['menyelement']){

			//$oversikt_array = array();
			$teller = 0;
			$antall = 0;

			foreach($innhold_array as $row){
				$idmeny = $row['idmeny'];
				if($idmeny == $sideID){
			//		$oversikt_array[] = $row;
					$antall += 1;
				}
			}
			$sidenavn = str_replace(' ', '', $sidenavn);
			//$antall = mysqli_num_rows($result);
			if($antall > 1) {

				echo("<div class='mainContent'>
					<div class='content'>
					<div class='content-wrapper'>");
				foreach($innhold_array as $row){
					if($row['idmeny']==$sideID){
						$tittel =$row['tittel'];
						$tekst =$row['tekst'];
						$ingress= $row['ingress'];
						$bilde = $row['hvor'];
						$navn = $row['side'];

						$navn = str_replace(' ', '', $navn);
						$small = substr($ingress, 0, 25).'...';

						$teller++;


						if($bilde==NULL){
						$bilde='FinnerIkkeBilde.jpg';
						}
						if($teller ==1){
							echo("

									<article class='shortcut1'>
										<div id='bilde1' style='background:url(../Bilder/$bilde); min-height:150px; background-repeat:no-repeat; background-position:center'>
										</div>
										<div id='tekst1'>
											<h2 class='boldtittel' >$tittel</h3>
											<p><strong>$small</strong></p>
											<p><a href=$sidenavn/$navn >Les mer</a></p>
										</div>
									</article>
							");

						}
						if($teller==2){
							echo("

								<article class='shortcut2'>
									<div id='bilde2' style='background:url(../Bilder/$bilde); min-height:150px; background-repeat:no-repeat; background-position:center'>
									</div>
									<div id='tekst2'>
										<h2 class='boldtittel' >$tittel</h2>
										<p><strong>$small</strong></p>
										<p><a href=$sidenavn/$navn >Les mer</a></p>
									</div>
								</article>
							");

						}
						if($teller ==3){
							echo("
								<article class='shortcut3'>
									<div id='bilde3' style='background:url(../Bilder/$bilde); min-height:150px ; background-repeat:no-repeat; background-position:center'>
									</div>
									<div id='tekst3'>
										<h2 class='boldtittel' >$tittel</h2>
										<p><strong>$small</strong></p>
										<p><a href=$sidenavn/$navn >Les mer</a></p>
									</div>
								</article>

							");
							$teller=0;
						}
					}
				}
				echo("
					</div>
					</div>
					</div>
					</div>
				");
			}//if
			else{
				echo("<div class='mainContent'>
					<div class='content'>");
				foreach($innhold_array as $row){
					if($row['idmeny'] == $sideID){
						$tittel =$row['tittel'];
						$teksten =$row['teksten'];
						$ingress= $row['ingress'];
						$bilde = $row['hvor'];

						echo("<div class='mainContent'>
								<div class='content'>
									<div class='content-wrapperInnhold'>
										<article class='leftContent'>
											<img class='bilde-innlegg' src='../Bilder/$bilde' height='100%' width='100%'>
										</article>
										<article class='rightContent'>
											<h2>$tittel</h2>
											<p><strong>$ingress</strong></p>
											<p>$teksten</p>
										</article>
									</div>
								</div>
							</div>");
					}
				}
				echo("</div>
				</div>");
			}
		}
	}
    // Funksjon for å lage footer på siden
	function lagFooter($db, $urlfiks){
	  echo('
		  <footer>
				<div id="wrapper_foot">

				<!-- Sosiale medier knapper med hyperlink til de forskjellige stedene (Snapchat mangler link til vikerfjell sin snapchat) -->
					<div class="footer2">
						<div class="sosmed">
							<p>Følg oss på sosiale medier</p>
							<ul class = "sosul">
								<a href="https://www.facebook.com/Tosseviksetra/">
									<img border="0" alt="sosial" src="'.$urlfiks.'../Bilder/fb.png" height="50" width="50">
								</a>

								<a href="http://www.snapchat.com/">
									<img border="0" alt="sosial" src="'.$urlfiks.'../Bilder/sc.png" alt="Smiley face" height="48" width="48">
								</a>

								<a href="https://www.instagram.com/explore/tags/Vikerfjell/">
									<img border="0" alt="sosial" src="'.$urlfiks.'../Bilder/ig.png" alt="Smiley face" height="52" width="52">
								</a>
							</ul>
						</div>
					</div>
					<div class="footer2">
						<div class="shortcut">
							<p class="fellesLeft"><b>Felles</b></p>
								<table>
								  <tr>
									<td><a href="'.$urlfiks.'../sidene/default.html">Hjem</a></td>
									<td><a href="'.$urlfiks.'../sidene/hytter.html">Hytter</a></td>
								  </tr>
								  <tr>
									<td><a href="'.$urlfiks.'../sidene/vei_og_fore.html">Vei og føre</a></td>
									<td><a href="'.$urlfiks.'../sidene/kontakt.html">Kontakt</a></td>
								  </tr>
									<tr>
									<td><a href="'.$urlfiks.'../sidene/aktuelt.html">Aktuelt</a></td>
									<td><a href="'.$urlfiks.'../sidene/tomter.html">Hyttetomter</a></td>
								  </tr>

								</table>
							<p class="adminFlytt"><strong>Admin</strong></p>
								<table>
									<tr>
										<td><a href="'.$urlfiks.'../php/logginn.php">Logg inn</a></td>
									</tr>
								</table>
							</div>
						</div>


					<!--  Footer kontakt-informasjon -->
					<div class="footer2">
						<div class="kontakt1">
							<p><strong>Kontakt</strong></p>
							<p><strong>E-post: <a href="#epost" style=color:#66ccff; >post@vikerfjell.com</a></strong></p>
							<p><strong>Telefon: </strong>930 11 567</p>
							<p><strong>Adresse:</strong> Elsrud Gård</p>
							<p><strong>Vestre Ådal 922, 3516 Hønefoss</p>
						</div>
					</div>
				</div>
		  </footer>
		  </body>
		  </html>'
		);
	}

?>
