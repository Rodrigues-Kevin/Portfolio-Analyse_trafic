<html>
    <head>
        <meta charset="utf-8" />
		<title>Projet Trace - Insertion</title>
        <link rel="stylesheet" href="style.css" type="text/css" />
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    </head>
    <body>
        <?php
            echo "<center><h1><u>Projet Trace - Insertion</u></h1></center>";

            //Enregistrement en String du contenu du fichier 'trace.txt' :
            $fichier = "trace.txt";
            $contenu = file_get_contents($fichier);

            //Met dans une liste chaque élément important du contenu du fichier :
            $element = "";
            $listElements = array();
            for ($i = 0; $i < strlen($contenu); $i++)
            {
                if ($contenu[$i] != " " && !str_contains($element, "-----------------------------------"))
                {
                    $element = $element . $contenu[$i];
                }
                else
                {
                    if (str_contains($element, ":"))
                    {
                        $input = "";
                        for ($j1 = 0; $j1 < 5; $j1++)
                        {
                            $input = $input . $element[$j1];
                        }
                        array_push($listElements, $input);
                        $input = "";
                        for ($j2 = 5; $j2 < strlen($element); $j2++)
                        {
                            $input = $input . $element[$j2];
                        }
                        array_push($listElements, $input);
                    }
                    else
                    {
                        array_push($listElements, $element);
                    }
                    $element = "";
                }
            }

            //Connexion à la base de données :
            require_once "inc/dbConst.php";
            require_once "inc/dbConst.php";
            try
            {
                $dbh = new PDO("mysql:host=$dbserver;dbname=$dbname", $user, $pass);
            }
            catch (PDOException $e)
            {
                print "Erreur : " . $e->getMessage() . "<br/>";
                die();
            }

            //Insère les données dans la base de données après les avoir trouver :
            $indexUser = -1;
            $indexPage = -1;
            $indexIp = -1;
            $indexHost = -1;
            $indexDateCnx = -1;
            $listTraceData = array();
            for ($i = 0; $i < sizeof($listElements); $i++)
            {
                //Trouve l'index du nom, de la page visitée, de l'adresse IP, du host et de la date de connexion pour chaque client :
                if ($listElements[$i] == "Client")
                {
                    $indexUser = $i + 8;
                    $indexPage = $i + 10;
                    $indexIp = $i + 6;
                    $indexHost = $i + 2;
                    $indexDateCnx = $i + 15;
                }

                //Insère dans la table User le nom et la date de création du client sauf si le client est déjà enregistrer :
                if ($i == $indexUser)
                {
                    //Si le client possède un nom :
                    if ($listElements[$i] != "")
                    {
                        //Recherche si le client est déjà dans la table User :
                        try
                        {
                            $row = $dbh->query("SELECT Name FROM User WHERE Name = '" . $listElements[$i] . "';");
                        }
                        catch (PDOException $e)
                        {
                            print "Erreur : " . $e->getMessage() . "<br/>";
                            die();
                        }

                        //Insère les données dans la table User si le client n'existe pas :
                        if ($row->rowCount() == 0)
                        {
                            $date = date("Y-m-d H:i:s");
                            try
                            {
                                $sql = "INSERT INTO User (DateCreation, Name) VALUES ('" . $date . "', '" . $listElements[$i] . "');";
                                echo $sql . "<br>";
                                $dbh -> exec($sql);
                            }
                            catch (PDOException $e)
                            {
                                print "Erreur : " . $e->getMessage() . "<br/>";
                                die();
                            }
                        }
                    }
                    array_push($listTraceData, $listElements[$i]);
                }

                //Insère dans la table Page le nom et la date de création de la page sauf si la page est déjà enregistrer :
                else if ($i == $indexPage)
                {
                    //Recherche si la page est déjà dans la table Page :
                    try
                    {
                        $row = $dbh->query("SELECT Page FROM Page WHERE Page = '" . $listElements[$i] . "';");
                    }
                    catch (PDOException $e)
                    {
                        print "Erreur : " . $e->getMessage() . "<br/>";
                        die();
                    }

                    //Insère les données dans la table Page si la page n'existe pas :
                    if ($row->rowCount() == 0)
                    {
                        $date = date("Y-m-d H:i:s");
                        try
                        {
                            $sql = "INSERT INTO Page (DateCreation, Page) VALUES ('" . $date . "', '" . $listElements[$i] . "');";
                            echo $sql . "<br>";
                            $dbh -> exec($sql);
                        }
                        catch (PDOException $e)
                        {
                            print "Erreur : " . $e->getMessage() . "<br/>";
                            die();
                        }
                    }
                    array_push($listTraceData, $listElements[$i]);
                }

                //Trouve l'host du client :
                else if ($i == $indexHost)
                {
                    array_push($listTraceData, $listElements[$i]);
                }

                //Trouve l'IP du client :
                else if ($i == $indexIp)
                {
                    array_push($listTraceData, $listElements[$i]);
                }

                //Trouve la date de connexion du client et la convertit au format DATETIME de SQL :
                else if ($i == $indexDateCnx)
                {
                    $date = $listElements[$i] . " " . $listElements[$i + 4] . ":00";
                    $date = $date[6] . $date[7] . $date[8] . $date[9] . "-" . $date[3] . $date[4] . "-" . $date[0] . $date[1] . " " . $date[11] . $date[12] . $date[13] . $date[14] . $date[15] . $date[16] . $date[17] . $date[18];
                    array_push($listTraceData, $date);
                }

                //Insère toutes les données d'un client dans la table Trace quand toutes les données de se client ont été collecter :
                if (count($listTraceData) == 5)
                {
                    //Cherche l'id du client dans la table User et si l'id n'existe pas alors l'id du client est NULL :
                    if ($listTraceData[2] != "")
                    {
                        try
                        {
                            foreach($dbh->query("SELECT Id FROM User WHERE Name = '" . $listTraceData[2] . "';") as $row)
                            {
                                $idUser = $row["Id"];
                            }
                        }
                        catch (PDOException $e)
                        {
                            print "Erreur : " . $e->getMessage() . "<br/>";
                            die();
                        }
                    }
                    else
                    {
                        $idUser = "NULL";
                    }

                    //Trouve l'id de la page :
                    try
                    {
                        foreach($dbh->query("SELECT Id FROM Page WHERE Page = '" . $listTraceData[3] . "';") as $row)
                        {
                            $idPage = $row["Id"];
                        }
                    }
                    catch (PDOException $e)
                    {
                        print "Erreur : " . $e->getMessage() . "<br/>";
                        die();
                    }

                    //Insère les données dans la table Trace :
                    try
                    {
                        $sql = "INSERT INTO Trace (Ip, Host, DateCnx, Id_User, Id_Page) VALUES ('" . $listTraceData[1] . "', '" . $listTraceData[0] . "', '" . $listTraceData[4] . "', ";
                        if ($idUser != "NULL")
                        {
                            $sql = $sql . "'" . $idUser . "', '" . $idPage . "');";
                        }
                        else
                        {
                            $sql = $sql . "NULL, '" . $idPage . "');";
                        }
                        echo $sql . "<br>";
                        $dbh -> exec($sql);
                    }
                    catch (PDOException $e)
                    {
                        print "Erreur : " . $e->getMessage() . "<br/>";
                        die();
                    }
                    $listTraceData = array();
                }
            }
            $dbh = null;
        ?>
    </body>
</html>