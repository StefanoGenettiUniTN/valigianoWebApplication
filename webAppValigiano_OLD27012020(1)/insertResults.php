<?php

/**Calcolo e assegnamento punti

    $_POST["catID"])    --> categoria selezionata
    $_POST["raceID"])   --> gara selezionata
    $_POST["resultsArray"] --> array contenente i numeri di pettorina in ordine di arrivo


    FORMULA USATA:
 *
 * [(totPartecipanti - PosInClassifica)/totPartecipanti]*100
 *
 * NOTA: viene fatto *100 per evitare approssimazioni


 */

session_start();
require_once("DBconfig.php");
require_once("function.php");
?>

<?php

if(isset($_POST["catID"]) && isset($_POST["raceID"]) && isset($_POST["resultsArray"])) {

    $catID = $_POST["catID"];
    $garaID = $_POST["raceID"];
    $arrayRisultati = $_POST['resultsArray'];

    $totPartecipanti = count($arrayRisultati);

    $posInClassifica = 1;

    foreach ($arrayRisultati as $pettorina){
        $punteggio = (($totPartecipanti-$posInClassifica)/$totPartecipanti)*100;    //punteggio da assegnare secondo formula
        /**Query di aggiornamento*/
        $updateQuery  = "UPDATE classifica INNER JOIN utente ON utente.ID = classifica.id_utente SET classifica.punteggio=".$punteggio." WHERE utente.n_pettorina=".$pettorina." AND classifica.id_gara=".$garaID.";";
        $risultato = $conn->query($updateQuery);

        $posInClassifica++;
    }

    /**Aggiorno pagina*/
    //Query
    $selectQuery  = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.id_gara=".$garaID." AND categoria.ID = ".$catID." ORDER BY classifica.punteggio DESC, utente.nome ASC, utente.cognome ASC;";
    $risultatoSelectQuery = $conn->query($selectQuery);

    echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
        <table id=\"TabellaRis\" align=\"center\" style=\"width: 90%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border\">
            <tr class=\"w3-green\">
                <th>Punteggio</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Societ&agrave</th>
                <th>Categoria</th>
                <th>Pettorina</th>
            </tr>";

    while($outUtenti = $risultatoSelectQuery->fetch_assoc()){
        echo "
                <tr class='cat" . $outUtenti["id_categoria"] . " record'>
                    <td class='riga'>" . $outUtenti["punteggio"] . "</td>
                    <td class='riga'>" . $outUtenti["utente_nome"] . "</td>
                    <td class='riga'>" . $outUtenti["utente_cognome"] . "</td>
                    <td class='riga'>" . $outUtenti["societa_nome"] . "</td>
                    <td class='riga'>" . $outUtenti["categoria_nome"] . "</td>
                    <td class='riga'>" . $outUtenti["n_pettorina"] . "</td>
                    ";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
}else{

    if(isset($_POST["catID"]) && isset($_POST["raceID"])) { //array vuoto
        /**Nel caso l'array sia vuoto*/
        /**Aggiorno la pagina senza eseguire Query*/

        /**Aggiorno pagina*/

        $catID = $_POST["catID"];
        $garaID = $_POST["raceID"];

        //Query
        $selectQuery = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.id_gara=" . $garaID . " AND categoria.ID = " . $catID . " ORDER BY classifica.punteggio DESC, utente.nome ASC, utente.cognome ASC;";
        $risultatoSelectQuery = $conn->query($selectQuery);

        echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
        <table id=\"TabellaRis\" align=\"center\" style=\"width: 90%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border\">
            <tr class=\"w3-green\">
                <th>Punteggio</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Societ&agrave</th>
                <th>Categoria</th>
                <th>Pettorina</th>
            </tr>";

        while ($outUtenti = $risultatoSelectQuery->fetch_assoc()) {
            echo "
                <tr class='cat" . $outUtenti["id_categoria"] . " record'>
                    <td class='riga'>" . $outUtenti["punteggio"] . "</td>
                    <td class='riga'>" . $outUtenti["utente_nome"] . "</td>
                    <td class='riga'>" . $outUtenti["utente_cognome"] . "</td>
                    <td class='riga'>" . $outUtenti["societa_nome"] . "</td>
                    <td class='riga'>" . $outUtenti["categoria_nome"] . "</td>
                    <td class='riga'>" . $outUtenti["n_pettorina"] . "</td>
                    ";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    }else{
        echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>"; //TODO Error page
    }
}
?>
