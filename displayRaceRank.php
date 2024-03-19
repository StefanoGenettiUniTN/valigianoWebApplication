<?php
/**Riceve in POST idGara. Stampa su classificaSingolaGara.php la classifica relativa alla gara selezionata*/
/**NB: Ristampa anche select categoria*/

session_start();
require_once("DBconfig.php");
require_once("function.php");
?>

<?php

if(isset($_POST["garID"])){
    $garID = $_POST["garID"];

    /**Aggiorno pagina*/

    /*SELECT CATEGORIA*/
    echo "<h5 class=\"w3-margin-left w3-padding\">Specifichi la <b>cetegoria</b> da stampare</h5>
    <select id=\"catFilter\" class='w3-select w3-border w3-round w3-margin-left' name=\"filtroCategoria\" style=\"width: 90%;\" onchange='filtroCategoria(this.value);'>
        <option value=\"zero\" selected>Nessuna categoria selezionata</option>";
    /*RICERCA CATEGORIA PER SELECT*/
    $queryCategoria = "SELECT id, nome FROM categoria;";
    $risCategoria = $conn->query($queryCategoria);
    while($categoria = $risCategoria->fetch_assoc()) {
        echo "<option value='".$categoria["id"]."'>".$categoria["nome"]."</option>";
    }
    echo "</select>";

    if($garID!="zero") { //è stata selezionata una gara, allora stampo
        echo "<button class=\"w3-button w3-margin-top w3-margin-left w3-round-large w3-centered w3-deep-orange\" style=\"width: 20%;\" onclick=\"printJS({ printable: 'TabellaRis', type: 'html', header: 'Classifica gara: <b>".getRaceName($garID)."</b>', headerStyle: 'font-size: 15;', style: 'table, th, td {border: 1px solid black;} table {border-collapse: collapse;} th, td {text-align: center;}'});\">STAMPA</button><br>";
        echo "<hr style=\"margin:auto; margin-top: 2%; margin-bottom: 3%; width: 95%;\">";
        /*............*/

        /*TABELLA*/
        echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
            <table id=\"TabellaRis\" align=\"center\" style=\"width: 90%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border\">
                <tr class=\"w3-green\">
                    <th>Posizione</th>
                    <th>Punteggio</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Societ&agrave</th>
                    <th>Categoria</th>
                    <th>Pettorina</th>
                </tr>";

        //Query
        $selectQuery = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM classifica, utente, categoria, societa, gara WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.id_gara = gara.ID AND gara.ID = " . $garID . " AND classifica.punteggio>0 ORDER BY categoria.nome ASC, punteggio DESC, classifica.posClassifica ASC, utente.data_nascita ASC;";

        $risultatoSelectQuery = $conn->query($selectQuery);
        $posizione = 1;
        $categoriaCorrente = "nessuna"; //tiene traccia della categoria che si sta stampando per stampare correttamente le posizioni (quando cambia categoria $posizione=1)
        while ($outUtenti = $risultatoSelectQuery->fetch_assoc()) {
            if ($categoriaCorrente != $outUtenti["categoria_nome"]) { //#TODO selezione per idCategoria
                $posizione = 1;
                $categoriaCorrente = $outUtenti["categoria_nome"];
            }
            echo "
                    <tr class='cat" . $outUtenti["id_categoria"] . " record'>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["id_utente"] . "' onclick='infoUser(" . $outUtenti["id_utente"] . ");'>" . $posizione . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["id_utente"] . "' onclick='infoUser(" . $outUtenti["id_utente"] . ");'>" . $outUtenti["punteggio"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["id_utente"] . "' onclick='infoUser(" . $outUtenti["id_utente"] . ");'>" . $outUtenti["utente_nome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["id_utente"] . "' onclick='infoUser(" . $outUtenti["id_utente"] . ");'>" . $outUtenti["utente_cognome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["id_utente"] . "' onclick='infoUser(" . $outUtenti["id_utente"] . ");'>" . $outUtenti["societa_nome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["id_utente"] . "' onclick='infoUser(" . $outUtenti["id_utente"] . ");'>" . $outUtenti["categoria_nome"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["id_utente"] . "' onclick='infoUser(" . $outUtenti["id_utente"] . ");'>" . $outUtenti["n_pettorina"] . "</td>
                        ";
            echo "</tr>";
            $posizione++;
        }
        echo "</table>";
        echo "</div>";
    }else{  //nessuna gara selezionata --> NON STAMPO NIENTE (evito problemi con btnStampa)
        echo "<hr style=\"margin:auto; margin-top: 2%; margin-bottom: 3%; width: 95%;\">";
    }
}
?>
