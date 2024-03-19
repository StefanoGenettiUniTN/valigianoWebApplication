<?php
session_start();
require_once("DBconfig.php");
?>

<?php
    if(isset($_GET["catID"])){
?>

<?php

$idCat = $_GET["catID"];

/**Cancello dalla tabella Categorie la categoria in questione*/
$deleteQuery = "DELETE FROM categoria WHERE categoria.ID = ".$idCat.";";
$risultato = $conn->query($deleteQuery);
/**.....*/

/**Aggiorno pagina*/
echo "<div class=\"w3-responsive\"><!--Scroll bar se schermata troppo piccola-->
    <table align=\"center\" style=\"width: 70%; margin-left: 5%;\" class=\"w3-table w3-striped w3-centered w3-large w3-hoverable w3-border\">
        <tr class=\"w3-green\">
            <th>Nome Categoria</th>
            <th>Punteggio di partenza</th>
            <th>Anno minimo</th>
            <th>Anno massimo</th>
        </tr>";

        $query = "SELECT * FROM categoria ORDER BY nome ASC;";
        $ris = $conn->query($query);

        while($outCategoria = $ris->fetch_assoc()){
            /*href='profiloCategoria.php?userID=".$outCategoria['ID']."'*/
            echo "
                <tr class='riga'>
                    <td>".$outCategoria["nome"]."</td>
                    <td>".$outCategoria["tetto"]."</td>
                    <td>".$outCategoria["minAnno"]."</td>
                    <td>".$outCategoria["maxAnno"]."</td>
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
                            <td><input id='".$outCategoria["ID"]."tetto' class=\"w3-input w3-border w3-round\" type=\"number\" name=\"tetto\"  value='".$outCategoria["tetto"]."'></td>
                            <td><input id='".$outCategoria["ID"]."minAnno' class=\"w3-input w3-border w3-round\" type=\"number\" name=\"minAnno\"  value='".$outCategoria["minAnno"]."'></td>
                            <td><input id='".$outCategoria["ID"]."maxAnno' class=\"w3-input w3-border w3-round\" type=\"number\" name=\"maxAnno\"  value='".$outCategoria["maxAnno"]."'></td>";
            echo "
                    <td><button class='w3-button w3-teal' onclick=\"modificaCategoria(".$outCategoria['ID'].", document.getElementById('".$outCategoria['ID']."nome').value, document.getElementById('".$outCategoria['ID']."tetto').value, document.getElementById('".$outCategoria['ID']."minAnno').value, document.getElementById('".$outCategoria['ID']."maxAnno').value);\">MODIFICA</button></td>
                    </tr>        
                    </div>
                ";
        }

echo "
        </table>
        </div>
    ";
?>

<?php
}else{
    echo "<script>alert(')-: Errore. Contattare l\'amministratore di sistema.');</script>";  //#TODO Error page
}
?>
