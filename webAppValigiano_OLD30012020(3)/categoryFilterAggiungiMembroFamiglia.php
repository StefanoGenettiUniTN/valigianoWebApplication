<?php

/**Da aggiungiMembroFamiglia.php faccio nuovamente query per filtrare categoria*/

session_start();
require_once("DBconfig.php");
require_once("function.php");
?>


<?php
if(isset($_GET["catID"]) && isset($_GET["famID"])){
    ?>

    <?php

    $catID = $_GET["catID"];
    $famID = $_GET["famID"];

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
    if($catID == "all") //nessuna categoria selezionata
        $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, societa.nome AS nome_societa, societa.ID FROM societa, categoria, utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID ORDER BY utente.id_categoria DESC, utente.nome DESC, utente.cognome DESC, utente.id_societa DESC;";
    else
        $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, societa.nome AS nome_societa, societa.ID FROM societa, categoria, utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND categoria.ID=".$catID." ORDER BY utente.id_categoria DESC, utente.nome DESC, utente.cognome DESC, utente.id_societa DESC;";

    $ris = $conn->query($query);

    /**RICERCO UTENTI GIA ISCRITTI ALLA FAMIGLIA, CHE NON STAMPO (controllo con funzione isRelative())*/
    $parenti = array();
    $queryCercaParenti = "SELECT id_utente, id_famiglia FROM relazionefamigliare WHERE id_famiglia = ".$famID.";";
    $risAlreadySubscribed = $conn->query($queryCercaParenti);
    while($outAlreadySubscribed = $risAlreadySubscribed->fetch_assoc()){
        array_push($parenti,$outAlreadySubscribed["id_utente"]);   //inserisco nell'array gli ID di quelli gia iscritti
    }
    /**....*/

    while($outUtenti = $ris->fetch_assoc()){
        if(!isRelative($parenti, $outUtenti["userID"])) {    //Stampo solo se l'utente non è già iscritto
            //Si usa classe cat[idCategoria] per select JQuery categoria
            echo "
                        <tr class='cat" . $outUtenti["id_categoria"] . " record'>
                            <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>" . $outUtenti["nome_utente"] . "</td>
                            <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>" . $outUtenti["cognome_utente"] . "</td>
                            <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>" . $outUtenti["nome_societa"] . "</td>
                            <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>" . $outUtenti["nome_categoria"] . "</td>
                            <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>" . $outUtenti["pettorina_utente"] . "</td>
                            ";
            echo "
                            <td><button onclick='addRelative(" . $outUtenti["userID"] . ", " . $famID . ");' class=\"w3-btn w3-ripple\"><img src='outline_add_black_18dp.png'></button></td>
                            ";

            echo "</tr>";
        }
    }
    echo "</table>";
    echo "</div>";

    /**Aggiorno libreria sorttable*/
    echo "<script>refreshSortable();</script>";
?>

<?php
}else{
    echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  //#TODO Error page
    header("location: errorPage.php");
}
?>