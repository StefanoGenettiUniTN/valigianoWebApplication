<?php
session_start();
require_once("DBconfig.php");
?>


<?php

$idSoc = $_GET["socID"];

/**Cancello dalla tabella Societa la societa in questione*/
$deleteQuery = "DELETE FROM societa WHERE societa.ID = ".$idSoc.";";
$risultato = $conn->query($deleteQuery);
/**.....*/

/**Aggiorno pagina*/
echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
    <table style=\"width: 50%; margin-left: 5%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border\">
        <tr class=\"w3-green\">
            <th>Nome Societ&agrave</th>
            <th>Sede</th>
        </tr>";

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

echo "
        </table>
        </div>
    ";
?>
