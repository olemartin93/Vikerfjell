<?php
	session_start();
	include("db.php");
	include("meny.php");
	include("redigermeny.php");
	include("sessionTimeout.php");
  if(!$_SESSION['bruker']){
    header("location:logginn.php");
    die('Kunne ikke koble til databasen' . mysqli_connect_error());
  }
	$melding ="";
	$class = "";
	$_SESSION['melding'] = $melding;
	$_SESSION['class'] = $class;

	if(!$_SESSION['bruker']) {
		header('location:logginn.php');
		die();
	}
	if(isset($_POST['nybilde'])) {
		$bildenavn = mysqli_real_escape_string($db,$_POST['bildenavn']);
		$msql = "INSERT INTO bilder (hvor) VALUES ('$bildenavn')";
		unset($_POST['nybilde']);
		unset($_POST['bildenavn']);
		$melding="";
		$class="";
		if(!$smsql = mysqli_query($db,$msql)){
			$melding = "kan ikke legge til bilde";
			$class ="negativ";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;
		} else {
			$melding = "Bilde lagt til";
			$class = "positiv";
			$_SESSION['melding'] = $melding;
			$_SESSION['class'] = $class;

	}
}

	if(isset($_POST['slett'])) {
		$melding="";
		$class="";
		$id = $_POST['slett'];
		slettBilde($db, $id);
		unset($_POST['send']);

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
				<h2>Legg til bilde</h2>
				<p class='<?php echo($_SESSION['class']);?>'> <?php echo($_SESSION['melding']); ?></p>
				<form method="POST" action="" id="lForm" class="lForm">
					<table>
						<tr><td>Navn på Bilde: </td>
						<!-- <td><input type='text' name='bildenavn' autofocus></td></tr> -->
						<td><input type="file" name='bildenavn' id="bildeFile"></td>
						<tr><td><input type='submit' class='nybilde' name='nybilde' value='Legg til nytt bilde'></td></tr>
					</table>
				</form>
			</div>
			<div class="slett">
				<h2>Slett bilde</h2>
				<!--<form action="POST" id="sForm" class="sForm">-->
					<table>
						<?php
							visslettBilde($db);
						?>
					</table>
				<!--</form>-->
			</div>

		</div>
	</body>
</html>
