<?php
session_start();
require_once("DBconfig.php");
require_once("function.php");
?>

<?php

if(isset($_POST["id"]) && isset($_POST["name"])){
    $famID = $_POST['id'];
    $nome = $_POST['name'];

    //Query
    $updateQuery  = "UPDATE famiglia SET nome = '".$nome."' WHERE ID = ".$famID.";";
    $risultato = $conn->query($updateQuery);

    /**Aggiorno pagina*/
    echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
        <table style=\"width: 40%; margin-left: 5%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border\">
            <tr class=\"w3-green\">
                <th>Nome Famiglia</th>
            </tr>";

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

    echo "
        </table>
        </div>
    ";
}
?>
