<?php

/**Assegnamento di 1 punto agli atleti che si sono ritirati durante la gara

$_POST["catID"])    --> categoria selezionata
$_POST["raceID"])   --> gara selezionata
$_POST["resultsArray"] --> array contenente i numeri di pettorina degli atleti ritirati

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

    $posInClassifica = 100; //In questa verisone del campionato la posizione non compare in nessuna formula.
                            //Nella versione di 2 anni fa invece a parita di punteggio si ordinava in base alla posizione
                            //media nelle gare.
                            //As a consequence per semplicità agli atleti ritirati metto semplicemente punteggio 1 e
                            //posizione 100.

    foreach ($arrayRisultati as $pettorina){
        $punteggio = 1;
        /**Query di aggiornamento*/
        $updateQuery  = "UPDATE classifica INNER JOIN utente ON utente.ID = classifica.id_utente SET classifica.punteggio=".$punteggio.", classifica.posClassifica=".$posInClassifica." WHERE utente.n_pettorina=".$pettorina." AND classifica.id_gara=".$garaID.";";
        $risultato = $conn->query($updateQuery);
    }

    /**Aggiorno pagina*/
    //Query
    $selectQuery  = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.id_gara=".$garaID." AND categoria.ID = ".$catID." ORDER BY classifica.punteggio DESC, classifica.posClassifica ASC, utente.data_nascita ASC, utente.nome ASC, utente.cognome ASC;";
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
        /**Aggiorno la pagina senza eseguire Query attribuzione punteggi*/

        /**Aggiorno pagina*/

        $catID = $_POST["catID"];
        $garaID = $_POST["raceID"];

        //Query
        $selectQuery = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.id_gara=" . $garaID . " AND categoria.ID = " . $catID . " ORDER BY classifica.punteggio DESC, classifica.posClassifica ASC, utente.data_nascita ASC, utente.nome ASC, utente.cognome ASC;";
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
        header("location: errorPage.php");
    }
}
?>
