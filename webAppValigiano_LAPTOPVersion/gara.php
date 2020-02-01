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
    <script>
        /**Elimina gara*/
        function rimuoviGara(luogo, idGara){
            if(confirm("La gara "+luogo+" sta per essere eliminata"))
                $.ajax({
                    url: 'removeRace.php?garaID='+idGara,  //idGara contiene l'id della gara da rimuovere
                    success: function(data) {
                        $("#outputJQ").html(data);
                    }
                });
        }

        function infoGara(link) {
            window.location.href = "profiloGara.php?garaID="+link;
        }

        /**Animazione modifica profilo gara*/
        function animazioneModificaGara(idGara){
            if($("#"+idGara).is(":hidden")){
                $("#"+idGara).show("slow");
            }
            else{
                $("#" + idGara).hide("slow");
            }
        }


        /*Riceve in input dati gara e modifica record*/
        function modificaGara(idGara, luogo, data){
            if(idGara && luogo && data){
                $.post("modifyGar.php",
                    {
                        id: idGara,
                        place: luogo,
                        date: data
                    },
                    function(data, status){
                        $("#outputJQ").html(data);
                    });
            }
        }
    </script>

</head>

<body>
<a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 10%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
<div class="w3-card w3-margin w3-round">
    <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 10%;">Pagina di gestione delle gare</h2>
</div>

    <a href="aggiungiGara.php" class="w3-ripple w3-teal w3-button w3-block w3-centered" style="margin:auto; margin-top: 3%; width: 90%;" >AGGIUNGI GARA</a><br>

    <div id="outputJQ"><!--/Output JQUERY.../-->

        <div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
            <table align="center" style="width: 90%;" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border">
                <tr class="w3-green">
                    <th>Luogo</th>
                    <th>Data</th>
                </tr>
                <?php
                $query = "SELECT * FROM gara ORDER BY gara.luogo ASC;";
                $ris = $conn->query($query);

                while($outGare = $ris->fetch_assoc()){
                    echo "
                <tr>
                    <td class='riga' href='profiloGara.php?garaID=".$outGare["ID"]."' onclick='infoGara(".$outGare["ID"].");'>".$outGare["luogo"]."</td>
                    <td class='riga' href='profiloGara.php?garaID=".$outGare["ID"]."' onclick='infoGara(".$outGare["ID"].");'>".$outGare["data"]."</td>
                    ";

                    echo "
                    <td><button onclick='animazioneModificaGara(".$outGare["ID"].");' class=\"w3-btn w3-ripple\"><img src='round_create_black_18dp.png'></button></td>
                    <td><button onclick='rimuoviGara(\"".$outGare["luogo"]."\",".$outGare["ID"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
                    ";

                    echo "</tr>";

                    /*HIDDEN --> slideDown click su matita modifica gara*/
                    echo "
                    <div method='post' action='gara.php'>
                    <tr style='display: none;' id='".$outGare["ID"]."'>
                            <td><input id='".$outGare["ID"]."luogo' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"nome\"  value=\"".$outGare["luogo"]."\"></td>
                            <td><input id='".$outGare["ID"]."data' class=\"w3-input w3-border w3-round\" type=\"date\" name=\"data\"  value=".$outGare["data"]."></td>";

                    echo "
                    <td><button class='w3-button w3-teal' onclick=\"modificaGara(".$outGare['ID'].", document.getElementById('".$outGare['ID']."luogo').value, document.getElementById('".$outGare['ID']."data').value);\">MODIFICA</button></td>
                    </tr>        
                    </div>
                ";
                }
                ?>
            </table>
        </div>

    </div><!--/Output JQUERY.../-->
</body>

</html>