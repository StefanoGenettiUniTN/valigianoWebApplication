<?php
    session_start();
    require_once("DBconfig.php");
?>

<html>

    <head>
        <title>Valligiano Web Application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="w3.css">
        <link rel="icon" href="immagini/logo.png" type="image/gif" sizes="16x16">
        <script src="jquery-3.4.1.min.js"></script>
        <script src="sorttable.js"></script>    <!--#TODO NON usata per bug slowDown modifica, ma interessante--><!--Libreria per ordinamento tabella da colonna-->
        <script>
            /**Elimina utente*/
            function rimuoviUtente(nome, idUtente){
                var raggruppamentoSelezionato = $('#btnRaggruppamento').val();  //SE 1 c'è scritto raggruppa per SOCIETA, SE 2 c'è scritto ragguppa per CATEGORIA
                var categoriaSelezionata = $("#catFilter").val();   //categoria selezionata
                if(confirm("L'utente "+nome+" sta per essere eliminato"))
                    $.ajax({
                        url: 'removeUser.php?userID='+idUtente+"&type="+raggruppamentoSelezionato+"&catID="+categoriaSelezionata,  //idUtente contiene l'id dell'utente da rimuovere
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

            /**Animazione modifica profilo utente*/
            function animazioneModificaUtente(idUtente){
                if($("#"+idUtente).is(":hidden")){
                    $("#"+idUtente).show("slow");
                }
                else{
                    $("#" + idUtente).hide("slow");
                }
            }


            /*Riceve in input dati utente e modifica record*/
            function modificaUtente(idUtente, nome, cognome, sesso, data, societa, categoria, pettorina){
                var raggruppamentoSelezionato = $('#btnRaggruppamento').val();  //SE 1 c'è scritto raggruppa per SOCIETA, SE 2 c'è scritto ragguppa per CATEGORIA
                var categoriaSelezionata = $("#catFilter").val();   //categoria selezionata

                if(idUtente && nome && cognome && sesso && data && societa && categoria && pettorina){
                    $.post("modify.php?type="+raggruppamentoSelezionato+"&catID="+categoriaSelezionata,
                        {
                            id: idUtente,
                            name: nome,
                            surname: cognome,
                            sex: sesso,
                            date: data,
                            society: societa,
                            category: categoria,
                            number: pettorina   //pettorina
                        },
                        function(data, status){
                            $("#outputJQ").html(data);
                        });
                }
            }

            //Filtra categoria
            /*
            $('#catFilter').on('change', function() {
                if(this.value=="all"){
                    $('.record').show();
                }else{
                    $('.record').hide();
                    $(".cat"+this.value).show();
                }
            });
            */

            /*
            function filtroCategoria(valore){   //si preferisce non usare più questa alternativa ma rifare query #TODO aggiustare id e classi
                if(valore=="all"){
                    $('.record').show();
                }else{
                    $('.record').hide();
                    $(".cat"+valore).show();
                }
            }
            */

            function filtroCategoria(valore){   //valore contiene la categoria selezionata
                var raggruppamentoSelezionato = $('#btnRaggruppamento').val();  //SE 1 c'è scritto raggruppa per SOCIETA, SE 2 c'è scritto ragguppa per CATEGORIA
                $.ajax({
                    url: 'categoryFilterModificaUtente.php?catID='+valore+'&type='+raggruppamentoSelezionato,  //type 1 --> raggruppa societa type 2 --> raggruppa categoria //catID=categoria selezionata
                    success: function(data) {
                        $("#outputJQRaggruppamento").html(data);
                    }
                });
            }


            function raggruppa(tipo){
                var categoriaSelezionata = $("#catFilter").val();
                $.ajax({
                    url: 'raggruppa.php?type='+tipo+'&catID='+categoriaSelezionata,  //type 1 --> raggruppa societa type 2 --> raggruppa categoria //catID=categoria selezionata
                    success: function(data) {
                        $("#outputJQRaggruppamento").html(data);
                    }
                });
            }

            /*
            NON POSSO METTERLO PER SLOW DOWN MODIFY
            Aggiorna libreria sorttable
            function refreshSortable(){
                var newTableObject = document.getElementById("myTable");
                sorttable.makeSortable(newTableObject);
            }
            */

        </script>

    </head>

<body>
    <a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 10%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
    <div class="w3-card w3-margin w3-round">
        <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 10%;">Pagina di modifica dati atleta</h2>
    </div>

    <div style="margin-right: 10%; margin-top: 2%; width: 20%;" class="w3-margin-bottom">
        <p class="w3-margin-left">Filtra per categoria:</p>
        <select id="catFilter" class='w3-select w3-border w3-round' name=\"filtroCategoria\" style="margin-left: 10%;" onchange="filtroCategoria(this.value);">
        <option value="all" selected>Mostra tutte le categorie</option>
        <?php
            /*RICERCA CATEGORIA PER SELECT*/
            $queryCategoria = "SELECT id, nome FROM categoria;";
            $risCategoria = $conn->query($queryCategoria);
            while($categoria = $risCategoria->fetch_assoc()) {
                echo "<option value='".$categoria["id"]."'>".$categoria["nome"]."</option>";
            }
        ?>
        </select>
    </div>

    <div id="outputJQRaggruppamento"><!--/Output JQUERY Raggruppamento.../-->
        <button style="margin-right: 10%; margin-top: 2%; width: 20%;" class="w3-blue-gray w3-button w3-block w3-centered w3-margin w3-round-medium" onclick="raggruppa(1)" id="btnRaggruppamento" value="1">RAGGRUPPA PER SOCIETA</button>

    <hr style="margin:auto; margin-top: 2%; width: 95%;">
    <a href="aggiungiUtente.php" class="w3-ripple w3-teal w3-button w3-block w3-centered" style="margin:auto; margin-top: 3%; width: 90%;" >AGGIUNGI ATLETA</a><br>

    <div id="outputJQ"><!--/Output JQUERY.../-->

    <div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
    <table align="center" style="width: 90%;" id="myTable" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border">
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
        $query = "SELECT utente.nome AS nome_utente, utente.cognome AS cognome_utente, utente.sesso AS sesso_utente, utente.data_nascita AS data_nascita_utente, utente.n_pettorina AS pettorina_utente, utente.ID AS userID, utente.id_categoria, utente.id_societa, categoria.nome AS nome_categoria, categoria.ID, classifica.id_utente, societa.nome AS nome_societa, societa.ID, SUM(classifica.punteggio) AS punteggio FROM societa, categoria, utente LEFT OUTER JOIN classifica ON utente.ID = classifica.id_utente WHERE utente.id_categoria = categoria.ID AND utente.id_societa = societa.ID GROUP BY utente.ID ORDER BY categoria.nome ASC, utente.nome ASC, utente.cognome ASC, societa.nome ASC;";
        //$queryUtenti = "SELECT * FROM utente LEFT OUTER JOIN categoria AS cat ON utente.id_categoria = cat.ID UNION SELECT * FROM utente LEFT OUTER JOIN classifica AS cla ON utente.ID = cla.ID;";
        //$queryPunteggio = "SELECT * FROM utente, classifica WHERE utente.ID = classifica.id_utente;";
        //$queryCategoria = "SELECT * FROM utente, categoria WHERE utente.id_categoria = categoria.ID;";

        $ris = $conn->query($query);
        //$risQ_Utenti = $conn->query($queryUtenti);
        //$risQ_Punteggio = $conn->query($queryPunteggio);
        //$risQ_Categoria = $conn->query($queryUtenti);

        while($outUtenti = $ris->fetch_assoc()){
            /*#TODO class="riga" tentativo originale...vedi script commentato in header*/

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
                            <td><input id='".$outUtenti["userID"]."nome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"nome\"  value=\"".$outUtenti["nome_utente"]."\"></td>
                            <td><input id='".$outUtenti["userID"]."cognome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"cognome\"  value=\"".$outUtenti["cognome_utente"]."\"></td>";
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
        ?>
    </table>
    </div>

    </div><!--/Output JQUERY.../-->
    </div><!--/Output JQUERY raggrupamento.../-->
</body>

</html>