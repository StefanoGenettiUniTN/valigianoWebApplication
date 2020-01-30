<?php
require_once("DBconfig.php");
?>

<head>
    <title>Valigiano Web Application</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="w3.css">
    <script src="jquery-3.4.1.min.js"></script>
    <script src="sorttable.js"></script>
    <script src="print.min.js"></script>
    <link rel="stylesheet" type="text/css" href="print.min.css">

    <script>

        function infoUser(link) {
            window.location.href = "profiloUtente.php?userID="+link;
        }


        /**ALTERNATIVA NON USATA --> filtro categoria con nuova query #TODO pensare quale soluzione sia meglio (rispetto a hide/show)*/
        /**Nel DIV OUTPUTJQUERY restituisce la tabella relativa ai risultati della categoria e della gara selezionati*/
        function stampaRisultatiCategoria(categoriaSelezionata){
            if(categoriaSelezionata){
                $.post("displayCategoryRank.php",
                    {
                        catID: categoriaSelezionata
                    },
                    function(data, status){
                        $("#outputJQ").html(data);
                    });
            }
        }
        /**..............................*/

        function filtroCategoria(valore){
            if(valore=="zero"){
                $('.record').show();
            }else{
                $('.record').hide();
                $(".cat"+valore).show();
            }
        }


        /**Nel DIV OUTPUTJQUERY restituisce la tabella relativa ai risultati della gara selezionata*/
        /**NB: Ristampa anche select categoria*/
        function stampaRisultatiGara(garaSelezionata){
            $.post("displayRaceRank.php",
                {
                    garID: garaSelezionata
                },
                function(data, status){
                    $("#outputJQGara").html(data);
                });
        }

    </script>

</head>

<body>
<a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
<div class="w3-card w3-margin w3-round">
    <h2 class='w3-margin w3-container' style='height: 8%;'>Classifica singola gara</h2>
</div>

<h5 class="w3-margin-left w3-padding">Specifichi la <b>gara</b></h5>
<select id="garFilter" class='w3-select w3-border w3-round w3-margin-left' name="filtroGara" style="width: 90%;" onchange='stampaRisultatiGara(this.value);'>
    <option value="zero" selected>Nessuna gara selezionata</option>
    <?php
    /*RICERCA Gare PER SELECT*/
    $queryGara = "SELECT * FROM gara;";
    $risGara = $conn->query($queryGara);
    while($gara = $risGara->fetch_assoc()) {
        echo "<option value='".$gara["ID"]."'>".$gara["luogo"]."</option>";
    }
    ?>
</select>

<div id="outputJQGara"><!--/Output JQUERY Gara.../-->

    <h5 class="w3-margin-left w3-padding">Specifichi la <b>cetegoria</b> da stampare</h5>
    <select id="catFilter" class='w3-select w3-border w3-round w3-margin-left' name="filtroCategoria" style="width: 90%;" onchange='filtroCategoria(this.value);'>
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

    <hr style="margin:auto; margin-top: 2%; margin-bottom: 3%; width: 95%;">

    <div id="outputJQClassifica"><!--/Output JQUERY Classifica.../-->

    </div> <!--Output JQUERY Classifica-->
</div>  <!--Output JQUERY Gara-->

</body>
</html>

