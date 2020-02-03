<?php
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

</head>

<script>
    /*#TODO NON ANCORA USATO NELLA PAGINA modificaCategoria.php - aggiungere collegamento per numero iscritti*/
    /*Script per collegamento ipertestuale click sulla riga della tabella*/
    /*
    $(document).ready(function(){
        $('.riga').click(function(){
            window.location = $(this).attr('href');
            return false;
        });
    });
    */

    /**Elimina categoria*/
    function rimuoviCategoria(nome, idCategoria){
        if(confirm("La categoria "+nome+" sta per essere eliminata"))
            $.ajax({
                url: 'removeCategory.php?catID='+idCategoria,  //idCategoria contiene l'id della categoria da rimuovere
                success: function(data) {
                    $("#outputJQ").html(data);
                }
            });
    }


    /**Animazione modifica categoria*/
    function animazioneModificaCategoria(idCategoria){
        if($("#"+idCategoria).is(":hidden")){
            $("#"+idCategoria).show("slow");
        }
        else{
            $("#" + idCategoria).hide("slow");
        }
    }


    /*Riceve in input dati categoria e modifica record*/
    function modificaCategoria(idCategoria, nome, tetto){
        if(idCategoria && nome && tetto){
            $.post("modifyCat.php",
                {
                    id: idCategoria,
                    name: nome,
                    tetto: tetto
                },
                function(data, status){
                    $("#outputJQ").html(data);
                });
        }
    }


</script>

<body>
<a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
<div class="w3-card w3-margin w3-round">
    <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 8%;">Pagina di modifica categoria</h2>
</div>

<a href="aggiungiCategoria.php" class="w3-ripple w3-teal w3-button w3-block w3-centered" style="margin: auto; width: 90%;" >AGGIUNGI CATEGORIA</a><br>

<div id="outputJQ"><!--/Output JQUERY.../-->

<div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
    <table style="width: 60%; margin-left: 5%;" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border">
        <tr class="w3-green">
            <th>Nome Categoria</th>
            <th>Punteggio di partenza</th>
        </tr>

        <?php
        $query = "SELECT * FROM categoria ORDER BY nome ASC;";
        $ris = $conn->query($query);

        while($outCategoria = $ris->fetch_assoc()){
            /*href='profiloCategoria.php?userID=".$outCategoria['ID']."'*/
            echo "
                <tr class='riga'>
                    <td>".$outCategoria["nome"]."</td>
                    <td>".$outCategoria["tetto"]."</td>
            ";

            echo "
                    <td style='width: 2%;'><button onclick='animazioneModificaCategoria(".$outCategoria["ID"].");' class=\"w3-btn w3-ripple\"><img src='round_create_black_18dp.png'></button></td>
                    <td style='width: 2%;'><button onclick='rimuoviCategoria(\"".$outCategoria["nome"]."\",".$outCategoria["ID"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                    ";

            echo "</tr>";


            /**NASCOSTO PER SLIDE DOWN*/
            echo "
                    <div method='post' action='modificaCategoria.php'>
                    <tr style='display: none;' id='".$outCategoria["ID"]."'>
                            <td><input id='".$outCategoria["ID"]."nome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"nome\"  value=\"".$outCategoria["nome"]."\"></td>
                            <td><input id='".$outCategoria["ID"]."tetto' class=\"w3-input w3-border w3-round\" type=\"number\" name=\"tetto\"  value='".$outCategoria["tetto"]."'></td>";
            echo "
                    <td><button class='w3-button w3-teal' onclick=\"modificaCategoria(".$outCategoria['ID'].", document.getElementById('".$outCategoria['ID']."nome').value, document.getElementById('".$outCategoria['ID']."tetto').value);\">MODIFICA</button></td>
                    </tr>        
                    </div>
                ";
        }
        ?>
    </table>
</div>

</div><!--/Fine...OUTPUT JQUERY-->

</body>

</html>