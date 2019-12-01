<?php
    require_once("DBconfig.php");

    if(isset($_GET["userID"])) {
        $userID = $_GET["userID"];  //id utente selezionato

?>

<html>

    <head>
        <title>Valigiano Web Application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="w3.css">
        <script src="jquery-3.4.1.min.js"></script>
    </head>

    <style>
        /**Gestione w3card stampa elementi stessa riga*/
        .w3CardPadre {
            list-style: none;
            white-space: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .w3TestoFiglio {
            display: inline-block;
        }
    </style>

<body>
    <a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
    <div class="w3-card w3-margin w3-round">
        <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 8%;">Profilo utente</h2>
    </div>

    <?php
        $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID, utente.id_categoria, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, SUM(classifica.punteggio) AS punteggio FROM categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.ID=".$_GET["userID"]." GROUP BY utente.ID;";
        $ris = $conn->query($query);

        while($outUtente = $ris->fetch_assoc()){
            echo "<p>".$outUtente["nome_utente"]."</p>";
            /*
            echo "
                    <tr href='profiloUtente.php'>
                        <td>".$outUtente["nome_utente"]."</td>
                        <td>".$outUtente["cognome_utente"]."</td>
                        <td>".$outUtente["sesso_utente"]."</td>
                        <td>".$outUtente["data_nascita_utente"]."</td>
                        <td>".$outUtente["nome_categoria"]."</td>
                        <td>".$outUtente["pettorina_utente"]."</td>
                        ";

            if(is_null($outUtente["punteggio"])){
                echo "<td> - </td>";
            }else{
                echo "<td>".$outUtente["punteggio"]."</td>";
            }
            echo "</tr>";
            */
        }
    ?>

    <div style="width:40%; height: fit-content;" class="w3-card-4 w3-margin w3-round w3-white w3CardPadre">
        <div style = "height: 30%; width: 30%;" class="w3-center w3-display-container w3-margin w3-padding w3TestoFiglio">
                <img src="man.png" class="w3-circle" style="height:106px;width:106px" alt="Avatar">
        </div>
        <div class="w3TestoFiglio">
        <h4 class="w3-margin-left">My Profile</h4>
        <h5 class="w3-margin-left">CIAO</h5>
        <h5 class="w3-margin-left">CIAO</h5>
        <h5 class="w3-margin-left">CIAO</h5>
        <h5 class="w3-margin-left">CIAO</h5>
        <h5 class="w3-margin-left">CIAO</h5>
        <h5 class="w3-margin-left">CIAO</h5>
        <h5 class="w3-margin-left">CIAO</h5>
        </div>
    </div>

</body>

</html>

<?php
    }else{
        echo "Error!";  //#TODO Error page
    }
?>