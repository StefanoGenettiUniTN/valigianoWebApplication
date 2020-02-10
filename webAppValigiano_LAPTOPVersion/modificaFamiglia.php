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

    <style>
        .riga {
            cursor: pointer;
        }
    </style>

</head>

<script>
    /**Elimina famiglia*/
    function rimuoviFamiglia(nome, idFamiglia){
        if(confirm("La famiglia "+nome+" sta per essere eliminata"))
            $.ajax({
                url: 'removeFamily.php?famID='+idFamiglia,  //idFamiglia contiene l'id della famiglia da rimuovere
                success: function(data) {
                    $("#outputJQ").html(data);
                }
            });
    }


    /**Animazione modifica famiglia*/
    function animazioneModificaFamiglia(idFamiglia){
        if($("#"+idFamiglia).is(":hidden")){
            $("#"+idFamiglia).show("slow");
        }
        else{
            $("#" + idFamiglia).hide("slow");
        }
    }


    /*Riceve in input dati famiglia e modifica record*/
    function modificaFamiglia(idFamiglia, nome){
        if(idFamiglia && nome){
            $.post("modifyFam.php",
                {
                    id: idFamiglia,
                    name: nome
                },
                function(data, status){
                    $("#outputJQ").html(data);
                });
        }
    }


    /**Accede ai componenti della famiglia --> nella pagina posso inserire componenti famiglia*/
    function infoFamiglia(link) {
        window.location.href = "profiloFamiglia.php?famID="+link;
    }

</script>

<body>
<a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 10%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
<div class="w3-card w3-margin w3-round">
    <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 10%;">Pagina di modifica famiglia</h2>
</div>

<a href="aggiungiFamiglia.php" class="w3-ripple w3-teal w3-button w3-block w3-centered" style="margin: auto; width: 90%;" >AGGIUNGI FAMIGLIA</a><br>

<div id="outputJQ"><!--/Output JQUERY.../-->

    <div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
        <table style="width: 40%; margin-left: 5%;" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border">
            <tr class="w3-green">
                <th>Nome Famiglia</th>
            </tr>

            <?php
            $query = "SELECT * FROM famiglia ORDER BY nome ASC;";
            $ris = $conn->query($query);

            while($outFamiglia = $ris->fetch_assoc()){
                echo "
                <tr class='riga'>
                    <td href='profiloFamiglia.php?famID=".$outFamiglia["ID"]."' onclick='infoFamiglia(".$outFamiglia["ID"].");'>".$outFamiglia["nome"]."</td>
                    ";

                echo "
                    <td style='width: 2%;'><button onclick='animazioneModificaFamiglia(".$outFamiglia["ID"].");' class=\"w3-btn w3-ripple\"><img src='round_create_black_18dp.png'></button></td>
                    <td style='width: 2%;'><button onclick='rimuoviFamiglia(\"".$outFamiglia["nome"]."\",".$outFamiglia["ID"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                    ";

                echo "</tr>";


                /**NASCOSTO PER SLIDE DOWN*/
                echo "
                    <div method='post' action='modificaFamiglia.php'>
                    <tr style='display: none;' id='".$outFamiglia["ID"]."'>
                            <td><input id='".$outFamiglia["ID"]."nome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"nome\"  value=\"".$outFamiglia["nome"]."\"></td>";
                echo "
                    <td><button class='w3-button w3-teal' onclick=\"modificaFamiglia(".$outFamiglia['ID'].", document.getElementById('".$outFamiglia['ID']."nome').value);\">MODIFICA</button></td>
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