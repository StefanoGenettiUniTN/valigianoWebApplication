<?php

/**Calcolo e assegnamento punti

    $_POST["catID"])    --> categoria selezionata
    $_POST["raceID"])   --> gara selezionata
    $_POST["resultsArray"] --> array contenente i numeri di pettorina in ordine di arrivo

 * Nota: come 2 anni fa i minicuccioli maschi e femmine prendono 1 punto tutti indipendentemente dalla posizione
 * in classifica
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

    //Aggiornamento 29 maggio 2022:
    /*
     * Il totale di partenza all'inizio di questo campionato sembrava dipendere dal numero di partecipanti alla gara.
     * Invece per calcolare il totale di partenza bisogna basarsi sul numero di iscritti alla prima gara.
     * Per vedere versione precedente, vedi commit prima di questa data in Valligiano versione 2022.
     * Per velocizzare l'aggiornamento uso di nuovo il buon vecchio getTotPartenza e inserisco a mano i valori dai quali scalare
    */
    $totDiPartenza = getTotPartenza($catID);    //Si parte da un punteggio di partenza salvato nella tabella categoria (es. 50) e lo si assegna a scalare ad ogni atleta

    foreach ($arrayRisultati as $pettorina){
        if(getCategoryName($catID)=="01-Minicuccioli-M" || getCategoryName($catID)=="02-Minicuccioli-F"){   //personalizzazione per categoria minicuccioli (sempre 1 punto)
            $punteggio = 1;
        }else{
            if($totDiPartenza>=2) {  //totDiPartenza ancora positivo allora no problem
                $punteggio = $totDiPartenza;
                $totDiPartenza--;
            }else{  //se totDiPartenza diventa negativo --> $punteggio = 2
                $punteggio=2;
            }
        }

        /**Query di aggiornamento*/
        $updateQuery  = "UPDATE classifica INNER JOIN utente ON utente.ID = classifica.id_utente SET classifica.punteggio=".$punteggio.", classifica.posClassifica=".$posInClassifica." WHERE utente.n_pettorina=".$pettorina." AND classifica.id_gara=".$garaID.";";
        $risultato = $conn->query($updateQuery);

        $posInClassifica++;
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
                    <td class='riga pettorina'>" . $outUtenti["n_pettorina"] . "</td>
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
