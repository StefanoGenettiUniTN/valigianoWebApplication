<?php
require_once("DBconfig.php");

if(isset($_GET["garaID"])) {
    $garaID = $_GET["garaID"];  //id gara selezionata

    ?>

    <head>
        <title>Valigiano Web Application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="w3.css">
        <script src="jquery-3.4.1.min.js"></script>
        <script src="sorttable.js"></script>

        <script>

            /**Elimina utente*/
            function rimuoviIscritto(nome, idGara, idUtente){
                if(confirm("L'utente "+nome+" sta per essere rimosso dalla lista iscritti alla gara"))
                    $.ajax({
                        url: 'removeSubscriber.php?raceID='+idGara+'&userID='+idUtente,  //idUtente contiene l'id dell'utente da rimuovere
                        success: function(data) {
                            $("#outputJQ").html(data);
                        }
                    });
            }

            function infoUser(link) {
                window.location.href = "profiloUtente.php?userID="+link;
            }

            function filtroCategoria(valore){
                if(valore=="all"){
                    $('.record').show();
                }else{
                    $('.record').hide();
                    $(".cat"+valore).show();
                }
            }

            /**Nel DIV OUTPUTJQUERY restituisce la tabella relativa ai risultati della categoria selezionata*/
            function stampaRisultatiCategoria(garaSelezionata, categoriaSelezionata){
                if(garaSelezionata, categoriaSelezionata){
                    if(categoriaSelezionata != "zero"){ //zero significa "Nessuna categoria selezionata"... in quel caso non fare nntù

                        $.post("viewCategoryResults.php",
                            {
                                catID: categoriaSelezionata,
                                raceID: garaSelezionata
                            },
                            function(data, status){
                                $("#outputJQ").html(data);
                            });

                    }else{
                        $("#outputJQ").html("");
                    }
                }
            }

            /**Una serie di pop up in cui inserire i numeri di pettorina in ordine di arrivo poi passa in post e aggiorna pagina*/
            function inserimentoDati(garaSelezionata){
                var rowCount = $('#TabellaRis tr').length;  //restituisce numero delle righe della tabella (compresa intestazione)... usata per numero di ripetizione iterazione
                var selectedCat = $('#catFilter').val();    //categoria selezionata

                var arrayRisultati = []; //array popolato dei numeri di pettorina in ordine di arrivo
                var promptInput;    //dati inseriti prima in prompt input e poi scritti nell'array
                var i = 0;
                if(selectedCat!="zero"){    //nessuna categoria selezionata = "zero"
                    while(i<(rowCount-1) && promptInput!="F"){
                        promptInput = prompt("Inserisca i numeri di pettorina degli atleti in ordine di arrivo. Per terminare l'inupt manualmente digiti [ F (fine)]");
                        if(promptInput!="F" && Number.isInteger(parseInt(promptInput))) {
                            arrayRisultati.push(promptInput);
                            i++;
                        }else if(promptInput!="F"){
                            alert("ATTENZIONE, ha inserito un valore non valido. Ripetere l'inserimento del valore.");
                        }
                    }

                    $.post("insertResults.php",
                        {
                            catID: selectedCat,
                            raceID: garaSelezionata,
                            resultsArray: arrayRisultati
                        },
                        function(data, status){
                            $("#outputJQ").html(data);
                        });
                }
            }

        </script>

    </head>

    <body>
    <a href="profiloGara.php?garaID=<?php echo"$garaID";?>" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="round_keyboard_backspace_black_18dp.png"></i>Torna alla lista iscritti</button></a>
    <div class="w3-card w3-margin w3-round">
        <?php
        $queryTitolo = "SELECT * FROM gara WHERE ID=".$garaID.";";
        $risQueryTitolo = $conn->query($queryTitolo);
        if($outTitolo = $risQueryTitolo->fetch_assoc())
            echo "<h2 class='w3-margin w3-container' style='height: 8%;'><b>EVENTO:</b> ".$outTitolo["luogo"]."  ".$outTitolo["data"]." - <u>Inserimento risultati</u></h2>";
        else
            echo "<script>alert('Fatal error');</script>";  #TODO error page
        ?>
    </div>


    <h5 class="w3-margin-left w3-padding">Specifichi la <b>cetegoria</b> per la quale vuole inserire i risultati degli atleti.</h5>
    <select id="catFilter" class='w3-select w3-border w3-round w3-margin-left' name="filtroCategoria" style="width: 90%;" onchange='stampaRisultatiCategoria(<?php echo $garaID;?>, this.value);'>
        <option value="zero" selected>Nessuna categoria selezionata</option>
        <?php
        /*RICERCA CATEGORIA PER SELECT*/
        $queryCategoria = "SELECT id, nome FROM categoria;";
        $risCategoria = $conn->query($queryCategoria);
        while($categoria = $risCategoria->fetch_assoc()) {
            echo "<option value='".$categoria["id"]."'>".$categoria["nome"]."</option>";
        }
        ?>
    </select>

    <button class="w3-button w3-margin-top w3-margin-left w3-round-large w3-centered w3-deep-orange" style="width: 20%;" onclick="inserimentoDati(<?php echo $garaID;?>);">COMPILA LA CLASSIFICA</button><br>

    <hr style="margin:auto; margin-top: 2%; margin-bottom: 3%; width: 95%;">

    <div id="outputJQ"><!--/Output JQUERY.../-->

    </div>

</body>
</html>

<?php
}else{
    echo "Error!";  //#TODO Error page
}
?>
