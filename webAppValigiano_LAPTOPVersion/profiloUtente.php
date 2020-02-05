<?php
    require_once("DBconfig.php");

    if(isset($_GET["userID"])) {
        $userID = $_GET["userID"];  //id utente selezionato

?>

<html>

    <head>
        <title>Valligiano Web Application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="w3.css">
        <link rel="icon" href="immagini/logo.png" type="image/gif" sizes="16x16">
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

        /**Colonna immagine*/
        .tdImg {
            width: 30%;
            height: 100%;
            text-align: left;
            vertical-align: bottom;
            position: relative;
        }

        /**Colonna testo*/
        .tdLabel {
            width: 80%;
            height: 100%;
            text-align: left;
            vertical-align: bottom;
            position: relative;
        }

        /**Immagine*/
        .avatarIcon {
            width: 90%;
            height: auto;
            display: inline-block;
            margin-right: 5px;
            position: absolute;
            top: 0;
        }
    </style>

<body>
    <a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 10%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
    <div class="w3-card w3-margin w3-round">
        <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 10%;">Profilo atleta</h2>
    </div>

    <?php
        $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID, utente.id_categoria, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND utente.ID=".$_GET["userID"]." GROUP BY utente.ID;";
        $queryGare = "SELECT gara.ID, gara.luogo AS gara_luogo, gara.data AS gara_data, utente.ID, classifica.id_utente, classifica.id_gara, classifica.punteggio, classifica.posClassifica FROM utente, gara, classifica WHERE utente.ID = classifica.id_utente AND gara.ID = classifica.id_gara AND utente.ID=".$_GET["userID"].";";
        $ris = $conn->query($query);

        while($outUtente = $ris->fetch_assoc()) {
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


            echo "
                <div style=\"width:70%; height: fit-content; margin-left: 5%;\" class=\"w3-card-4 w3-white w3-border\">
                    <table style=\"width: 80%;\">
                        <tr>
                ";

            if($outUtente["sesso_utente"] == "M"){    //utente maschio --> icona avatar maschile; else icona avatar femminile
                echo "
                            <td class=\"tdImg\">
                                <img src=\"immagini/rectangularMale.png\" class=\"w3-circle avatarIcon w3-margin w3-bottombar w3-border\" alt=\"Avatar\">
                            </td>
                 ";
            }else{
                echo "
                            <td class=\"tdImg\">
                                <img src=\"immagini/rectangularFemale.png\" class=\"w3-circle avatarIcon w3-margin w3-bottombar w3-border\" alt=\"Avatar\">
                            </td>
                 ";
            }

            echo "
                            <td>
                            <div class=\"tdLabel\">
                                <h3 style=\"margin-left: 25%\" ><b>".$outUtente["nome_utente"]." ".$outUtente["cognome_utente"]."</b></h3><br>
                                <h5 style=\"margin-left: 20%\"><b>Data di nascita:</b>  ".$outUtente["data_nascita_utente"]."</h5>
                                <h5 style=\"margin-left: 20%\"><b>Sesso:</b>  ".$outUtente["sesso_utente"]."</h5>
                                <h5 style=\"margin-left: 20%\"><b>Categoria:</b>  ".$outUtente["nome_categoria"]."</h5>
                                <h5 style=\"margin-left: 20%\"><b>Pettorina:</b>  ".$outUtente["pettorina_utente"]."</h5>
                                <h5 style=\"margin-left: 20%\"><b>Societ&agrave:</b>  ".$outUtente["nome_societa"]."</h5>
                            </div>
                            </td>
                        </tr>
                    </table>
            ";
        }
    ?>

    <!--La tabella e' finita ma la card continua (riusultati delle gare)-->
    <hr style="width: 60%; margin-left: 20%; border: 10px solid green;">
    <h4 style="margin-left: 5%" ><i>RISULTATI GARE</i></h4><br>

    <table class="w3-table">
        <tr>
            <th></th>
            <th></th>
            <th>Punteggio</th>
            <th>Posizione</th>
        </tr>

    <?php

    $ris_queryGare = $conn->query($queryGare);
    $punteggioTotale = 0;   //somma tutti punteggi delle gare; #TODO aggiungere con SUM alla query "$queryGare"

    while($outGare = $ris_queryGare->fetch_assoc()) {
        $punteggioTotale+=$outGare["punteggio"];
        echo "            
                <tr>
                    <td>".$outGare["gara_luogo"]."</td>
                    <td>".$outGare["gara_data"]."</td>
                    <td>".$outGare["punteggio"]."</td>
                    <td>".$outGare["posClassifica"]."</td>
                </tr>
        ";
    }
    ?>
    </table>

    <hr>
    <h6 style="margin-left: 5%" ><i>PUNTEGGIO TOTALE: </i><?php echo $punteggioTotale;?></h6><br>

    <!--
    echo "<div class=\"w3-card\" style='margin-bottom: 20px; width: 50%;'>
        <img src='".$rig["immagine"]."' style='float: left;'>
        <div style='margin-left: 300px; padding: 50px;'>
            <p><b>Tipo: </b>".$rig["nomeComune"]."</p>
            <p><b>Codice Anagrafico: </b>".$rig["codiceAnagrafico"]."</p>
            <p><b>Sesso: </b>".$rig["sesso"]."</p>
            <p><b>Data nascita: </b>".$rig["dataNascita"]."</p>
            <p><b>Luogo nascita: </b>".$rig["luogoNascita"]."</p>
            <br><br>

            <p><b>Prezzo: ".$rig["prezzo"]."</b></p>

            <button class='w3-btn w3-section w3-teal w3-ripple' id='addToChart' onclick='aggiungiAlCarrello(".$rig["id"].")'>Aggiungi al carrello</button>
        </div>
    </div>";
    -->

    <!-- Footer -->
    <?php //include("footerLayout.php"); #TODO aggiungere footer?>
</body>
</html>

<?php
    }else{
        echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  //#TODO Error page
        header("location: errorPage.php");
    }
?>