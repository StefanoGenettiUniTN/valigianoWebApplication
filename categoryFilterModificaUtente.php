<?php

    /**Da modificaUtente.php faccio nuovamente query per filtrare categoria*/

session_start();
require_once("DBconfig.php");
?>


<?php
if(isset($_GET["type"]) && isset($_GET["catID"])){
?>

    <?php

    $type = $_GET["type"];
    $catID = $_GET["catID"];

    /**
    type = 1 --> raggurppa per societa
    type = 2 --> raggruppa per categoria
     */

    /**ATTENZIONE --> rispetto a raggruppa.php i controlli su $type sono AL CONTRARIO, perchè dopo il filtro per categoria il bottone, e quindi il raggruppamento NON deve cambiare*/
    if($type=="2"){ //RAGGRUPPA PER SOCIETA

        echo "<button style='margin-right: 10%; margin-top: 2%; width: 20%;' class='w3-blue-gray w3-button w3-block w3-centered w3-margin w3-round-medium' onclick=\"raggruppa('2')\" id='btnRaggruppamento' value='2'>RAGGRUPPA PER CATEGORIA</button>
    <hr style='margin:auto; margin-top: 2%; width: 95%;'>
    <a href='aggiungiUtente.php' class='w3-ripple w3-teal w3-button w3-block w3-centered' style='margin:auto; margin-top: 3%; width: 90%;' >AGGIUNGI UTENTE</a><br>

    <div id='outputJQ'><!--/Output JQUERY.../-->

    <div class='w3-responsive'><!--Scroll bar se schermata troppo piccola-->
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
        if($catID=="all")   //se l'utente non ha selezionato nessuna categoria in particolare, stampa tutto
            $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY societa.nome ASC, utente.nome ASC, utente.cognome ASC, categoria.nome ASC;";
        else
            $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND utente.id_categoria = ".$catID." GROUP BY utente.ID ORDER BY societa.nome ASC, utente.nome ASC, utente.cognome ASC, categoria.nome ASC;";

        $ris = $conn->query($query);

        while($outUtenti = $ris->fetch_assoc()){
            //Si usa classe cat[idCategoria] per select JQuery categoria
            echo "
    <tr class='cat".$outUtenti["id_categoria"]." record'>
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
                    <td><button onclick='animazioneModificaUtente(".$outUtenti["userID"].");' class=\"w3-btn w3-ripple\"><img src='round_create_black_18dp.png'></button></td>
                    <td><button onclick='rimuoviUtente(\"".$outUtenti["nome_utente"]."\",".$outUtenti["userID"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                    ";

            echo "</tr>";

            /*HIDDEN --> slideDown click su matita modifica utente*/
            echo "
                    <div method='post' action='modificaUtente.php'>
                    <tr style='display: none;' id='".$outUtenti["userID"]."'>
                            <td><input id='".$outUtenti["userID"]."nome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"nome\"  value=".$outUtenti["nome_utente"]."></td>
                            <td><input id='".$outUtenti["userID"]."cognome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"cognome\"  value=".$outUtenti["cognome_utente"]."></td>";
            if($outUtenti["sesso_utente"] == "M"){  //il "pre select" viene settato al valore inizialmente registrato
                echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='".$outUtenti["userID"]."sesso' name=\"sesso\"><option value=\"male\" selected>M</option><option value=\"female\">F</option></select></td>";
            }else{
                echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='".$outUtenti["userID"]."sesso' name=\"sesso\"><option value=\"male\">M</option><option value=\"female\" selected>F</option></select></td>";
            }
            echo "
                            <td><input id='".$outUtenti["userID"]."data' class=\"w3-input w3-border w3-round\" type=\"date\" name=\"data\"  value=".$outUtenti["data_nascita_utente"]."></td>";

            echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='".$outUtenti["userID"]."societa' name=\"societa\">";
            /*RICERCA SOCIETA PER SELECT*/
            $querySocieta = "SELECT id, nome FROM societa;";
            $risSocieta = $conn->query($querySocieta);
            while($societa = $risSocieta->fetch_assoc()) {
                if($societa["id"]==$outUtenti["id_societa"])
                    echo "<option value='".$societa["id"]."' selected>".$societa["nome"]."</option>";
                else
                    echo "<option value='".$societa["id"]."'>".$societa["nome"]."</option>";
            }
            echo "</select></td>";


            echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='".$outUtenti["userID"]."categoria' name=\"categoria\">";
            /*RICERCA CATEGORIA PER SELECT*/
            $queryCategoria = "SELECT id, nome FROM categoria;";
            $risCategoria = $conn->query($queryCategoria);
            while($categoria = $risCategoria->fetch_assoc()) {
                if($categoria["id"]==$outUtenti["id_categoria"])
                    echo "<option value='".$categoria["id"]."' selected>".$categoria["nome"]."</option>";
                else
                    echo "<option value='".$categoria["id"]."'>".$categoria["nome"]."</option>";
            }
            echo "</select></td>";


            echo "<td><input id='".$outUtenti["userID"]."pettorina' class=\"w3-input w3-border w3-round\" type=\"number\" name=\"pettorina\"  value=".$outUtenti["pettorina_utente"]."></td>";

            echo "<td></td>";   //copre buco punteggio (il punteggio di ogni gara è modificabile su un altra schermata)

            echo "
                    <td><button class='w3-button w3-teal' onclick=\"modificaUtente(".$outUtenti['userID'].", document.getElementById('".$outUtenti['userID']."nome').value, document.getElementById('".$outUtenti['userID']."cognome').value, document.getElementById('".$outUtenti['userID']."sesso').value, document.getElementById('".$outUtenti['userID']."data').value, document.getElementById('".$outUtenti['userID']."societa').value, document.getElementById('".$outUtenti['userID']."categoria').value, document.getElementById('".$outUtenti['userID']."pettorina').value);\">MODIFICA</button></td>
                    </tr>        
                    </div>
                ";
        }
        echo "        
        </table>
        </div>
        </div>
    ";


    }else{  //RAGGRUPPA PER CATEGORIA
        echo "
        <button style='margin-right: 10%; margin-top: 2%; width: 20%;' class='w3-blue-gray w3-button w3-block w3-centered w3-margin w3-round-medium' onclick=\"raggruppa('1')\" id='btnRaggruppamento' value='1'>RAGGRUPPA PER SOCIETA</button>
    <hr style='margin:auto; margin-top: 2%; width: 95%;'>
    <a href='aggiungiUtente.php' class='w3-ripple w3-teal w3-button w3-block w3-centered' style='margin:auto; margin-top: 3%; width: 90%;' >AGGIUNGI UTENTE</a><br>

    <div id='outputJQ'><!--/Output JQUERY.../-->

    <div class='w3-responsive'><!--Scroll bar se schermata troppo piccola-->
    <table align='center' style='width: 90%;' id='myTable' class='w3-table w3-striped w3-centered w3-large w3-hoverable w3-border'>
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

        if($catID=="all")   //se l'utente non ha selezionato nessuna categoria in particolare, stampa tutto
            $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY categoria.nome ASC, utente.nome ASC, utente.cognome ASC, societa.nome ASC;";
        else
            $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND utente.id_categoria = ".$catID." GROUP BY utente.ID ORDER BY categoria.nome ASC, utente.nome ASC, utente.cognome ASC, societa.nome ASC;";

        $ris = $conn->query($query);

        while($outUtenti = $ris->fetch_assoc()){
            //Si usa classe cat[idCategoria] per select JQuery categoria
            echo "
    <tr class='cat".$outUtenti["id_categoria"]." record'>
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
                    <td><button onclick='animazioneModificaUtente(".$outUtenti["userID"].");' class=\"w3-btn w3-ripple\"><img src='round_create_black_18dp.png'></button></td>
                    <td><button onclick='rimuoviUtente(\"".$outUtenti["nome_utente"]."\",".$outUtenti["userID"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                    ";

            echo "</tr>";

            /*HIDDEN --> slideDown click su matita modifica utente*/
            echo "
                    <div method='post' action='modificaUtente.php'>
                    <tr style='display: none;' id='".$outUtenti["userID"]."'>
                            <td><input id='".$outUtenti["userID"]."nome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"nome\"  value=".$outUtenti["nome_utente"]."></td>
                            <td><input id='".$outUtenti["userID"]."cognome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"cognome\"  value=".$outUtenti["cognome_utente"]."></td>";
            if($outUtenti["sesso_utente"] == "M"){  //il "pre select" viene settato al valore inizialmente registrato
                echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='".$outUtenti["userID"]."sesso' name=\"sesso\"><option value=\"male\" selected>M</option><option value=\"female\">F</option></select></td>";
            }else{
                echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='".$outUtenti["userID"]."sesso' name=\"sesso\"><option value=\"male\">M</option><option value=\"female\" selected>F</option></select></td>";
            }
            echo "
                            <td><input id='".$outUtenti["userID"]."data' class=\"w3-input w3-border w3-round\" type=\"date\" name=\"data\"  value=".$outUtenti["data_nascita_utente"]."></td>";

            echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='".$outUtenti["userID"]."societa' name=\"societa\">";
            /*RICERCA SOCIETA PER SELECT*/
            $querySocieta = "SELECT id, nome FROM societa;";
            $risSocieta = $conn->query($querySocieta);
            while($societa = $risSocieta->fetch_assoc()) {
                if($societa["id"]==$outUtenti["id_societa"])
                    echo "<option value='".$societa["id"]."' selected>".$societa["nome"]."</option>";
                else
                    echo "<option value='".$societa["id"]."'>".$societa["nome"]."</option>";
            }
            echo "</select></td>";


            echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='".$outUtenti["userID"]."categoria' name=\"categoria\">";
            /*RICERCA CATEGORIA PER SELECT*/
            $queryCategoria = "SELECT id, nome FROM categoria;";
            $risCategoria = $conn->query($queryCategoria);
            while($categoria = $risCategoria->fetch_assoc()) {
                if($categoria["id"]==$outUtenti["id_categoria"])
                    echo "<option value='".$categoria["id"]."' selected>".$categoria["nome"]."</option>";
                else
                    echo "<option value='".$categoria["id"]."'>".$categoria["nome"]."</option>";
            }
            echo "</select></td>";


            echo "<td><input id='".$outUtenti["userID"]."pettorina' class=\"w3-input w3-border w3-round\" type=\"number\" name=\"pettorina\"  value=".$outUtenti["pettorina_utente"]."></td>";

            echo "<td></td>";   //copre buco punteggio (il punteggio di ogni gara è modificabile su un altra schermata)

            echo "
                    <td><button class='w3-button w3-teal' onclick=\"modificaUtente(".$outUtenti['userID'].", document.getElementById('".$outUtenti['userID']."nome').value, document.getElementById('".$outUtenti['userID']."cognome').value, document.getElementById('".$outUtenti['userID']."sesso').value, document.getElementById('".$outUtenti['userID']."data').value, document.getElementById('".$outUtenti['userID']."societa').value, document.getElementById('".$outUtenti['userID']."categoria').value, document.getElementById('".$outUtenti['userID']."pettorina').value);\">MODIFICA</button></td>
                    </tr>        
                    </div>
                ";
        }
        echo "        
    </table>
    </div>
    </div>";
    }
?>

<?php
}else{
    echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  //#TODO Error page
    //header("location: errorPage.php");
}
?>