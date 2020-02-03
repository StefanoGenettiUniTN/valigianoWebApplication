<?php
/**Riceve in POST idGara e idCategoria. Stampa su aggiungiRisultati.php la classifica relativa alla gara selezionata*/

session_start();
require_once("DBconfig.php");
require_once("function.php");
?>

<?php

if(isset($_POST["catID"]) && isset($_POST["raceID"])){
    $catID = $_POST["catID"];
    $garaID = $_POST["raceID"];

    //Query
    $selectQuery  = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.id_gara=".$garaID." AND categoria.ID = ".$catID." ORDER BY classifica.punteggio DESC, classifica.posClassifica ASC, utente.data_nascita ASC, utente.nome ASC, utente.cognome ASC;";
    $risultato = $conn->query($selectQuery);

    /**Aggiorno pagina*/
    echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
        <table id=\"TabellaRis\" align=\"center\" style=\"width: 90%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border sortable\">
            <tr class=\"w3-green\">
                <th>Punteggio</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Societ&agrave</th>
                <th>Categoria</th>
                <th>Pettorina</th>
            </tr>";

    while($outUtenti = $risultato->fetch_assoc()){
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

    /**Aggiorno libreria sorttable*/
    echo "<script>refreshSortable();</script>";
}
?>
