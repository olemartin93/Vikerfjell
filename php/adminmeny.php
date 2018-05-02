<?php
	session_start();
	include("db.php");
	include("meny.php");
	include("redigermeny.php");
	include("lagehtml.php");
	include("sessionTimeout.php");
  if(!$_SESSION['bruker']){
    header("location:logginn.php");
    die('Kunne ikke koble til databasen' . mysqli_connect_error());
  }
	if(isset($message)){
		$_SESSION['melding'] = $message;
	}

	if(!$_SESSION['bruker']) {
		header('location:logginn.php');
		die();
	}
	if(isset($_POST['nymeny'])) {
		$rekkesql = "SELECT MAX(rekke)+1 as rekke from meny";
		$srekkesql = mysqli_query($db,$rekkesql);
		$row = mysqli_fetch_assoc($srekkesql);
		$maxrekke = $row['rekke'];
		
		$menynavn = mysqli_real_escape_string($db,$_POST['menynavn']);
		$menyfilnavn = str_replace(' ', '', $menynavn);
		$msql = "INSERT INTO meny (tekst,side,rekke,toolTip,alt) VALUES ('$menynavn','$menyfilnavn.html','$maxrekke','$menynavn','$menynavn')";
		unset($_POST['nymeny']);
		unset($_POST['menynavn']);
		$melding="";
		if(!$smsql = mysqli_query($db,$msql)){
			$melding = "kan ikke legge til menyelement";
			$class ="negativ";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;
		} else {
			$melding = "Menyelement lagt til";
			$class = "positiv";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;
		}
		lagehtml($db);
	}
	if(isset($_POST['send'])) {
		$teksten = mysqli_real_escape_string($db,$_POST['tekst']);
		$id = $_POST['send'];
		$rekke = mysqli_real_escape_string($db,$_POST['rekke']);
		$gRekke = mysqli_real_escape_string($db,$_POST['gRekke']);
		
		$rsql = "UPDATE meny SET tekst = '$teksten' WHERE idmeny = '$id'";
		
		lagehtml($db);
		$melding="";
		if(!$srsql = mysqli_query($db,$rsql)){
			$melding = "kan ikke oppdatere menyelement";
			$class ="negativ";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;
		} else {
			$melding = "Menyelement oppdatert";
			$class = "positiv";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;
			
			if($gRekke > $rekke){
				$operator = '+';
				$hvilkenRekke = $rekke;
				$rekksql = "UPDATE meny SET rekke = (rekke $operator 1) 
				WHERE (rekke BETWEEN '$rekke' AND '$gRekke') OR rekke = '$hvilkenRekke'";
				
			} else {
				$operator = '-';
				$hvilkenRekke = $gRekke;
				$rekksql = "UPDATE meny SET rekke = (rekke $operator 1) 
				WHERE (rekke BETWEEN '$gRekke' AND '$rekke') OR rekke = '$hvilkenRekke'";
			}
			
			mysqli_query($db,$rekksql);
	
				
				//$resql = "UPDATE meny SET rekke = (rekke - 1) WHERE rekke BETWEEN '$gRekke' AND '$rekke'";
				//mysqli_query($db,$resql);
			
			$rekkesql = "UPDATE meny SET rekke = '$rekke' WHERE idmeny = '$id'";
			mysqli_query($db,$rekkesql);
		}
		unset($_POST['send']);


	}
	// slett menyelement
	if(isset($_POST['slett'])) {
		$id = $_POST['slett'];
		$melding="";
		$_SESSION['melding'] = $melding;
		slettfil($db,$id);
		unset($_POST['send']);
		lagehtml($db);
	}

?>
<!DOCTYPE HTML>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../CSS/admin.css">
		<link rel="stylesheet" href="VisitVikerfjell/font-awesome-4.7.0/css/font-awesome.min.css">	
		<!--
		**faviconer for forskjellige størrelser under.
		-->
		<link rel="icon" type="image/png" href="../Bilder/cog-0.png" sizes="16x16">  
		<link rel="icon" type="image/png" href="../Bilder/cog-3.png" sizes="32x32">  
		<link rel="icon" type="image/png" href="../Bilder/cog-5.png" sizes="96x96">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>

	<body>
		<header>
			<div id="logginn">
				<a href="../php/loggut.php">Logg ut</a>
				<a href="../php/admin.php">Bruker: <?php echo($_SESSION['bruker']); ?></a>
				<div id="box">
			</div>
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
					<?php
						//lagmeny($db);
					?>
				</ul>
			</nav>
		</header>
	<div class="aside-div">
		<aside>
			<ul>

			</ul>
		</aside>
	</div>

			<div class="leggTil">
				<h2>Legg til element i meny</h2>
				<p class='<?php echo($_SESSION['class']);?>'> <?php echo($_SESSION['melding']); ?></p>
			<!--
				<button type="button" onClick="vislForm()">Legg til element i meny</button>
				<button type="button" onClick="visrForm()">Rediger element i meny</button>
				<button type="button" onClick="vissForm()">Slett element i meny</button> -->

				<form method="POST" action="" id="lForm" class="lForm">
					<table>
						<tr><td>Navn på siden: </td>
						<td><input type='text' name='menynavn' autofocus></td></tr>
						<tr><td><input type='submit' class='nymeny' name='nymeny' value='Legg til nytt element'></td></tr>
					</table>
				</form>
			</div>
			<div class="rediger">
				<h2>Rediger element i meny</h2>
				<!--<form action="" method="POST" id="rForm" class="rForm">-->
					<table>
          <tr><th></th><th>Navn</th><th>Rekke</th></tr>
						<?php
							visred($db);
						?>
					</table>
				<!--</form>-->
			</div>
			<div class="slett">
				<h2>Slett element i meny</h2>
				<!--<form action="POST" id="sForm" class="sForm">-->
					<table>
						<?php
							visslett($db);
						?>
					</table>
				<!--</form>-->
			</div>
			<script>
			
			// Sjekker om input er et tall, hvis ikke blir den ikke lagt til.
			function isNumberKey(evt){
			var charCode = (evt.which) ? evt.which : evt.keyCode
				if (charCode > 31 && (charCode < 48 || charCode > 57))
					return false;
				return true;
			}
			/*
				var lForm = document.getElementById('lForm');
				var sForm = document.getElementById('sForm');
				var rForm = document.getElementById('rForm');
				function vislForm() {
					lForm.style.display='block';
					sForm.style.display='none';
					rForm.style.display='none';
				}
				function visrForm() {
					lForm.style.display='none';
					sForm.style.display='none';
					rForm.style.display='block';
				}
				function vissForm() {
					lForm.style.display='none';
					sForm.style.display='block';
					rForm.style.display='none';
				} */
			</script>
		</div>
	</body>
</html>
