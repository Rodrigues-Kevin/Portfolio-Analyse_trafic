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
			<h1><u>Projet Trace - Analyse</u></h1>
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
						<a href="affichage.php">
    						<button>Affichage</button>
						</a> 
					</th>
				</tr>
			</table>
			<br>
			<br>
			<hr>
			<br>
			<br>
			<h2><u>Nombre de connexions pour un jour ou une semaine</u></h2>
			<form action="analyse.php" method="post">
				User
				<select name="Name">
					<option value="">all</option>
					<?php
						$user = "root";
						$pass = "";
                        $dbh = new PDO("mysql:host=localhost;dbname=ProjetTrace", $user, $pass);
	
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
				Jour
				<input type="date" name="Date"></input>
                &nbsp;&nbsp;&nbsp;&nbsp;
				Semaine
				<input type="week" name="Date"></input>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" value="Valider">
			</form>
            <br>
            <?php
				if ($_POST["Name"] != "")
				{
					$name = $_POST["Name"];
					echo $name;
                    $row = $dbh->query("SELECT COUNT(Trace.Id) AS Nb FROM Trace LEFT JOIN User ON Trace.Id_User = User.Id WHERE User.Name = '" . $name . "' AND DateCnx BETWEEN '2023-01-08 00:00:00' AND '2023-01-08 23:59:59';");
                    echo $row["Nb"];
				}
            ?>
			<br>
			<br>
			<br>
			<br>
		</center>
	</body>
</html>