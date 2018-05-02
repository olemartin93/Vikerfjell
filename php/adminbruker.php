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
// Side for endring av passord og oppretting av nye brukere.
?>
<!DOCTYPE HTML>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../CSS/admin.css">
		<link rel="stylesheet" href="VisitVikerfjell/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<!--
		**faviconer for forskjellige størrelser under.
		-->
		<link rel="icon" type="image/png" href="../Bilder/cog-0.png" sizes="16x16">
		<link rel="icon" type="image/png" href="../Bilder/cog-3.png" sizes="32x32">
		<link rel="icon" type="image/png" href="../Bilder/cog-5.png" sizes="96x96">
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
		<div class="content">
				<h1>Bruker: <?php echo($_SESSION['bruker']); ?></h1>
				<button onclick="window.location='adminbruker.php?ny'" type="submit" form="form1" value="Submit" name="skjulInnlegg"><i class="fa fa-user" aria-hidden="true"></i> Legg til ny bruker</button>
				<button onclick="window.location='adminbruker.php?nypw'" type="submit" form="form1" value="Submit" name="skjulInnlegg"><i class="fa fa-key" aria-hidden="true"></i>Endre passord</button>
      <?php
        $bruker = $_SESSION['bruker'];
        $passord = $_SESSION['passord'];

        $lagny = false;
        $nypwrd = false;

        if (isset($_GET['ny'])) {$lagny = true;}
        if (isset($_GET['nypw'])) {$nypwrd = true;}
        if (!($db = new vikerfjell())) {
          die("Ingen forbindelse til databasen");
        }
        if (isset($_POST['lagny']) &&
          $_POST['lagny']=='Registrer ny bruker') {
          //  Skal legge inn ny bruker
          $pass = $_POST['nyttpw'];
          $salt = "IT2_2017";
          $hash1_sha1 = sha1($salt.$pass);
          // salting av passord med sha1
    		  $nybruker = $_POST['nybruker'];
    		  $epost = $_POST['nyePost'];

    		  $lsql = "INSERT INTO bruker (brukerNavn, passord, ePost, feilLogginnTeller) VALUES ('$nybruker', '$hash1_sha1', '$epost', 0)";
    		  $ssql = mysqli_query($db,$lsql);
          // legger inn i databasen
        }
        if (isset($_POST['nypwrd']) &&
          $_POST['nypwrd']=='bytt Passord') {


            $passordG = mysqli_real_escape_string($db, $_POST['gammeltpw']);
            $passord1 = mysqli_real_escape_string($db, $_POST['nyttPassord']);
            $passord2 = mysqli_real_escape_string($db, $_POST['bekreftPassord']);
            $salt = "IT2_2017";
            $hash1_sha1 = sha1($salt.$passord2);

            if($passord == $passordG){
              // vis det gamle passordet stemmer
              if($passord1 == $passord2){
                $pwsql = "UPDATE bruker SET passord='$hash1_sha1' WHERE brukerNavn='$bruker'";
                $ssql = mysqli_query($db,$pwsql);
                // vis begge nye passord er lik, får bruker endre passord
              }
              else{
                echo"det nye passordet matcher ikke";
                echo"<script>window.location.href ='admin.php?nypw';</script>";
                die();

              }
            }
            else{
              echo"gammelt passord stemmer ikke";
              die();
            }


            if (!$ssql) {
        			printf("Error: %s\n", mysqli_error($db));
        			exit();
        		}
          }
		// Skjer hvis opprett bruker-knapppen er blitt trykket på
        if ($lagny) {
          echo("<form method='POST' action=''>");
          echo("<table><tr><th>Bruker navn:</th>");
          echo("<th>Passord</th>");
          echo("<th>ePost</th></tr>\n");
          echo("<tr><td><input type='text' name='nybruker' autofocus></td>\n");
          echo("<td><input type='password' name='nyttpw'></td>\n");
          echo("<td><input type='text' name='nyePost'></td></tr>\n");
          echo("</table>");
          echo("<input type='submit' name='lagny' value='Registrer ny bruker'>");
          echo("</form>");
          // lager feltet for å legge til ny bruker
        }
		// Skjer hvis endre passord-knappen er blitt trykket på
        if($nypwrd){
          echo("<form method='POST' action=''>");
          echo("<table><tr><th>Gammelt passord</th>");
          echo("<th>Nytt passord</th>");
          echo("<th>Bekreft passord</th></tr>\n");
          echo("<tr><td><input type='password' name='gammeltpw' autofocus></td>\n");
          echo("<td><input type='password' name='nyttPassord'></td>\n");
          echo("<td><input type='password' name='bekreftPassord'></td></tr>\n");
          echo("</table>");
          echo("<input type='submit' name='nypwrd' value='bytt Passord'>");
          echo("</form>");
          // lager feltet for endring av passord
        }
      ?>
		</div>
	</body>
</html>
