<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style.css">
		<title>Projet Trace - Affichage</title>
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
	</head>
	<body>
		<center>
			<h1><u>Projet Trace - Affichage</u></h1>
			<br>
			<br>
			<table class="tableauBoutons">
				<tr>
					<th>
						<a href="insertion.php" target="_blank">
    						<button>Insertion</button>
						</a> 
					</th>
					<th>
						<a href="analyse.php">
    						<button>Analyse</button>
						</a> 
					</th>
					<th>
						<a href="#filtres">
    						<button>Filtres</button>
						</a> 
					</th>
				</tr>
			</table>
			<br>
			<br>
			<hr>
			<br>
			<br>
			<h2><u>Table Trace</u></h2>
			<table>
				<tr>
					<th>Id</th>
					<th>IP</th>
					<th>Host</th>
					<th>Date de connexion</th>
					<th>Id User</th>
					<th>User</th>
					<th>Id Page</th>
					<th>Page</th>
				</tr>
				<?php
					$user = "root";
					$pass = "";
	
					try
					{
						$request = "SELECT Trace.Id, Trace.Ip, Trace.Host, Trace.DateCnx, Trace.Id_User, User.Name, Trace.Id_Page, Page.Page FROM Trace LEFT JOIN User ON Trace.Id_User = User.Id INNER JOIN Page ON Trace.Id_Page = Page.Id";
						
						//Si le filtre Name n'est pas vide :
						if ($_POST["Name"] != "")
						{
							$name = $_POST["Name"];
							$request = $request . " AND User.Name = '".$name."'";
						}
						//Si le filtre Page n'est pas vide :
						if ($_POST["Page"] != "")
						{
							$page = $_POST["Page"];
							$request = $request . " AND Page.Page = '".$page."'";
						}
						//Si le filtre IP n'est pas vide :
						if ($_POST["IP"] != "")
						{
							$ip = $_POST["IP"];
							$request = $request . " AND Trace.Ip = '".$ip."'";
						}
						//Si le filtre Date n'est pas vide :
						if ($_POST["Date"] != "")
						{
							$date = $_POST["Date"];
							$request = $request . " AND Trace.DateCnx = '".$date."'";
						}
						$request = $request . " ORDER BY Trace.Id;";

						//Affichage du tableau résultant de la requête SQL :
						$dbh = new PDO("mysql:host=localhost;dbname=ProjetTrace", $user, $pass);
						foreach($dbh->query($request) as $row)
						{
							echo("<tr>");
								echo("<td><center>".$row["Id"]."</center></td>");
								echo("<td><center>".$row["Ip"]."</center></td>");
								echo("<td><center>".$row["Host"]."</center></td>");
								echo("<td><center>".$row["DateCnx"]."</center></td>");
								echo("<td><center>".$row["Id_User"]."</center></td>");
								echo("<td><center>".$row["Name"]."</center></td>");
								echo("<td><center>".$row["Id_Page"]."</center></td>");
								echo("<td><center>".$row["Page"]."</center></td>");
							echo("</tr>");
						}
					}
					catch (PDOException $e)
					{
    					print "Erreur : " . $e->getMessage() . "<br/>";
    					die();
					}
				?>
			</table>
			<br>
			<a id="filtres"></a>
			<form action="affichage.php" method="post">
				User
				<select name="Name">
					<option value="">all</option>
					<?php
						try
						{
							foreach($dbh->query("SELECT Name FROM User;") as $row)
							{
								echo("<option value='".$row["Name"]."'>".$row["Name"]."</option>");
							}
						}
						catch (PDOException $e)
						{
    						print "Erreur : " . $e->getMessage() . "<br/>";
    						die();
						}
					?>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;
				Page
				<select name="Page">
					<option value="">all</option>
					<?php
						try
						{
							foreach($dbh->query("SELECT Page FROM Page;") as $row)
							{
								echo("<option value='".$row["Page"]."'>".$row["Page"]."</option>");
							}
						}
						catch (PDOException $e)
						{
    						print "Erreur : " . $e->getMessage() . "<br/>";
    						die();
						}
					?>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;
				IP
				<select name="IP">
					<option value="">all</option>
					<?php
						try
						{
							foreach($dbh->query("SELECT DISTINCT Ip FROM Trace;") as $row)
							{
								echo("<option value='".$row["Ip"]."'>".$row["Ip"]."</option>");
							}
						}
						catch (PDOException $e)
						{
    						print "Erreur : " . $e->getMessage() . "<br/>";
    						die();
						}
					?>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;
				Date
				<input type="datetime-local" name="Date"></input>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" value="Valider">
			</form>
			<br>
			<br>
			<br>
			<br>
			<hr>
			<br>
			<br>
			<h2><u>Table User</u></h2>
			<table>
				<tr>
					<th>Id</th>
					<th>Date de création</th>
					<th>Nom</th>
				</tr>
				<?php
					try
					{
						foreach($dbh->query("SELECT * FROM User;") as $row)
						{
							echo("<tr>");
								echo("<td><center>".$row["Id"]."</center></td>");
								echo("<td><center>".$row["DateCreation"]."</center></td>");
								echo("<td><center>".$row["Name"]."</center></td>");
							echo("</tr>");
						}
					}
					catch (PDOException $e)
					{
    					print "Erreur : " . $e->getMessage() . "<br/>";
    					die();
					}
				?>
			</table>
			<br>
			<br>
			<br>
			<br>
			<hr>
			<br>
			<br>
			<h2><u>Table Page</u></h2>
			<table>
				<tr>
					<th>Id</th>
					<th>Date de création</th>
					<th>Exclude</th>
					<th>Page</th>
				</tr>
				<?php
					try
					{
						foreach($dbh->query("SELECT * FROM Page;") as $row)
						{
							echo("<tr>");
								echo("<td><center>".$row["Id"]."</center></td>");
								echo("<td><center>".$row["DateCreation"]."</center></td>");
								echo("<td><center>".$row["exclude"]."</center></td>");
								echo("<td><center>".$row["Page"]."</center></td>");
							echo("</tr>");
						}
					}
					catch (PDOException $e)
					{
    					print "Erreur : " . $e->getMessage() . "<br/>";
    					die();
					}
					$dbh = null;
				?>
			</table>
			<br>
			<br>
			<br>
			<br>
		</center>
	</body>
</html>