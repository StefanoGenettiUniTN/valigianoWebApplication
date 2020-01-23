<?php
session_start();
require_once("DBconfig.php");
require_once("function.php");
?>

<?php

if(isset($_POST["id"]) && isset($_POST["place"]) && isset($_POST["date"])){
    $garID = $_POST['id'];
    $luogo = $_POST['place'];
    $data = $_POST['date'];

    //Query
    $updateQuery  = "UPDATE gara SET luogo = '".$luogo."', gara.data = '".$data."' WHERE ID = ".$garID.";";
    $risultato = $conn->query($updateQuery);

    /**Aggiorno pagina*/
    echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
            <table align=\"center\" style=\"width: 90%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border\">
                <tr class=\"w3-green\">
                    <th>Luogo</th>
                    <th>Data</th>
                </tr>";

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
                            <td><input id='".$outGare["ID"]."luogo' class=\"w3-input w3-border w3-round\" type=\"text\" name=\"nome\"  value=".$outGare["luogo"]."></td>
                            <td><input id='".$outGare["ID"]."data' class=\"w3-input w3-border w3-round\" type=\"date\" name=\"data\"  value=".$outGare["data"]."></td>";

        echo "
                    <td><button class='w3-button w3-teal' onclick=\"modificaGara(".$outGare['ID'].", document.getElementById('".$outGare['ID']."luogo').value, document.getElementById('".$outGare['ID']."data').value);\">MODIFICA</button></td>
                    </tr>        
                    </div>
                ";
    }

    echo "
        </table>
        </div>
    ";
}
?>
