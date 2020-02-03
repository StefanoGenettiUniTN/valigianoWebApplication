<?php
/**Riceve in POST idGara. Stampa su classificaFamiglia.php la classifica relativa alla gara selezionata*/
session_start();
require_once("DBconfig.php");
require_once("function.php");
?>

<?php

if(isset($_POST["garID"])){
    $garID = $_POST["garID"];

    /**Aggiorno pagina*/

    /*TABELLA*/
    echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
        <table id=\"TabellaRis\" align=\"center\" style=\"width: 90%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border\">
            <tr class=\"w3-green\">
                <th>Posizione</th>
                <th>Punteggio Totale</th>
                <th>Nome Famiglia</th>
            </tr>";

    //Query
    if($garID=="zero")  //nessuna gara in particolare selezionata
        $selectQuery  = "SELECT *,famiglia.nome AS famiglia_nome, SUM(punteggio) AS punteggioTotale FROM famiglia, classifica, relazioneFamigliare, utente WHERE relazioneFamigliare.id_utente = utente.ID AND relazioneFamigliare.id_famiglia=famiglia.ID AND classifica.id_utente=utente.ID GROUP BY famiglia.ID ORDER BY punteggioTotale DESC;";
    else
        $selectQuery  = "SELECT *,famiglia.nome AS famiglia_nome, SUM(punteggio) AS punteggioTotale FROM famiglia, classifica, relazioneFamigliare, utente WHERE relazioneFamigliare.id_utente = utente.ID AND relazioneFamigliare.id_famiglia=famiglia.ID AND classifica.id_utente=utente.ID AND classifica.id_gara=".$garID." GROUP BY famiglia.ID ORDER BY punteggioTotale DESC;";

    $risultatoSelectQuery = $conn->query($selectQuery);
    $posizione = 1;
    while($outFamiglia = $risultatoSelectQuery->fetch_assoc()){
        echo "
        <tr>
            <td class='riga'>" . $posizione . "</td>
            <td class='riga'>" . $outFamiglia["punteggioTotale"] . "</td>
            <td class='riga'>" . $outFamiglia["famiglia_nome"] . "</td>
            ";
        echo "</tr>";
        $posizione++;
    }
    echo "</table>";
    echo "</div>";
}
?>
