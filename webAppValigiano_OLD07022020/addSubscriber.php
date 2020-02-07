<?php
/**Aggiunta iscritto alla gara specificata con GET*/

session_start();
require_once("DBconfig.php");
require_once("function.php");
?>

<?php

if(isset($_POST["userID"]) && isset($_POST["raceID"])){
    $userID = $_POST["userID"];
    $garaID = $_POST["raceID"];

    //Query
    $insertQuery  = "INSERT INTO classifica (id_utente, id_gara, punteggio) VALUES (".$userID.", ".$garaID.", 0);";
    $risultato = $conn->query($insertQuery);

    /**Aggiorno pagina*/
    echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
        <table align=\"center\" style=\"width: 90%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border sortable\">
            <tr class=\"w3-green\">
                <th>Nome</th>
                <th>Cognome</th>
                <th>Societ&agrave</th>
                <th>Categoria</th>
                <th>Pettorina</th>
            </tr>";
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
    echo "</table>";
    echo "</div>";
}
?>
