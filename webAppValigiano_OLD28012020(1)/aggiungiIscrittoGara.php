<?php
session_start();
require_once("DBconfig.php");
require_once("function.php");

if(isset($_GET["garaID"])) {
    $garaID = $_GET["garaID"];  //id gara selezionata

?>

<html>

<head>
    <title>Valigiano Web Application</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="w3.css">
    <script src="jquery-3.4.1.min.js"></script>
    <script src="sorttable.js"></script>
    <script>

        /*Riceve in input dati utente e inserisce iscritto in Classifica*/
        function addSubscriber(idUtente, idGara){
            if(idUtente, idGara){
                $.post("addSubscriber.php",
                    {
                        userID: idUtente,
                        raceID: idGara
                    },
                    function(data, status){
                        $("#outputJQ").html(data);
                    });
            }
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
<a href="profiloGara.php?garaID=<?php echo"$garaID";?>" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="round_keyboard_backspace_black_18dp.png"></i>Torna alla lista iscritti</button></a>
<div class="w3-card w3-margin w3-round">
    <?php
    $queryTitolo = "SELECT * FROM gara WHERE ID=".$garaID.";";
    $risQueryTitolo = $conn->query($queryTitolo);
    if($outTitolo = $risQueryTitolo->fetch_assoc())
        echo "<h2 class='w3-margin w3-container' style='height: 8%;'><b>EVENTO:</b> ".$outTitolo["luogo"]."  ".$outTitolo["data"]." - <u>Aggiunta iscritti</u></h2>";
    else
        echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  #TODO error page
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
<hr style="margin:auto; margin-top: 2%; margin-bottom: 3%; width: 95%;">

<div id="outputJQ"><!--/Output JQUERY.../-->

    <div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
        <table align="center" style="width: 90%;" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border sortable">
            <tr class="w3-green">
                <th>Nome</th>
                <th>Cognome</th>
                <th>Societ&agrave</th>
                <th>Categoria</th>
                <th>Pettorina</th>
            </tr>
            <?php
            $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY utente.id_categoria DESC, utente.nome DESC, utente.cognome DESC, utente.id_societa DESC;";
            $ris = $conn->query($query);

            /**RICERCO UTENTI GIA ISCRITTI ALLA GARA, CHE NON STAMPO (controllo con funzione isRegistered())*/
            $iscritti = array();
            $queryCercaIscritti = "SELECT id_utente, id_gara FROM classifica WHERE id_gara = ".$garaID.";";
            $risAlreadySubscribed = $conn->query($queryCercaIscritti);
            while($outAlreadySubscribed = $risAlreadySubscribed->fetch_assoc()){
                array_push($iscritti,$outAlreadySubscribed["id_utente"]);   //inserisco nell'array gli ID di quelli gia iscritti
            }
            /**....*/

            while($outUtenti = $ris->fetch_assoc()){
                if(!isRegistered($iscritti, $outUtenti["userID"])) {    //Stampo solo se l'utente non è già iscritto
                    //Si usa classe cat[idCategoria] per select JQuery categoria
                    echo "
                        <tr class='cat" . $outUtenti["id_categoria"] . " record'>
                            <td class='riga'>" . $outUtenti["nome_utente"] . "</td>
                            <td class='riga'>" . $outUtenti["cognome_utente"] . "</td>
                            <td class='riga'>" . $outUtenti["nome_societa"] . "</td>
                            <td class='riga'>" . $outUtenti["nome_categoria"] . "</td>
                            <td class='riga'>" . $outUtenti["pettorina_utente"] . "</td>
                            ";
                                echo "
                            <td><button onclick='addSubscriber(" . $outUtenti["userID"] . ", " . $garaID . ");' class=\"w3-btn w3-ripple\"><img src='outline_add_black_18dp.png'></button></td>
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
        echo "Error!";  //#TODO Error page
    }
?>