<?php
    require_once("DBconfig.php");
    require_once("function.php");
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

        <style>
            .riga {
                cursor: pointer;
            }
        </style>

        <script>

            function infoUser(link) {
                window.location.href = "profiloUtente.php?userID="+link;
            }


            /**Nel DIV OUTPUTJQUERY restituisce la tabella relativa ai risultati della categoria selezionata*/
            function stampaRisultatiCategoria(categoriaSelezionata){
                if(categoriaSelezionata){
                    $.post("displayCategoryRank.php",
                        {
                            catID: categoriaSelezionata
                        },
                        function(data, status){
                            $("#outputJQ").html(data);
                        });
                }
            }

        </script>

    </head>

    <body>
    <a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
    <div class="w3-card w3-margin w3-round">
        <h2 class='w3-margin w3-container' style='height: 8%;'>Classifica complessiva</h2>
    </div>


    <h5 class="w3-margin-left w3-padding">Specifichi la <b>cetegoria</b> da stampare.</h5>
    <select id="catFilter" class='w3-select w3-border w3-round w3-margin-left' name="filtroCategoria" style="width: 90%;" onchange='stampaRisultatiCategoria(this.value);'>
        <option value="zero" selected>Nessuna categoria selezionata</option>
        <?php
        /*RICERCA CATEGORIA PER SELECT*/
        $queryCategoria = "SELECT id, nome FROM categoria;";
        $risCategoria = $conn->query($queryCategoria);
        while($categoria = $risCategoria->fetch_assoc()) {
            echo "<option value='".$categoria["id"]."'>".$categoria["nome"]."</option>";
        }
        ?>
    </select>

    <button class="w3-button w3-margin-top w3-margin-left w3-round-large w3-centered w3-deep-orange" style="width: 20%;" onclick="printJS({ printable: 'TabellaRis', type: 'html', header: 'Classifica complessiva', headerStyle: 'font-size: 15;', style: 'table, th, td {border: 1px solid black;} table {border-collapse: collapse;} th, td {text-align: center;}'});">STAMPA</button><br>

    <hr style="margin:auto; margin-top: 2%; margin-bottom: 3%; width: 95%;">

    <div id="outputJQ"><!--/Output JQUERY.../-->

        <div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
            <table id="TabellaRis" align="center" style="width: 90%;" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border">
                <!--INTESTAZIONE-->
                <tr class="w3-green">
                    <th>Posizione</th>
                    <th>Punteggio totale</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Societ&agrave</th>
                    <th>Categoria</th>
                    <th>Pettorina</th>
                </tr>
                <!--...-->
            <?php
            //Query
            /**Vengono stampati solo gli atleti che hanno partecipato ad almeno 2 gare

             * garePartecipate conta le gare; prende in considerazione solo le gare alle quali l'atleta ha
             * partecipato con classifica.punteggio>0.
             *
             *
             * punteggioEsatto è quello che viene stampato. In questa versione NON vengono dati punti in più nel caso l'atleta abbia fatto tutte le gare.
             *
             */
            //$selectQuery  = "SELECT *,utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome, SUM(classifica.punteggio) AS punteggioTotale, MIN(classifica.punteggio) AS garaPeggiore, COUNT(classifica.id_utente) AS garePartecipate, AVG(classifica.posClassifica) mediaPosizione, IF((COUNT(classifica.id_utente))>=5, (SUM(classifica.punteggio))-(MIN(classifica.punteggio))+2, (SUM(classifica.punteggio))-(MIN(classifica.punteggio))) AS punteggioEsatto FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.punteggio>0 GROUP BY utente.ID HAVING garePartecipate>=3 ORDER BY categoria.nome ASC, punteggioEsatto DESC, mediaPosizione ASC, utente.data_nascita ASC;";
            //$selectQuery = "SELECT *,utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome, SUM(classifica.punteggio) AS punteggioTotale, COUNT(classifica.id_utente) AS garePartecipate, IF((COUNT(classifica.id_utente))>=3, (SUM(classifica.punteggio))+2, (SUM(classifica.punteggio))) AS punteggioEsatto FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.punteggio>0 GROUP BY utente.ID HAVING garePartecipate>=2 ORDER BY categoria.nome ASC, punteggioEsatto DESC, utente.data_nascita DESC;";
            $selectQuery = "SELECT *,utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome, SUM(classifica.punteggio) AS punteggioTotale, COUNT(classifica.id_utente) AS garePartecipate, SUM(classifica.punteggio) AS punteggioEsatto FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.punteggio>0 GROUP BY utente.ID HAVING garePartecipate>=2 ORDER BY categoria.nome ASC, punteggioEsatto DESC, CASE
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome='15-Senior-M' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome='16-Senior-F' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome='17-AmatoriA-M' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome='18-AmatoriA-F' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome='19-AmatoriB-M' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome='20-AmatoriB-F' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome='21-VeteraniA-M' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome='22-VeteraniA-F' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome='23-VeteraniB-M' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome='24-VeteraniB-F' THEN utente.data_nascita  
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                END ASC,
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             CASE
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome<>'15-Senior-M' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome<>'16-Senior-F' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome<>'17-AmatoriA-M' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome<>'18-AmatoriA-F' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome<>'19-AmatoriB-M' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome<>'20-AmatoriB-F' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome<>'21-VeteraniA-M' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome<>'22-VeteraniA-F' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome<>'23-VeteraniB-M' THEN utente.data_nascita
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                WHEN categoria.nome<>'24-VeteraniB-F' THEN utente.data_nascita  
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                END DESC;";

            $risultatoSelectQuery = $conn->query($selectQuery);
            $posizione = 1;
            $categoriaCorrente = "nessuna"; //tiene traccia della categoria che si sta stampando per stampare correttamente le posizioni (quando cambia categoria $posizione=1)
                while($outUtenti = $risultatoSelectQuery->fetch_assoc()){
                    if($categoriaCorrente != $outUtenti["categoria_nome"]){ //#TODO selezione per idCategoria
                        $posizione=1;
                        $categoriaCorrente = $outUtenti["categoria_nome"];
                    }
                    echo "
                    <tr class='cat" . $outUtenti["id_categoria"] . " record'>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $posizione . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["punteggioEsatto"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["utente_nome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["utente_cognome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["societa_nome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["categoria_nome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["n_pettorina"] . "</td>
                        ";
                        echo "</tr>";
                        $posizione++;
                }
            ?>
            </table>
        </div>

    </div>

    </body>
</html>

