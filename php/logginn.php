<?php
	session_start();
	include ("../php/db.php");
	include ("../php/sessionTimeout.php");
	// Kontroll og sending av data til databasen for pålogging.
	if(isset($_POST['tilbake'])){
		header("location:../sidene/default.html");
	}
	$message="";
	$message2="";
	$class="";
	if(isset($_POST['send'])) {
		// Brukernavn og passord blir hentet fra tekstfeltene.
		$bruker = mysqli_real_escape_string($db,$_POST['bruker']);
		$passord = mysqli_real_escape_string($db,$_POST['passord']);

		// Passordet saltes og krypteres.
        $salt = "IT2_2017";
        $hash1_sha1 = sha1($salt.$passord);

		// Queries blir sendt til databasen
		$lsql = "SELECT idbruker FROM bruker WHERE brukerNavn = '$bruker' and passord = '$hash1_sha1'";
		$ssql = mysqli_query($db,$lsql);
		$asql = "SELECT feilLogginnTeller, idbruker FROM bruker WHERE brukerNavn = '$bruker'";
		$sasql = mysqli_query($db,$asql);
		$dsql = "SELECT TIMESTAMPDIFF(MINUTE,feilLogginnSiste,NOW()) AS tid FROM bruker WHERE brukerNavn = '$bruker'";
		$dssql = mysqli_query($db,$dsql);

		// Svar kommer fra databasen og blir lagt i arrays
		@$brukerrad = mysqli_fetch_array($ssql,MYSQLI_ASSOC);
		@$brukerid = $brukerrad['idbruker'];
		@$tellerrad = mysqli_fetch_array($sasql,MYSQLI_ASSOC);
		@$antallFeil = $tellerrad['feilLogginnTeller'];
		@$feilbrukerID = $tellerrad['idbruker'];
		@$datorad = mysqli_fetch_array($dssql,MYSQLI_ASSOC);
		@$minutter = $datorad['tid'];

		/*
		@$rad = mysqli_fetch_array($ssql,MYSQLI_ASSOC);
		@$aktiv = $rad['active'];
		$antallFeil = $rad["feilLogginnTeller"];
		$sisteFeil = $rad["feilLogginnSiste"];
		$datotid = date('Y/m/d H:i:s');

		echo($antallFeil);
		*/

		// Kontroll av antall forsøkte pålogginger
		if(($antallFeil >= 5) and ($minutter < 15)) {
			// Bruker må vente 15 minutter for hver gang etter 5 forsøk.
			$visminutter = ($minutter*-1)+15;
			$message2 = "Du har logget inn for mange ganger. vent $visminutter minutter for nytt forsøk.";
			$class ="negativ";
			unset($_POST['send']);

		} else {
			// Hvis det er en bruker med denne informasjonen blir man logget på.
			$antall = mysqli_num_rows($ssql);
			if($antall == 1) {
				$_SESSION['bruker'] = $bruker;
				$_SESSION['passord'] = $passord;
				$rlsql = "UPDATE bruker set feilLogginnTeller = 0 WHERE idbruker = '$feilbrukerID'";
				$rssql = mysqli_query($db,$rlsql);
				header("location:../php/admin.php");
			}  else {
				// Ved feil informasjon vil databasen oppdatere feilteller med +1 og ny tid for siste logginn.
				$message = "Feil brukernavn eller passord";
				$class = "negativ";


				$flsql = "UPDATE bruker set feilLogginnSiste = NOW(), feilLogginnTeller = (feilLogginnTeller + 1) WHERE idbruker = '$feilbrukerID'";
				mysqli_query($db,$flsql);
				unset($_POST['send']);

			}
		}
	}

	if(isset($_POST['sendEpost'])){
		$brukerNavn;
		$nyttPassord;
		$hentepost = $_POST['epostHENT'];

		$nysql = $db->prepare("SELECT * FROM bruker WHERE ePost='$hentepost'");
		mysqli_set_charset($db, "UTF8");
		$nysql->execute();
		$result = $nysql->get_result();
		while($row = $result->fetch_assoc()){
			$idbruker = $row['idbruker'];
			$brukerNavn = $row['brukerNavn'];
			$passord = $row['passord'];
			$ePost = $row['ePost'];

		}

		require '../phpmailer/PHPMailerAutoload.php';

			$mail = new PHPMailer;

			//$mail->SMTPDebug = 2;                               // Enable verbose debug output

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.live.com';  											// Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'debug.usn@hotmail.com';            // SMTP username
			$mail->Password = 'Debug123';                         // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port som tilkobles

			$mail->setFrom('debug.usn@hotmail.com', 'Mailer');
			$mail->addAddress($hentepost, $hentepost);


			$mail->isHTML(true);                                  // Setter epostformat til HTML

			function random_password( $length = 8 ) {
    		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				for ($i = 0; $i < $length; $i++) {
					$password = substr ( str_shuffle ( str_repeat ( $chars ,$length ) ), 0, $length );
					}
					return $password;

			}
			$password = random_password(6);
			$salt = "IT2_2017";
			$hash1_sha1 = sha1($salt.$password);


			$mail->Subject = 'Nytt passord - VisitVikerfjell.no';
			@$mail->Body    = '<b>Brukernavn:</b>'.$brukerNavn.'<br> <b>Nytt passord:</b>'.$password.'';
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			if(!$mail->send()) {
					$message = "Sending av nytt passord feilet, skriv inn epost på nytt.";
					$class ="negativ";

			} else {
					$message= "Melding sendt";
					$class="positiv";


					$pwsql = "UPDATE bruker SET passord='$hash1_sha1' WHERE brukerNavn='$brukerNavn'";
					$ssql = mysqli_query($db,$pwsql);

			}


	}



