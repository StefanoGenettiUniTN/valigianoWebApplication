<?php
/**Rimozione famigliare passato con GET dalla famiglia passata con GET*/
session_start();
require_once("DBconfig.php");

if(isset($_GET["famID"]) && isset($_GET["userID"])) {
?>
    <?php

    $idUtente = $_GET["userID"];
    $famID = $_GET["famID"];

    /**Cancello dalla tabella RelazioneFamigliare il record corrispondete a famID e IDUtente selezionati*/
    $deleteQuery = "DELETE FROM relazionefamigliare WHERE id_utente = ".$idUtente." AND id_famiglia=".$famID.";";
    $risultato = $conn->query($deleteQuery);
    /**.....*/

    /**Aggiorno pagina*/
    echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
            <table align=\"center\" style=\"width: 90%;\" id=\"myTable\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border sortable\">
                <tr class=\"w3-green\">
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Societ&agrave</th>
                    <th>Categoria</th>
                    <th>Pettorina</th>
                </tr>";

    $query = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM utente, categoria, societa, relazionefamigliare WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND relazionefamigliare.id_utente = utente.ID AND relazionefamigliare.id_famiglia=".$famID." ORDER BY utente.nome, utente.cognome;";
    $ris = $conn->query($query);

    while($outParenti = $ris->fetch_assoc()){
        //Si usa classe cat[idCategoria] per select JQuery categoria
        echo "
                <tr class='cat".$outParenti["id_categoria"]." record'>
                    <td class='riga' href='profiloUtente.php?userID=".$outParenti["id_utente"]."' onclick='infoUser(".$outParenti["id_utente"].");'>".$outParenti["utente_nome"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outParenti["id_utente"]."' onclick='infoUser(".$outParenti["id_utente"].");'>".$outParenti["utente_cognome"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outParenti["id_utente"]."' onclick='infoUser(".$outParenti["id_utente"].");'>".$outParenti["societa_nome"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outParenti["id_utente"]."' onclick='infoUser(".$outParenti["id_utente"].");'>".$outParenti["categoria_nome"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outParenti["id_utente"]."' onclick='infoUser(".$outParenti["id_utente"].");'>".$outParenti["n_pettorina"]."</td>
                    ";
        echo "<td><button onclick='rimuoviParente(\"".$outParenti["utente_nome"]."\",".$famID.", ".$outParenti["id_utente"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>";
        echo "</tr>";
    }

    echo "
        </table>
        </div>
    ";

    echo "<script>refreshSortable();</script>";
?>

    <?php
}else{
    echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  //#TODO Error page
}
?>