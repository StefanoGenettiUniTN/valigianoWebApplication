<?php

/**Da profiloGara.php faccio nuovamente query per filtrare categoria*/

session_start();
require_once("DBconfig.php");
?>


<?php
if(isset($_GET["catID"]) && isset($_GET["raceID"])){
    ?>

    <?php

    $catID = $_GET["catID"];
    $garaID = $_GET["raceID"];

    /**Aggiorno pagina*/
    echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
            <table align=\"center\" style=\"width: 90%;\" id='myTable' class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border sortable\">
                <tr class=\"w3-green\">
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Societ&agrave</th>
                    <th>Categoria</th>
                    <th>Pettorina</th>
                    <th>Punteggio</th>
                </tr>";

    if($catID=="all")   //nessuna categoria in particolare selezionata
        $query = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.id_gara=".$garaID." ORDER BY categoria.nome ASC, punteggio DESC, classifica.posClassifica ASC, utente.data_nascita ASC, utente.nome ASC, utente.cognome ASC;";
    else
        $query = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.id_gara=".$garaID." AND utente.id_categoria=".$catID." ORDER BY categoria.nome ASC, punteggio DESC, classifica.posClassifica ASC, utente.data_nascita ASC, utente.nome ASC, utente.cognome ASC;";
    $ris = $conn->query($query);

    while($outIscritti = $ris->fetch_assoc()){
        //Si usa classe cat[idCategoria] per select JQuery categoria
        echo "
            <tr class='cat".$outIscritti["id_categoria"]." record'>
                <td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["utente_nome"]."</td>
                <td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["utente_cognome"]."</td>
                <td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["societa_nome"]."</td>
                <td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["categoria_nome"]."</td>
                <td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["n_pettorina"]."</td>
                ";

        if(is_null($outIscritti["punteggio"])){
            echo "<td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'> - </td>";
        }else{
            echo "<td class='riga' href='profiloUtente.php?userID=".$outIscritti["id_utente"]."' onclick='infoUser(".$outIscritti["id_utente"].");'>".$outIscritti["punteggio"]."</td>";
        }
        /**Rimosso 27 gennaio 2020 dopo aver scoperto che gli utenti sono da subito iscritti a tutte le gare*/
        /*
        echo "
                <td><button onclick='rimuoviIscritto(\"".$outIscritti["utente_nome"]."\",".$garaID.", ".$outIscritti["id_utente"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                ";
        */

        echo "</tr>";
    }

    echo "
        </table>
        </div>
    ";

    /**Aggiorno libreria sorttable*/
    echo "<script>refreshSortable();</script>";

?>

<?php
}else{
    echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  //#TODO Error page
    //header("location: errorPage.php");
}
?>