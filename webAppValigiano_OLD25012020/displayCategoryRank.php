<?php
/**Riceve in POST idCategoria. Stampa su classificaTotale.php la classifica relativa alla categoria selezionata*/

session_start();
require_once("DBconfig.php");
require_once("function.php");
?>

<?php

if(isset($_POST["catID"])){
    $catID = $_POST["catID"];

    /**Aggiorno pagina*/
    echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
            <table id=\"TabellaRis\" align=\"center\" style=\"width: 90%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border\">
                <tr class=\"w3-green\">
                    <th>Posizione</th>
                    <th>Punteggio totale</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Societ&agrave</th>
                    <th>Categoria</th>
                    <th>Pettorina</th>
                </tr>";

    //Query
    if($catID == "zero")    //nessuna categoria selezionata
        $selectQuery  = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome, SUM(classifica.punteggio) AS punteggioTotale, COUNT(classifica.id_utente) AS garePartecipate FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID HAVING garePartecipate>=3 ORDER BY categoria.nome ASC, punteggioTotale DESC, utente.data_nascita ASC;";
    else
        $selectQuery  = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome, SUM(classifica.punteggio) AS punteggioTotale, COUNT(classifica.id_utente) AS garePartecipate FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND categoria.ID = ".$catID." GROUP BY utente.ID HAVING garePartecipate>=3 ORDER BY categoria.nome ASC, punteggioTotale DESC, utente.data_nascita ASC;";

    $risultatoSelectQuery = $conn->query($selectQuery);
    $posizione = 1;
    $categoriaCorrente = "nessuna"; //tiene traccia della categoria che si sta stampando per stampare correttamente le posizioni (quando cambia categoria $posizione=1)
    while($outUtenti = $risultatoSelectQuery->fetch_assoc()){
        if($categoriaCorrente != $outUtenti["categoria_nome"]){ //#TODO selezione per idCategoria
            $posizione=1;
            $categoriaCorrente = $outUtenti["categoria_nome"];
        }
        echo "
                    <tr class='cat" . $outUtenti["id_categoria"] . " record'>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $posizione . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["punteggioTotale"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["utente_nome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["utente_cognome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["societa_nome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["categoria_nome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=".$outUtenti["id_utente"]."' onclick='infoUser(".$outUtenti["id_utente"].");'>" . $outUtenti["n_pettorina"] . "</td>
                        ";
        echo "</tr>";
        $posizione++;
    }
    echo "</table>";
    echo "</div>";
}
?>
