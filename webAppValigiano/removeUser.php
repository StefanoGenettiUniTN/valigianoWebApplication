<?php
    session_start();
    require_once("DBconfig.php");
?>


<?php

    $idUtente = $_GET["userID"];

    /**Cancello dalla tabella Utenti l'utente in questione*/
    $deleteQuery = "DELETE FROM utente WHERE utente.ID = ".$idUtente.";";
    $risultato = $conn->query($deleteQuery);
    /**.....*/

    /**Aggiorno pagina*/
    echo "<div class='w3-responsive'>
    <table align='center' style='width: 90%;' class='w3-table w3-striped w3-centered w3-large w3-hoverable w3-border'>
        <tr class='w3-green'>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Sesso</th>
            <th>Data di nascita</th>
            <th>Societ&agrave</th>
            <th>Categoria</th>
            <th>Pettorina</th>
            <th>Punteggio</th>
        </tr>";
        $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID;";

        $ris = $conn->query($query);

        while($outUtenti = $ris->fetch_assoc()){
            echo "
                <tr>
                    <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>".$outUtenti["nome_utente"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>".$outUtenti["cognome_utente"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>".$outUtenti["sesso_utente"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>".$outUtenti["data_nascita_utente"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>".$outUtenti["nome_societa"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>".$outUtenti["nome_categoria"]."</td>
                    <td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>".$outUtenti["pettorina_utente"]."</td>
                    ";

                    if(is_null($outUtenti["punteggio"])){
                        echo "<td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'> - </td>";
                    }else{
                        echo "<td class='riga' href='profiloUtente.php?userID=".$outUtenti["userID"]."' onclick='infoUser(".$outUtenti["userID"].");'>".$outUtenti["punteggio"]."</td>";
                    }
                echo "
                    <td><button class=\"w3-btn w3-ripple\"><img src='round_create_black_18dp.png'></button></td>
                    <td><button onclick='rimuoviUtente(\"".$outUtenti["nome_utente"]."\",".$outUtenti["userID"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                    ";
                echo "</tr>";
        }
    echo "</table>";
    echo "</div>";
?>
