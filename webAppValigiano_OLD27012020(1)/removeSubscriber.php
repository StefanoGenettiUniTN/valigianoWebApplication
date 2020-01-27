<?php
/**Rimozione iscritto passato con GET dalla gara passata con GET*/
session_start();
require_once("DBconfig.php");

if(isset($_GET["raceID"]) && isset($_GET["userID"])) {
?>


<?php

$idUtente = $_GET["userID"];
$garaID = $_GET["raceID"];

/**Cancello dalla tabella Classifica il record corrispondete a IDGara e IDUtente selezionati (effetto collaterale: perdo punteggio nel caso la gara fosse già stata fatta)*/
$deleteQuery = "DELETE FROM classifica WHERE id_utente = ".$idUtente." AND id_gara=".$garaID.";";
$risultato = $conn->query($deleteQuery);
/**.....*/

/**Aggiorno pagina*/
echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
            <table align=\"center\" style=\"width: 90%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border sortable\">
                <tr class=\"w3-green\">
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Societ&agrave</th>
                    <th>Categoria</th>
                    <th>Pettorina</th>
                    <th>Punteggio</th>
                </tr>";

    $query = "SELECT *, utente.nome AS utente_nome, utente.cognome AS utente_cognome, societa.nome AS societa_nome, categoria.nome AS categoria_nome FROM classifica, utente, categoria, societa WHERE classifica.id_utente = utente.ID AND utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND classifica.id_gara=".$garaID." ORDER BY categoria.nome ASC, utente.nome ASC, utente.cognome ASC;";
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
        echo "
                <td><button onclick='rimuoviIscritto(\"".$outIscritti["utente_nome"]."\",".$garaID.", ".$outIscritti["id_utente"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                ";

        echo "</tr>";
    }

echo "
        </table>
        </div>
    ";
?>

<?php
    }else{
        echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  //#TODO Error page
    }
?>