?>
<!DOCTYPE html>
<html>

  <head>
    <!-- laget av: Ole, Kontrollert av Gabriel -->
    <meta name="author" content="GOTC ~ Gruppe 1"> <!--Forfatter -->
    <meta name="keywords" content="Vikerfjell, høyfjell, Hønefoss, vestre Ådal, Buskerud"> <!--søkeord -->
    <meta http-equiv="content-type" content="text/html; charset=utf-8"> <!--Spesifiserer bokstav-kode type som er brukt -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><!--Sørger for responsivt design -->
    <link rel="stylesheet" type="text/css" href="../CSS/admin.css"><!--Linker til CSS-dokumentet -->
			<link rel="stylesheet" href="VisitVikerfjell/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="shortcut icon" href="Bilder/icon2.ico" type="favicon/ico" /> <!--Øverste ikon på hjemmesiden(fjell-logoen) -->
    <title>Visit Vikerfjell</title> <!--Logonavn på browser-tabben -->
  </head>
  <!--media="screen" spesifiserer at innholdet er optimalisert for skjerm -->
  <style media="screen"> </style>

  <body class = "lbody">
    <!-- laget av: Tobias, kontrollert av Christian -->

<!--~~~~~~~~~~~~~~~~~NAVIGASJON~~~~~~~~~~~~~~~~~~~~~-->
<!-- laget av: Tobias, kontrollert av Christian -->


    <!--~~~~~~~~~~~~~~~~Innhold på siden~~~~~~~~~~~~~~~~~~ -->
    <!-- laget av: Gabriel og Christian (50%/50%), kontrollert av Tobias og Ole -->
    <div class="mainContent">
      <div class = "login-side">
			<div class = "logform">
				<form action = "" method = "POST" id = "iform" class = "innlogging-form">
				<div class="ribbon"><span><i class="fa fa-user-circle" aria-hidden="true"></i></span></div>
					<h1>Administrator <br>Logg inn</h1>
					<input type = "text" name = "bruker" title = "Skriv inn brukernavn" autofocus placeholder = "Brukernavn"> <br>
					<input type = "password" name = "passord" title = "Skriv inn passord" placeholder = "Passord">	<br>

					<p class="<?php echo("$class"); ?>"><?php echo("$message"); ?></p>
					<p class="<?php echo("$class"); ?>"><?php echo("$message2"); ?></p>

					<input class="button1" type = "submit" name = "send" value = "Logg inn">
					<br>

					<input class="button2" type = "submit" name = "tilbake" value = "til forsiden" >

					<br>
					<span class="psw"><a class="jslink" onClick = "bytteForm()">Glemt passord?</a></span>


				</form>
				<form id="gform" class = "glemt-form" method="POST">
					<h1>Glemt passord</h1>
					<input type = "text" name = "epostHENT" title = "Skriv inn epost" placeholder = "Epost" autofocus> <br>
					<input class="button3" type = "submit" name = "sendEpost" value = "Send">
					<button type="button" onClick = "bytteForm()">Tilbake</button>

					<?php


					?>
				</form>
				<!-- script for å bytte form mellom logginn og glemt passord-->
				<script>
					function bytteForm() {
						var iform = document.getElementById('gform');
						var gform = document.getElementById('iform');
						if(gform.style.display=='none'){
							iform.style.display='none';
							gform.style.display='block';
						}else{
							gform.style.display='none';
							iform.style.display='block';
						}
					}
				</script>
			</div>
		</div>

    </div>




  </body>
</html>
