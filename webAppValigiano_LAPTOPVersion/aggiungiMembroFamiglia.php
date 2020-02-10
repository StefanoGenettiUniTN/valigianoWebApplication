<?php

/**Visualizza utenti da aggiungere alla famiglia passata con GET*/

session_start();
require_once("DBconfig.php");
require_once("function.php");

if(isset($_GET["famID"])) {
    $famID = $_GET["famID"];  //id famiglia selezionata

    ?>

    <html>

    <head>
        <title>Valligiano Web Application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="w3.css">
        <link rel="icon" href="immagini/logo.png" type="image/gif" sizes="16x16">
        <script src="jquery-3.4.1.min.js"></script>
        <script src="sorttable.js"></script>

        <style>
            .riga {
                cursor: pointer;
            }
        </style>

        <script>

            /*Riceve in input dati utente e inserisce membro in relazioneFamigliare*/
            function addRelative(idUtente, idFamiglia){
                var categoriaSelezionata = $("#catFilter").val();
                if(idUtente, idFamiglia){
                    $.post("addFamilyMember.php",
                        {
                            userID: idUtente,
                            catID: categoriaSelezionata,
                            famID: idFamiglia
                        },
                        function(data, status){
                            $("#outputJQ").html(data);
                        });
                }
            }


            /*
            function filtroCategoria(valore){
                if(valore=="all"){
                    $('.record').show();
                }else{
                    $('.record').hide();
                    $(".cat"+valore).show();
                }
            }
            */

            function filtroCategoria(valore, idFamiglia){   //valore contiene la categoria selezionata
                $.ajax({
                    url: 'categoryFilterAggiungiMembroFamiglia.php?catID='+valore+'&famID='+idFamiglia,  //catID=categoria selezionata
                    success: function(data) {
                        $("#outputJQ").html(data);
                    }
                });
            }


            function infoUser(link) {
                window.location.href = "profiloUtente.php?userID="+link;
            }

            /**Aggiorna libreria sorttable*/
            function refreshSortable(){
                var newTableObject = document.getElementById("myTable");
                sorttable.makeSortable(newTableObject);
            }

        </script>

    </head>

    <body>
    <a href="profiloFamiglia.php?famID=<?php echo"$famID";?>" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 10%;"><i style="margin-right: 30px;"><img src="round_keyboard_backspace_black_18dp.png"></i>Torna alla famiglia</button></a>
    <div class="w3-card w3-margin w3-round">
        <?php
        $queryTitolo = "SELECT * FROM famiglia WHERE ID=".$famID.";";
        $risQueryTitolo = $conn->query($queryTitolo);
        if($outTitolo = $risQueryTitolo->fetch_assoc())
            echo "<h2 class='w3-margin w3-container' style='height: 10%;'><b>FAMIGLIA:</b> ".$outTitolo["nome"]." - <u>Aggiunta famigliari</u></h2>";
        else
            echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  #TODO error page
        ?>
    </div>

    <div style="margin-right: 10%; margin-top: 2%; width: 20%;" class="w3-margin-bottom">
        <p class="w3-margin-left">Filtra per categoria:</p>
        <select id="catFilter" class='w3-select w3-border w3-round' name=\"filtroCategoria\" style="margin-left: 10%;" onchange="filtroCategoria(this.value, <?php echo $famID;?>);">
            <option value="all" selected>Mostra tutte le categorie</option>
            <?php
            /*RICERCA CATEGORIA PER SELECT*/
            $queryCategoria = "SELECT id, nome FROM categoria;";
            $risCategoria = $conn->query($queryCategoria);
            while($categoria = $risCategoria->fetch_assoc()) {
                echo "<option value='".$categoria["id"]."'>".$categoria["nome"]."</option>";
            }
            ?>
        </select>

    </div>
    <hr style="margin:auto; margin-top: 2%; margin-bottom: 3%; width: 95%;">

    <div id="outputJQ"><!--/Output JQUERY.../-->

        <div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
            <table align="center" style="width: 90%;" id="myTable" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border sortable">
                <tr class="w3-green">
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Societ&agrave</th>
                    <th>Categoria</th>
                    <th>Pettorina</th>
                </tr>
                <?php
                $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, societa.nome AS nome_societa, societa.ID FROM societa, categoria, utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID ORDER BY categoria.nome ASC, utente.nome ASC, utente.cognome ASC, societa.nome ASC;";
                $ris = $conn->query($query);

                /**RICERCO UTENTI GIA ISCRITTI ALLA FAMIGLIA, CHE NON STAMPO (controllo con funzione isRelative())*/
                $parenti = array();
                $queryCercaParenti = "SELECT id_utente, id_famiglia FROM relazionefamigliare WHERE id_famiglia = ".$famID.";";
                $risAlreadySubscribed = $conn->query($queryCercaParenti);
                while($outAlreadySubscribed = $risAlreadySubscribed->fetch_assoc()){
                    array_push($parenti,$outAlreadySubscribed["id_utente"]);   //inserisco nell'array gli ID di quelli gia iscritti
                }
                /**....*/

                while($outUtenti = $ris->fetch_assoc()){
                    if(!isRelative($parenti, $outUtenti["userID"])) {    //Stampo solo se l'utente non è già iscritto
                        //Si usa classe cat[idCategoria] per select JQuery categoria
                        echo "
                        <tr class='cat" . $outUtenti["id_categoria"] . " record'>
                            <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>" . $outUtenti["nome_utente"] . "</td>
                            <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>" . $outUtenti["cognome_utente"] . "</td>
                            <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>" . $outUtenti["nome_societa"] . "</td>
                            <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>" . $outUtenti["nome_categoria"] . "</td>
                            <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>" . $outUtenti["pettorina_utente"] . "</td>
                            ";
                        echo "
                            <td><button onclick='addRelative(" . $outUtenti["userID"] . ", " . $famID . ");' class=\"w3-btn w3-ripple\"><img src='outline_add_black_18dp.png'></button></td>
                            ";

                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>

    </div><!--/Output JQUERY.../-->
    </body>

    </html>

    <?php
}else{
    echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>"; //TODO Error page
    header("location: errorPage.php");
}
?>