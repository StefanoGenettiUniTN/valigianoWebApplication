<?php
require_once("DBconfig.php");

if(isset($_GET["garaID"])) {
    $garaID = $_GET["garaID"];  //id gara selezionata

?>

    <head>
        <title>Valigiano Web Application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="w3.css">
        <script src="jquery-3.4.1.min.js"></script>
        <script src="sorttable.js"></script>

        <script>

            /**Elimina utente*/
            function rimuoviIscritto(nome, idGara, idUtente){
                if(confirm("L'utente "+nome+" sta per essere rimosso dalla lista iscritti alla gara"))
                    $.ajax({
                        url: 'removeSubscriber.php?raceID='+idGara+'&userID='+idUtente,  //idUtente contiene l'id dell'utente da rimuovere
                        success: function(data) {
                            $("#outputJQ").html(data);
                        }
                    });
            }

            function infoUser(link) {
                window.location.href = "profiloUtente.php?userID="+link;
            }

            function filtroCategoria(valore){
                if(valore=="all"){
                    $('.record').show();
                }else{
                    $('.record').hide();
                    $(".cat"+valore).show();
                }
            }

        </script>

    </head>

    <body>
    <a href="gara.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="round_keyboard_backspace_black_18dp.png"></i>Torna alla lista gare</button></a>
    <div class="w3-card w3-margin w3-round">
        <?php
            $queryTitolo = "SELECT * FROM gara WHERE ID=".$garaID.";";
            $risQueryTitolo = $conn->query($queryTitolo);
            if($outTitolo = $risQueryTitolo->fetch_assoc())
                echo "<h2 class='w3-margin w3-container' style='height: 8%;'><b>EVENTO:</b> ".$outTitolo["luogo"]."  ".$outTitolo["data"]." - <u>Iscritti</u></h2>";
            else
                echo "<script>alert('Fatal error');</script>";  #TODO error page
        ?>
    </div>

    <div style="margin-right: 10%; margin-top: 2%; width: 20%;" class="w3-margin-bottom">
        <p class="w3-margin-left">Filtra per categoria:</p>
        <select id="catFilter" class='w3-select w3-border w3-round' name=\"filtroCategoria\" style="margin-left: 10%;" onchange="filtroCategoria(this.value);">
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
    <hr style="margin:auto; margin-top: 2%; width: 95%;">

    <a href="aggiungiIscrittoGara.php?garaID=<?php echo"$garaID";?>" class="w3-ripple w3-teal w3-button w3-block w3-centered" style="margin:auto; margin-top: 3%; width: 90%;" >AGGIUNGI ISCRITTI</a><br>

    <div id="outputJQ"><!--/Output JQUERY.../-->

        <div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
            <table align="center" style="width: 90%;" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border sortable">
                <tr class="w3-green">
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Societ&agrave</th>
                    <th>Categoria</th>
                    <th>Pettorina</th>
                    <th>Punteggio</th>
                </tr>
                <?php
                $query = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.id_gara=".$garaID." ORDER BY categoria.nome ASC, utente.nome ASC, utente.cognome ASC;";
                $ris = $conn->query($query);

                while($outIscritti = $ris->fetch_assoc()){
                    //Si usa classe cat[idCategoria] per select JQuery categoria
                    echo "
            <tr class='cat".$outIscritti["id_categoria"]." record'>
                <td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["utente_nome"]."</td>
                <td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["utente_cognome"]."</td>
                <td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["societa_nome"]."</td>
                <td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["categoria_nome"]."</td>
                <td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["n_pettorina"]."</td>
                ";

                    if(is_null($outIscritti["punteggio"])){
                        echo "<td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'> - </td>";
                    }else{
                        echo "<td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["punteggio"]."</td>";
                    }
                    echo "
                <td><button onclick='rimuoviIscritto(\"".$outIscritti["utente_nome"]."\",".$garaID.", ".$outIscritti["id_utente"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                ";

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
        echo "Error!";  //#TODO Error page
    }
?>
