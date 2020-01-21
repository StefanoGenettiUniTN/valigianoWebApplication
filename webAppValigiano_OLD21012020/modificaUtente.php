<?php
    session_start();
    require_once("DBconfig.php");
?>

<html>

    <head>
        <title>Valigiano Web Application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="w3.css">
        <script src="jquery-3.4.1.min.js"></script>

        <script>
            /**Elimina utente*/
            function rimuoviUtente(nome, idUtente){
                if(confirm("L'utente "+nome+" sta per essere eliminato"))
                    $.ajax({
                        url: 'removeUser.php?userID='+idUtente,  //idUtente contiene l'id dell'utente da rimuovere
                        success: function(data) {
                            $("#outputJQ").html(data);
                        }
                    });
            }

            /*Script per collegamento ipertestuale click sulla riga della tabella*/
            /*
            $(document).ready(function(){
                $('.riga').click(function(){
                    window.location = $(this).attr('href');
                    return false;
                });
            });
            */
            function infoUser(link) {
                window.location.href = "profiloUtente.php?userID="+link;
            }


        </script>

    </head>

<body>
    <a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
    <div class="w3-card w3-margin w3-round">
        <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 8%;">Pagina di modifica utente</h2>
    </div>

    <a href="aggiungiUtente.php" class="w3-ripple w3-teal w3-button w3-block w3-centered" style="margin: auto; width: 90%;" >AGGIUNGI UTENTE</a><br>

    <div id="outputJQ"><!--/Output JQUERY.../-->

    <div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
    <table align="center" style="width: 90%;" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border">
        <tr class="w3-green">
            <th>Nome</th>
            <th>Cognome</th>
            <th>Sesso</th>
            <th>Data di nascita</th>
            <th>Societ&agrave</th>
            <th>Categoria</th>
            <th>Pettorina</th>
            <th>Punteggio</th>
        </tr>
        <?php
        $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID;";
        //$queryUtenti = "SELECT * FROM utente LEFT OUTER JOIN categoria AS cat ON utente.id_categoria = cat.ID UNION SELECT * FROM utente LEFT OUTER JOIN classifica AS cla ON utente.ID = cla.ID;";
        //$queryPunteggio = "SELECT * FROM utente, classifica WHERE utente.ID = classifica.id_utente;";
        //$queryCategoria = "SELECT * FROM utente, categoria WHERE utente.id_categoria = categoria.ID;";

        $ris = $conn->query($query);
        //$risQ_Utenti = $conn->query($queryUtenti);
        //$risQ_Punteggio = $conn->query($queryPunteggio);
        //$risQ_Categoria = $conn->query($queryUtenti);

        while($outUtenti = $ris->fetch_assoc()){
            /*#TODO class="riga" tentativo originale...vedi script commentato in header*/
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
        ?>
    </table>
    </div>

    </div><!--/Output JQUERY.../-->
</body>

</html>