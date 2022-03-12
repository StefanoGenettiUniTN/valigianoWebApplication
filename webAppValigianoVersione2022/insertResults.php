<?php

/**Calcolo e assegnamento punti

    $_POST["catID"])    --> categoria selezionata
    $_POST["raceID"])   --> gara selezionata
    $_POST["resultsArray"] --> array contenente i numeri di pettorina in ordine di arrivo


    FORMULA USATA:
 In base al regolamento per la versione del campionato del 2022 si calcolano i punteggi come segue:
 * C'è un totale a scalare che dipende dal numero di partecipanti alla gara e INDIPENDENTE dalla categoria.
 * Da 1 a 10 partecipanti --> si parte da 15 punti (il primo prende 15, il secondo 14, ecc...)
 * Da 11 a 20 partecipanti --> si parte da 25 punti
 * Da 21 a 30 partecipanti --> si parte da 35 punti
 * Oltre i 30 partecipanti --> si parte da 45 punti
 *
 * Nota: si sceglie di lasciare nel DB l'informazione "tetto" per ogni categoria per non complicarmi troppo la vita.
 * Alla segreteria viene detto di assegnare al valore di tetto un valore a caso che tanto non viene considerato.
 * Una volta infatti ogni categoria aveva il suo punteggio di partenza dal quale scalare.
 *
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

    //Imposta il totale di partenza in base al numero di partecipanti
    if($totPartecipanti>=1 && $totPartecipanti<=10){        //Da 1 a 10 partecipanti --> si parte da 15 punti
        $totDiPartenza=15;
    }elseif($totPartecipanti>=11 && $totPartecipanti<=20){  //Da 11 a 20 partecipanti --> si parte da 25 punti
        $totDiPartenza=25;
    }elseif($totPartecipanti>=21 && $totPartecipanti<=30){  //Da 21 a 30 partecipanti --> si parte da 35 punti
        $totDiPartenza=35;
    }elseif($totPartecipanti>30){                           //Oltre i 30 partecipanti --> si parte da 45 punti
        $totDiPartenza=45;
    }else{  //Non so se funziona, tanto non dovrebbe mai accadere
        echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>"; //TODO Error page
        header("location: errorPage.php");
    }

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
