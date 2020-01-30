<?php
require_once("DBconfig.php");

if(isset($_GET["famID"])) {
    $famID = $_GET["famID"];  //id famiglia selezionata

    ?>

    <head>
        <title>Valligiano Web Application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="w3.css">
        <link rel="icon" href="immagini/logo.png" type="image/gif" sizes="16x16">
        <script src="jquery-3.4.1.min.js"></script>
        <script src="sorttable.js"></script>

        <script>

            /**Elimina utente*/
            function rimuoviParente(nome, idFamiglia, idUtente){
                //var categoriaSelezionata = $("#catFilter").val();
                if(confirm("L'utente "+nome+" sta per essere rimosso dalla famiglia")) {
                    $.ajax({
                        url: 'removeRelative.php?famID=' + idFamiglia + '&userID=' + idUtente,  //idUtente contiene l'id dell'utente da rimuovere
                        success: function (data) {
                            $("#outputJQ").html(data);
                        }
                    });
                }
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
    <a href="modificaFamiglia.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="round_keyboard_backspace_black_18dp.png"></i>Torna alla lista famiglie</button></a>
    <div class="w3-card w3-margin w3-round">
        <?php
        $queryTitolo = "SELECT * FROM famiglia WHERE ID=".$famID.";";
        $risQueryTitolo = $conn->query($queryTitolo);
        if($outTitolo = $risQueryTitolo->fetch_assoc())
            echo "<h2 class='w3-margin w3-container' style='height: 8%;'><b>FAMIGLIA: </b> ".$outTitolo["nome"]." - <u>Membri</u></h2>";
        else
            echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  #TODO error page
        ?>
    </div>

    <hr style="margin:auto; margin-top: 2%; width: 95%;" class="w3-margin-bottom">

    <a href="aggiungiMembroFamiglia.php?famID=<?php echo $famID;?>"class="w3-ripple w3-teal w3-button w3-block w3-centered" style="margin:auto; margin-top: 3%; width: 90%;" >AGGIUNGI MEMBRO</a><br>

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
                $query = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM utente, categoria, societa, relazionefamigliare WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND relazionefamigliare.id_utente = utente.ID AND relazionefamigliare.id_famiglia=".$famID." ORDER BY utente.nome, utente.cognome;";
                $ris = $conn->query($query);

                while($outParenti = $ris->fetch_assoc()){
                    //Si usa classe cat[idCategoria] per select JQuery categoria
                    echo "
                <tr class='cat".$outParenti["id_categoria"]." record'>
                    <td class='riga' href='profiloUtente.php?userID=".$outParenti["id_utente"]."' onclick='infoUser(".$outParenti["id_utente"].");'>".$outParenti["utente_nome"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outParenti["id_utente"]."' onclick='infoUser(".$outParenti["id_utente"].");'>".$outParenti["utente_cognome"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outParenti["id_utente"]."' onclick='infoUser(".$outParenti["id_utente"].");'>".$outParenti["societa_nome"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outParenti["id_utente"]."' onclick='infoUser(".$outParenti["id_utente"].");'>".$outParenti["categoria_nome"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outParenti["id_utente"]."' onclick='infoUser(".$outParenti["id_utente"].");'>".$outParenti["n_pettorina"]."</td>
                    ";
                echo "<td><button onclick='rimuoviParente(\"".$outParenti["utente_nome"]."\",".$famID.", ".$outParenti["id_utente"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>";
                echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div><!--/Output JQUERY.../-->
    </body>
    </html>


    <?php
}else{
    echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  //#TODO Error page
    header("location: errorPage.php");
}
?>
