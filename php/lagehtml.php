<?php
	// Skrevet av Christian sist endret: 02.06.17
	// Funksjon som lager sidene fra menytabellen i databasen i html
	//
	function lagehtml($db) {

		include("LagSide.php");

		// Henter oversiktssider som skal genereres
		$hsql = $db->prepare("SELECT * FROM vikerfjell.meny ORDER BY rekke");
		mysqli_set_charset($db, "UTF8");
		$hsql->execute();
		$rhsql = $hsql->get_result();
		$svar_array = array();

		// Legger sidene i et array
		while($svar = mysqli_fetch_assoc($rhsql)){
			$svar_array[] = $svar;
		}

		// Henter innhold
		$ssql = $db->prepare("SELECT innhold.*, innhold.tekst as teksten, bilder.idbilder, bilder.hvor FROM innhold LEFT OUTER JOIN bilderinnhold ON
			(innhold.idinnhold=bilderinnhold._idinnhold) LEFT OUTER JOIN bilder ON
			(bilderinnhold._idbilder=bilder.idbilder)");
		mysqli_set_charset($db, "UTF8");
		$ssql->execute();
		$result = $ssql->get_result();
		$innhold_array = array();
		
		// Legger innholdene i et array
		while($innhold = mysqli_fetch_assoc($result)){
			$innhold_array[] = $innhold;
		}

		// For-løkke som lager sider
		foreach($svar_array as $row){
			$urlfiks="";
			$sideID = $row['idmeny'];
			$sidenavn = $row['tekst'];
			$sidehent = $row['side'];
			$sidehent = str_replace(' ', '', $sidehent);
			//$sidehent = str_replace('../sidene/', '', $sidehent);
			
			// ob_start starter å skrive all output til en buffer
			ob_start();
				lagHeader($db,$urlfiks);
				lagMeny($db,$sideID,$urlfiks,$svar_array);
				laginnholdOversikt($db,$sideID,$sidenavn,$innhold_array);
				lagFooter($db, $urlfiks);
			// Legger buffer i en variabel
			$page = ob_get_contents();
			ob_end_clean();
			$file = "../" . "sidene" . "/" . "$sidehent";
			@chmod($file,0755);
			$fw = fopen($file, "w");
			// Lagrer variablen til en fil
			fputs($fw,$page, strlen($page));
			fclose($fw);
		}
		// Spørring mot databasen for å finne ut hvilke sider som kun har 1 tilhørende innhold
		/*
		$ssql = $db->prepare("SELECT innhold.idmeny, count(innhold.idmeny) as antall
								FROM innhold
								JOIN meny USING(idmeny)
								GROUP BY innhold.idmeny
								HAVING antall<2"
							);
		$ssql->execute();
		$rssql = $ssql->get_result();
		// Legger disse menyid'ene i en query for å hindre at de blir hentet ut senere
		if(mysqli_num_rows($rssql)>0){
			$query = "";
			$sjekk = true;
			while($row = $rssql->fetch_assoc()){
				$queryid = $row['idmeny'];
				if($sjekk){
					$query = "WHERE idmeny != '$queryid'";
					$sjekk = false;
				}else{
					$query .= " AND idmeny != '$queryid'";
				}
			}
		} */
		//$usql = $db->prepare("SELECT innhold.*, meny.tekst AS menytekst from innhold JOIN meny USING(idmeny) $query");
		$usql = $db->prepare(
		"SELECT innhold.*, bilder.hvor, bilder.idbilder,meny.tekst as sidenavn FROM innhold
			LEFT OUTER JOIN bilderinnhold ON
				(innhold.idinnhold=bilderinnhold._idinnhold)
			LEFT OUTER JOIN bilder ON
				(bilderinnhold._idbilder=bilder.idbilder)
			LEFT OUTER JOIN meny ON
				(innhold.idmeny=meny.idmeny)"
		);
		mysqli_set_charset($db, "UTF8");
		$usql->execute();
		$rusql = $usql->get_result();
		$svar_array2 = array();


		while($svar2 = mysqli_fetch_assoc($rusql)){
			$svar_array2[] = $svar2;
		}

		// For-løkke som lager undersider
		foreach($svar_array2 as $row2){
			$urlfiks="../";


			//var_dump($svar_array2);
			$sideID = $row2['idmeny'];
			$sidenavn = $row2['sidenavn'];

			$idinnhold = $row2['idinnhold'];
			$tittel = $row2['tittel'];
			$ingress = $row2['ingress'];
			$tekst = $row2['tekst'];
			$navn = $row2['side'];

			$bilde = $row2['hvor'];
			
			$sidenavn = str_replace(' ', '', $sidenavn);
			

			ob_start();
				lagHeader($db,$urlfiks);
				lagMeny($db,$sideID,$urlfiks,$svar_array);
				lagInnhold($db,$sidenavn,$idinnhold,$tittel,$ingress,$tekst,$bilde);
				lagFooter($db,$urlfiks);
			$page = ob_get_contents();
			ob_end_clean();
			//$mappe = dirname($sidenavn);
			
			$navn = str_replace(' ', '', $navn);
			$navn = str_replace('\\', '', $navn);
			//$navn = str_replace('../sidene/i/', '', $navn);
			
			$underfile = "../" . "sidene" . "/" . "$sidenavn" . "/" . "$navn";
			if (!is_dir("../" . "sidene")){
				mkdir("../" . "sidene", 0755, true);
			}
			if (!is_dir("../" . "sidene" . "/" . $sidenavn)){
				mkdir("../" . "sidene" . "/" . $sidenavn, 0755, true);
			}
			@chmod($file);
			$fw = fopen($underfile, "w");
			fputs($fw,$page, strlen($page));
			fclose($fw);
		}
		//header("location:adminmeny.php");
	}
?>
