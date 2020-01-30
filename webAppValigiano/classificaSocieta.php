<?php
require_once("DBconfig.php");
?>

<head>
    <title>Valligiano Web Application</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="w3.css">
    <link rel="icon" href="immagini/logo.png" type="image/gif" sizes="16x16">
    <script src="jquery-3.4.1.min.js"></script>
    <script src="sorttable.js"></script>
    <script src="print.min.js"></script>
    <link rel="stylesheet" type="text/css" href="print.min.css">

    <script>

        /**Nel DIV OUTPUTJQUERY restituisce la tabella relativa ai risultati della gara selezionata*/
        function stampaRisultatiGara(garaSelezionata){
            $.post("displayRaceRankSociety.php",
                {
                    garID: garaSelezionata
                },
                function(data, status){
                    $("#outputJQ").html(data);
                });
        }

    </script>

</head>

<body>
<a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
<div class="w3-card w3-margin w3-round">
    <h2 class='w3-margin w3-container' style='height: 8%;'>Classifica societ&agrave</h2>
</div>

<h5 class="w3-margin-left w3-padding">Specifichi la <b>gara</b></h5>
<select id="garFilter" class='w3-select w3-border w3-round w3-margin-left' name="filtroGara" style="width: 90%;" onchange='stampaRisultatiGara(this.value);'>
    <option value="zero" selected>Nessuna gara selezionata</option>
    <?php
    /*RICERCA Gare PER SELECT*/
    $queryGara = "SELECT * FROM gara;";
    $risGara = $conn->query($queryGara);
    while($gara = $risGara->fetch_assoc()) {
        echo "<option value='".$gara["ID"]."'>".$gara["luogo"]."</option>";
    }
    ?>
</select>

<button class="w3-button w3-margin-top w3-margin-left w3-round-large w3-centered w3-deep-orange" style="width: 20%;" onclick="printJS({ printable: 'TabellaRis', type: 'html', header: 'Classifica società', headerStyle: 'font-size: 15;'});">STAMPA</button>

<hr style="margin:auto; margin-top: 2%; margin-bottom: 3%; width: 95%;">

<div id="outputJQ"><!--/Output JQUERY.../-->

    <div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
        <table id="TabellaRis" align="center" style="width: 90%;" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border">
        <tr class="w3-green">
            <th>Posizione</th>
            <th>Punteggio Totale</th>
            <th>Nome Societa</th>
        </tr>
    <?php
        //Query
        //$selectQuery  = "SELECT *, societa.nome AS societa_nome, SUM(classifica.punteggio) AS punteggioTotale, COUNT(classifica.id_utente) AS garePartecipate FROM classifica, utente, societa WHERE classifica.id_utente = utente.ID AND utente.id_societa = societa.ID AND classifica.punteggio>0 GROUP BY societa.ID, utente.ID HAVING garePartecipate>=3 ORDER BY punteggioTotale DESC;";
        //$selectQuery = "SELECT *, societa.nome AS societa_nome, SUM(classifica.punteggio) AS punteggioTotale, COUNT(classifica.id_utente) AS garePartecipate FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_societa = societa.ID AND classifica.punteggio>0 GROUP BY societa.ID HAVING garePartecipate>=3 ORDER BY punteggioTotale DESC;";
        $selectQuery = "SELECT *, SUM(classifica.punteggio) AS punteggioTotale, societa.nome AS societa_nome FROM utente, classifica, societa WHERE utente.id_societa=societa.ID AND classifica.id_utente=utente.ID AND utente.ID IN (SELECT utente.ID FROM utente,classifica WHERE utente.ID=classifica.id_utente AND classifica.punteggio>0 GROUP BY utente.ID HAVING COUNT(classifica.id_utente)>=3) GROUP BY societa.ID ORDER BY punteggioTotale DESC;";
        $risultatoSelectQuery = $conn->query($selectQuery);
        $posizione = 1;
        while($outSocieta = $risultatoSelectQuery->fetch_assoc()){
        echo "
        <tr>
            <td class='riga'>" . $posizione . "</td>
            <td class='riga'>" . $outSocieta["punteggioTotale"] . "</td>
            <td class='riga'>" . $outSocieta["societa_nome"] . "</td>
            ";
            echo "</tr>";
        $posizione++;
        }
    ?>
        </table>
    </div>

</div> <!--Output JQUERY-->
</body>
</html>

