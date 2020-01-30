<?php
/**Riceve in POST idGara. Stampa su classificaSocieta.php la classifica relativa alla gara selezionata*/
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
            <th>Nome Societa</th>
        </tr>";

    //Query
    if($garID=="zero")  //nessuna gara in particolare selezionata
        $selectQuery  = "SELECT *, SUM(classifica.punteggio) AS punteggioTotale, societa.nome AS societa_nome FROM utente, classifica, societa WHERE utente.id_societa=societa.ID AND classifica.id_utente=utente.ID AND utente.ID IN (SELECT utente.ID FROM utente,classifica WHERE utente.ID=classifica.id_utente AND classifica.punteggio>0 GROUP BY utente.ID HAVING COUNT(classifica.id_utente)>=3) GROUP BY societa.ID ORDER BY punteggioTotale DESC;";
    else
        $selectQuery  = " SELECT *, SUM(classifica.punteggio) AS punteggioTotale, societa.nome AS societa_nome FROM utente, classifica, societa WHERE utente.id_societa=societa.ID AND classifica.id_utente=utente.ID AND classifica.id_gara=".$garID." GROUP BY societa.ID ORDER BY punteggioTotale DESC;";

    $risultatoSelectQuery = $conn->query($selectQuery);
    $posizione = 1;
    while($outSocieta = $risultatoSelectQuery->fetch_assoc()){
        echo "
        <tr>
            <td class='riga'>" . $posizione . "</td>
            <td class='riga'>" . $outSocieta["punteggioTotale"] . "</td>
            <td class='riga'>" . $outSocieta["societa_nome"] . "</td>
            ";
        echo "</tr>";
        $posizione++;
    }
    echo "</table>";
    echo "</div>";
}
?>
