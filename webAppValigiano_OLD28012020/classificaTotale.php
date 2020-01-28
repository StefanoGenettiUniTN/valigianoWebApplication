<?php
    require_once("DBconfig.php");
?>

    <head>
        <title>Valigiano Web Application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="w3.css">
        <script src="jquery-3.4.1.min.js"></script>
        <script src="sorttable.js"></script>

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
            /**Vengono stampati solo gli atleti che hanno partecipato ad almeno 3 gare

             * garePartecipate conta le gare; prende in considerazione solo le gare alle quali l'atleta ha
             * partecipato con classifica.punteggio>0. Infatti a una gara prendi sempre almeno 1 punto
             */
            $selectQuery  = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome, SUM(classifica.punteggio) AS punteggioTotale, COUNT(classifica.id_utente) AS garePartecipate FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.punteggio>0 GROUP BY utente.ID HAVING garePartecipate>=3 ORDER BY categoria.nome ASC, punteggioTotale DESC, utente.data_nascita ASC;";
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
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["punteggioTotale"] . "</td>
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
