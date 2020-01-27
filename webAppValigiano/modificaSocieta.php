<?php
require_once("DBconfig.php");
?>

<html>

<head>
    <title>Valigiano Web Application</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="w3.css">
    <script src="jquery-3.4.1.min.js"></script>
</head>

<script>
    /*#TODO NON ANCORA USATO NELLA PAGINA modificaSocieta.php*/
    /*Script per collegamento ipertestuale click sulla riga della tabella*/
    /*
    $(document).ready(function(){
        $('.riga').click(function(){
            window.location = $(this).attr('href');
            return false;
        });
    });
    */

    /**Elimina societa*/
    function rimuoviSocieta(nome, idSocieta){
        if(confirm("La sciet&agrave "+nome+" sta per essere eliminata"))
            $.ajax({
                url: 'removeSociety.php?socID='+idSocieta,  //idSocieta contiene l'id della società da rimuovere
                success: function(data) {
                    $("#outputJQ").html(data);
                }
            });
    }


    /**Animazione modifica societa*/
    function animazioneModificaSocieta(idSocieta){
        if($("#"+idSocieta).is(":hidden")){
            $("#"+idSocieta).show("slow");
        }
        else{
            $("#" + idSocieta).hide("slow");
        }
    }


    /*Riceve in input dati societa e modifica record*/
    function modificaSocieta(idSocieta, nome, sede){
        if(idSocieta && nome && sede){
            $.post("modifySoc.php",
                {
                    id: idSocieta,
                    name: nome,
                    location: sede
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
    <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 8%;">Pagina di modifica societ&agrave</h2>
</div>

<a href="aggiungiSocieta.php" class="w3-ripple w3-teal w3-button w3-block w3-centered" style="margin: auto; width: 90%;" >AGGIUNGI SOCIETA'</a><br>

<div id="outputJQ"><!--/Output JQUERY.../-->

<div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
    <table style="width: 50%; margin-left: 5%;" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border">
        <tr class="w3-green">
            <th>Nome Societ&agrave</th>
            <th>Sede</th>
        </tr>

        <?php
        $query = "SELECT * FROM societa ORDER BY nome ASC;";
        $ris = $conn->query($query);

        while($outSocieta = $ris->fetch_assoc()){
            /*href='profiloSocieta.php?userID=".$outSocieta['ID']."'*/
            echo "
                <tr class='riga'>
                    <td>".$outSocieta["nome"]."</td>
                    <td>".$outSocieta["sede"]."</td>
                    ";
            echo "
                    <td style='width: 2%;'><button onclick='animazioneModificaSocieta(".$outSocieta["ID"].");' class=\"w3-btn w3-ripple\"><img src='round_create_black_18dp.png'></button></td>
                    <td style='width: 2%;'><button onclick='rimuoviSocieta(\"".$outSocieta["nome"]."\",".$outSocieta["ID"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                    ";
            echo "</tr>";

            /**NASCOSTO PER SLIDE DOWN*/
            echo "
                    <div method='post' action='modificaSocieta.php'>
                    <tr style='display: none;' id='".$outSocieta["ID"]."'>
                            <td><input id='".$outSocieta["ID"]."nome' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"nome\"  value=\"".$outSocieta["nome"]."\"></td>
                            <td><input id='".$outSocieta["ID"]."sede' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"sede\"  value=\"".$outSocieta["sede"]."\"></td>";
            echo "
                    <td><button class='w3-button w3-teal' onclick=\"modificaSocieta(".$outSocieta['ID'].", document.getElementById('".$outSocieta['ID']."nome').value, document.getElementById('".$outSocieta['ID']."sede').value);\">MODIFICA</button></td>
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