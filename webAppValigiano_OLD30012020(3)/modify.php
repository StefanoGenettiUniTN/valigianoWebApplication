<?php
session_start();
require_once("DBconfig.php");
require_once("function.php");
?>

<?php

    /*
     * Vengono passati in GET anche catID (categoria selezionata) e type (raggruppamento selezionato) che non devono cambiare nell'aggiornamento dinamico
    */
    if(isset($_POST["id"]) && isset($_POST["name"]) && isset($_POST["surname"]) && isset($_POST["sex"]) && isset($_POST["date"]) && isset($_POST["society"]) && isset($_POST["category"]) && isset($_POST["number"]) && isset($_GET["type"]) && isset($_GET["catID"])){
        $userID = $_POST['id'];
        $nome = $_POST['name'];
        $cognome = $_POST['surname'];
        $sesso = $_POST['sex'];
        $data = $_POST['date'];
        $IDsocieta = $_POST['society'];
        $IDcategoria = $_POST['category'];
        $pettorina = $_POST['number'];

        //GET
        $catID = $_GET["catID"];
        $type = $_GET["type"];

        /**Prima di procedere vengono fatti i controlli sulla pettorina (FORMATO) + (ALREADY EXISTS)*/
        if(!is_int($pettorina)) {
            //Controllo anche con getPettorina che quella inserita non sia quella effettivamente posseduta dall'atleta che si sta modificando
            if ($pettorina==getPettorina($userID) || !pettorinaExists($pettorina)) {
                //Query
                if ($sesso == "female")
                    $updateQuery = "UPDATE utente SET nome = '" . $nome . "', cognome = '" . $cognome . "', data_nascita = '" . $data . "', sesso = 'F', n_pettorina = " . $pettorina . ", id_societa = '" . $IDsocieta . "', id_categoria = '" . $IDcategoria . "' WHERE ID = " . $userID . ";";
                else
                    $updateQuery = "UPDATE utente SET nome = '" . $nome . "', cognome = '" . $cognome . "', data_nascita = '" . $data . "', sesso = 'M', n_pettorina = " . $pettorina . ", id_societa = '" . $IDsocieta . "', id_categoria = '" . $IDcategoria . "' WHERE ID = " . $userID . ";";
                $risultato = $conn->query($updateQuery);

                /**Aggiorno pagina*/
                echo "<div class='w3-responsive'>
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

                /**ATTENZIONE --> rispetto a raggruppa.php i controlli su $type sono AL CONTRARIO, perchè dopo il filtro per categoria il bottone, e quindi il raggruppamento NON deve cambiare*/
                if ($type == 1) {

                    if ($catID == "all")   //se l'utente non ha selezionato nessuna categoria in particolare, stampa tutto
                        $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY utente.id_categoria DESC, utente.nome ASC, utente.cognome ASC, utente.id_societa DESC;";
                    else
                        $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND utente.id_categoria = " . $catID . " GROUP BY utente.ID ORDER BY utente.id_categoria DESC, utente.nome ASC, utente.cognome ASC, utente.id_societa DESC;";

                } else {  //type==2

                    if ($catID == "all")   //se l'utente non ha selezionato nessuna categoria in particolare, stampa tutto
                        $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY utente.id_societa DESC, utente.nome ASC, utente.cognome ASC, utente.id_categoria DESC;";
                    else
                        $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND utente.id_categoria = " . $catID . " GROUP BY utente.ID ORDER BY utente.id_societa DESC, utente.nome ASC, utente.cognome ASC, utente.id_categoria DESC;";

                }
                //$query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY utente.id_categoria DESC, utente.nome DESC, utente.cognome DESC, utente.id_societa DESC;";

                $ris = $conn->query($query);

                while ($outUtenti = $ris->fetch_assoc()) {
                    echo "
                <tr class='cat" . $outUtenti["id_categoria"] . " record'>
                    <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["nome_utente"] . "</td>
                    <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["cognome_utente"] . "</td>
                    <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["sesso_utente"] . "</td>
                    <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["data_nascita_utente"] . "</td>
                    <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["nome_societa"] . "</td>
                    <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["nome_categoria"] . "</td>
                    <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["pettorina_utente"] . "</td>
                    ";

                    if (is_null($outUtenti["punteggio"])) {
                        echo "<td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'> - </td>";
                    } else {
                        echo "<td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["punteggio"] . "</td>";
                    }
                    echo "
                    <td><button onclick='animazioneModificaUtente(" . $outUtenti["userID"] . ");' class=\"w3-btn w3-ripple\"><img src='round_create_black_18dp.png'></button></td>
                    <td><button onclick='rimuoviUtente(\"" . $outUtenti["nome_utente"] . "\"," . $outUtenti["userID"] . ");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                    ";
                    echo "</tr>";
                    /**...*/
                    /**PER SLIDE DOWN MODIFICA*/
                    /**...*/
                    echo "
                <div method='post' action='modificaUtente.php'>
                <tr style='display: none;' id='" . $outUtenti["userID"] . "'>
                        <td><input id='" . $outUtenti["userID"] . "nome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"nome\"  value=\"" . $outUtenti["nome_utente"] . "\"></td>
                        <td><input id='" . $outUtenti["userID"] . "cognome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"cognome\"  value=\"" . $outUtenti["cognome_utente"] . "\"></td>";
                    if ($outUtenti["sesso_utente"] == "M") {  //il "pre select" viene settato al valore inizialmente registrato
                        echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "sesso' name=\"sesso\"><option value=\"male\" selected>M</option><option value=\"female\">F</option></select></td>";
                    } else {
                        echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "sesso' name=\"sesso\"><option value=\"male\">M</option><option value=\"female\" selected>F</option></select></td>";
                    }
                    echo "
                        <td><input id='" . $outUtenti["userID"] . "data' class=\"w3-input w3-border w3-round\" type=\"date\" name=\"data\"  value=" . $outUtenti["data_nascita_utente"] . "></td>";

                    echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "societa' name=\"societa\">";
                    /*RICERCA SOCIETA PER SELECT*/
                    $querySocieta = "SELECT id, nome FROM societa;";
                    $risSocieta = $conn->query($querySocieta);
                    while ($societa = $risSocieta->fetch_assoc()) {
                        if ($societa["id"] == $outUtenti["id_societa"])
                            echo "<option value='" . $societa["id"] . "' selected>" . $societa["nome"] . "</option>";
                        else
                            echo "<option value='" . $societa["id"] . "'>" . $societa["nome"] . "</option>";
                    }
                    echo "</select></td>";


                    echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "categoria' name=\"categoria\">";
                    /*RICERCA CATEGORIA PER SELECT*/
                    $queryCategoria = "SELECT id, nome FROM categoria;";
                    $risCategoria = $conn->query($queryCategoria);
                    while ($categoria = $risCategoria->fetch_assoc()) {
                        if ($categoria["id"] == $outUtenti["id_categoria"])
                            echo "<option value='" . $categoria["id"] . "' selected>" . $categoria["nome"] . "</option>";
                        else
                            echo "<option value='" . $categoria["id"] . "'>" . $categoria["nome"] . "</option>";
                    }
                    echo "</select></td>";


                    echo "<td><input id='" . $outUtenti["userID"] . "pettorina' class=\"w3-input w3-border w3-round\" type=\"number\" name=\"pettorina\"  value=" . $outUtenti["pettorina_utente"] . "></td>";

                    echo "<td></td>";   //copre buco punteggio (il punteggio di ogni gara è modificabile su un altra schermata)

                    echo "
                <td><button class='w3-button w3-teal' onclick=\"modificaUtente(" . $outUtenti['userID'] . ", document.getElementById('" . $outUtenti['userID'] . "nome').value, document.getElementById('" . $outUtenti['userID'] . "cognome').value, document.getElementById('" . $outUtenti['userID'] . "sesso').value, document.getElementById('" . $outUtenti['userID'] . "data').value, document.getElementById('" . $outUtenti['userID'] . "societa').value, document.getElementById('" . $outUtenti['userID'] . "categoria').value, document.getElementById('" . $outUtenti['userID'] . "pettorina').value);\">MODIFICA</button></td>
                </tr>        
                </div>
            ";
                }
                echo "</table>";
                echo "</div>";
            } else {   //IF pettorina exists
                echo "<script>alert('Valore inserito non valido - Numero di pettorina gia presente.');</script>";
                    /**Aggiorno pagina*/
                    echo "<div class='w3-responsive'>
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

                    /**ATTENZIONE --> rispetto a raggruppa.php i controlli su $type sono AL CONTRARIO, perchè dopo il filtro per categoria il bottone, e quindi il raggruppamento NON deve cambiare*/
                    if ($type == 1) {

                        if ($catID == "all")   //se l'utente non ha selezionato nessuna categoria in particolare, stampa tutto
                            $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY utente.id_categoria DESC, utente.nome ASC, utente.cognome ASC, utente.id_societa DESC;";
                        else
                            $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND utente.id_categoria = " . $catID . " GROUP BY utente.ID ORDER BY utente.id_categoria DESC, utente.nome ASC, utente.cognome ASC, utente.id_societa DESC;";

                    } else {  //type==2

                        if ($catID == "all")   //se l'utente non ha selezionato nessuna categoria in particolare, stampa tutto
                            $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY utente.id_societa DESC, utente.nome ASC, utente.cognome ASC, utente.id_categoria DESC;";
                        else
                            $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND utente.id_categoria = " . $catID . " GROUP BY utente.ID ORDER BY utente.id_societa DESC, utente.nome ASC, utente.cognome ASC, utente.id_categoria DESC;";

                    }
                    //$query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY utente.id_categoria DESC, utente.nome DESC, utente.cognome DESC, utente.id_societa DESC;";

                    $ris = $conn->query($query);

                    while ($outUtenti = $ris->fetch_assoc()) {
                        echo "
                    <tr class='cat" . $outUtenti["id_categoria"] . " record'>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["nome_utente"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["cognome_utente"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["sesso_utente"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["data_nascita_utente"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["nome_societa"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["nome_categoria"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["pettorina_utente"] . "</td>
                        ";

                        if (is_null($outUtenti["punteggio"])) {
                            echo "<td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'> - </td>";
                        } else {
                            echo "<td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["punteggio"] . "</td>";
                        }
                        echo "
                        <td><button onclick='animazioneModificaUtente(" . $outUtenti["userID"] . ");' class=\"w3-btn w3-ripple\"><img src='round_create_black_18dp.png'></button></td>
                        <td><button onclick='rimuoviUtente(\"" . $outUtenti["nome_utente"] . "\"," . $outUtenti["userID"] . ");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                        ";
                        echo "</tr>";
                        /**...*/
                        /**PER SLIDE DOWN MODIFICA*/
                        /**...*/
                        echo "
                    <div method='post' action='modificaUtente.php'>
                    <tr style='display: none;' id='" . $outUtenti["userID"] . "'>
                            <td><input id='" . $outUtenti["userID"] . "nome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"nome\"  value=\"" . $outUtenti["nome_utente"] . "\"></td>
                            <td><input id='" . $outUtenti["userID"] . "cognome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"cognome\"  value=\"" . $outUtenti["cognome_utente"] . "\"></td>";
                        if ($outUtenti["sesso_utente"] == "M") {  //il "pre select" viene settato al valore inizialmente registrato
                            echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "sesso' name=\"sesso\"><option value=\"male\" selected>M</option><option value=\"female\">F</option></select></td>";
                        } else {
                            echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "sesso' name=\"sesso\"><option value=\"male\">M</option><option value=\"female\" selected>F</option></select></td>";
                        }
                        echo "
                            <td><input id='" . $outUtenti["userID"] . "data' class=\"w3-input w3-border w3-round\" type=\"date\" name=\"data\"  value=" . $outUtenti["data_nascita_utente"] . "></td>";

                        echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "societa' name=\"societa\">";
                        /*RICERCA SOCIETA PER SELECT*/
                        $querySocieta = "SELECT id, nome FROM societa;";
                        $risSocieta = $conn->query($querySocieta);
                        while ($societa = $risSocieta->fetch_assoc()) {
                            if ($societa["id"] == $outUtenti["id_societa"])
                                echo "<option value='" . $societa["id"] . "' selected>" . $societa["nome"] . "</option>";
                            else
                                echo "<option value='" . $societa["id"] . "'>" . $societa["nome"] . "</option>";
                        }
                        echo "</select></td>";


                        echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "categoria' name=\"categoria\">";
                        /*RICERCA CATEGORIA PER SELECT*/
                        $queryCategoria = "SELECT id, nome FROM categoria;";
                        $risCategoria = $conn->query($queryCategoria);
                        while ($categoria = $risCategoria->fetch_assoc()) {
                            if ($categoria["id"] == $outUtenti["id_categoria"])
                                echo "<option value='" . $categoria["id"] . "' selected>" . $categoria["nome"] . "</option>";
                            else
                                echo "<option value='" . $categoria["id"] . "'>" . $categoria["nome"] . "</option>";
                        }
                        echo "</select></td>";


                        echo "<td><input id='" . $outUtenti["userID"] . "pettorina' class=\"w3-input w3-border w3-round\" type=\"number\" name=\"pettorina\"  value=" . $outUtenti["pettorina_utente"] . "></td>";

                        echo "<td></td>";   //copre buco punteggio (il punteggio di ogni gara è modificabile su un altra schermata)

                        echo "
                    <td><button class='w3-button w3-teal' onclick=\"modificaUtente(" . $outUtenti['userID'] . ", document.getElementById('" . $outUtenti['userID'] . "nome').value, document.getElementById('" . $outUtenti['userID'] . "cognome').value, document.getElementById('" . $outUtenti['userID'] . "sesso').value, document.getElementById('" . $outUtenti['userID'] . "data').value, document.getElementById('" . $outUtenti['userID'] . "societa').value, document.getElementById('" . $outUtenti['userID'] . "categoria').value, document.getElementById('" . $outUtenti['userID'] . "pettorina').value);\">MODIFICA</button></td>
                    </tr>        
                    </div>
                ";
                    }
                    echo "</table>";
                    echo "</div>";
            /**................aggiorno pagina*/
            }
        }else {   //IF pettorina non intero
            echo "<script>alert('Valore inserito non valido - Numero di pettorina in formato non valido.');</script>";
            /**Aggiorno pagina*/
            echo "<div class='w3-responsive'>
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

            /**ATTENZIONE --> rispetto a raggruppa.php i controlli su $type sono AL CONTRARIO, perchè dopo il filtro per categoria il bottone, e quindi il raggruppamento NON deve cambiare*/
            if ($type == 1) {

                if ($catID == "all")   //se l'utente non ha selezionato nessuna categoria in particolare, stampa tutto
                    $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY utente.id_categoria DESC, utente.nome ASC, utente.cognome ASC, utente.id_societa DESC;";
                else
                    $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND utente.id_categoria = " . $catID . " GROUP BY utente.ID ORDER BY utente.id_categoria DESC, utente.nome ASC, utente.cognome ASC, utente.id_societa DESC;";

            } else {  //type==2

                if ($catID == "all")   //se l'utente non ha selezionato nessuna categoria in particolare, stampa tutto
                    $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY utente.id_societa DESC, utente.nome ASC, utente.cognome ASC, utente.id_categoria DESC;";
                else
                    $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID AND utente.id_categoria = " . $catID . " GROUP BY utente.ID ORDER BY utente.id_societa DESC, utente.nome ASC, utente.cognome ASC, utente.id_categoria DESC;";

            }
            //$query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY utente.id_categoria DESC, utente.nome DESC, utente.cognome DESC, utente.id_societa DESC;";

            $ris = $conn->query($query);

            while ($outUtenti = $ris->fetch_assoc()) {
                echo "
                    <tr class='cat" . $outUtenti["id_categoria"] . " record'>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["nome_utente"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["cognome_utente"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["sesso_utente"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["data_nascita_utente"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["nome_societa"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["nome_categoria"] . "</td>
                        <td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["pettorina_utente"] . "</td>
                        ";

                if (is_null($outUtenti["punteggio"])) {
                    echo "<td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'> - </td>";
                } else {
                    echo "<td class='riga' href='profiloUtente.php?userID=" . $outUtenti["userID"] . "' onclick='infoUser(" . $outUtenti["userID"] . ");'>" . $outUtenti["punteggio"] . "</td>";
                }
                echo "
                        <td><button onclick='animazioneModificaUtente(" . $outUtenti["userID"] . ");' class=\"w3-btn w3-ripple\"><img src='round_create_black_18dp.png'></button></td>
                        <td><button onclick='rimuoviUtente(\"" . $outUtenti["nome_utente"] . "\"," . $outUtenti["userID"] . ");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                        ";
                echo "</tr>";
                /**...*/
                /**PER SLIDE DOWN MODIFICA*/
                /**...*/
                echo "
                    <div method='post' action='modificaUtente.php'>
                    <tr style='display: none;' id='" . $outUtenti["userID"] . "'>
                            <td><input id='" . $outUtenti["userID"] . "nome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"nome\"  value=\"" . $outUtenti["nome_utente"] . "\"></td>
                            <td><input id='" . $outUtenti["userID"] . "cognome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"cognome\"  value=\"" . $outUtenti["cognome_utente"] . "\"></td>";
                if ($outUtenti["sesso_utente"] == "M") {  //il "pre select" viene settato al valore inizialmente registrato
                    echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "sesso' name=\"sesso\"><option value=\"male\" selected>M</option><option value=\"female\">F</option></select></td>";
                } else {
                    echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "sesso' name=\"sesso\"><option value=\"male\">M</option><option value=\"female\" selected>F</option></select></td>";
                }
                echo "
                            <td><input id='" . $outUtenti["userID"] . "data' class=\"w3-input w3-border w3-round\" type=\"date\" name=\"data\"  value=" . $outUtenti["data_nascita_utente"] . "></td>";

                echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "societa' name=\"societa\">";
                /*RICERCA SOCIETA PER SELECT*/
                $querySocieta = "SELECT id, nome FROM societa;";
                $risSocieta = $conn->query($querySocieta);
                while ($societa = $risSocieta->fetch_assoc()) {
                    if ($societa["id"] == $outUtenti["id_societa"])
                        echo "<option value='" . $societa["id"] . "' selected>" . $societa["nome"] . "</option>";
                    else
                        echo "<option value='" . $societa["id"] . "'>" . $societa["nome"] . "</option>";
                }
                echo "</select></td>";


                echo "<td><select class='w3-select w3-animate-input w3-border w3-round' id='" . $outUtenti["userID"] . "categoria' name=\"categoria\">";
                /*RICERCA CATEGORIA PER SELECT*/
                $queryCategoria = "SELECT id, nome FROM categoria;";
                $risCategoria = $conn->query($queryCategoria);
                while ($categoria = $risCategoria->fetch_assoc()) {
                    if ($categoria["id"] == $outUtenti["id_categoria"])
                        echo "<option value='" . $categoria["id"] . "' selected>" . $categoria["nome"] . "</option>";
                    else
                        echo "<option value='" . $categoria["id"] . "'>" . $categoria["nome"] . "</option>";
                }
                echo "</select></td>";


                echo "<td><input id='" . $outUtenti["userID"] . "pettorina' class=\"w3-input w3-border w3-round\" type=\"number\" name=\"pettorina\"  value=" . $outUtenti["pettorina_utente"] . "></td>";

                echo "<td></td>";   //copre buco punteggio (il punteggio di ogni gara è modificabile su un altra schermata)

                echo "
                    <td><button class='w3-button w3-teal' onclick=\"modificaUtente(" . $outUtenti['userID'] . ", document.getElementById('" . $outUtenti['userID'] . "nome').value, document.getElementById('" . $outUtenti['userID'] . "cognome').value, document.getElementById('" . $outUtenti['userID'] . "sesso').value, document.getElementById('" . $outUtenti['userID'] . "data').value, document.getElementById('" . $outUtenti['userID'] . "societa').value, document.getElementById('" . $outUtenti['userID'] . "categoria').value, document.getElementById('" . $outUtenti['userID'] . "pettorina').value);\">MODIFICA</button></td>
                    </tr>        
                    </div>
                ";
            }
            echo "</table>";
            echo "</div>";
            /**................aggiorno pagina*/
        }
    }else{
        echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  //#TODO Error page
        //header("location: errorPage.php");
    }
?>
