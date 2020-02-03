<?php
session_start();
require_once("DBconfig.php");
require_once("function.php");
?>

<?php

if(isset($_POST["id"]) && isset($_POST["name"]) && isset($_POST["tetto"])){
    $catID = $_POST['id'];
    $nome = $_POST['name'];
    $tetto = $_POST['tetto'];

    //Query
    $updateQuery  = "UPDATE categoria SET nome = '".$nome."', tetto = ".$tetto." WHERE ID = ".$catID.";";
    $risultato = $conn->query($updateQuery);

    /**Aggiorno pagina*/
    echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
    <table align=\"center\" style=\"width: 60%; margin-left: 5%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border\">
        <tr class=\"w3-green\">
            <th>Nome Categoria</th>
            <th>Punteggio di partenza</th>
        </tr>";

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
                    <td><button onclick='animazioneModificaCategoria(".$outCategoria["ID"].");' class=\"w3-btn w3-ripple\"><img src='round_create_black_18dp.png'></button></td>
                    <td><button onclick='rimuoviCategoria(\"".$outCategoria["nome"]."\",".$outCategoria["ID"].");' class=\"w3-btn w3-ripple\"><img src='baseline_delete_black_18dp.png'></button></td>
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

    echo "
        </table>
        </div>
    ";
}
?>
