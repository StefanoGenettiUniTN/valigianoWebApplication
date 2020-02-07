<?php

    /**Da aggiungi utente al momento di inserimento della data consiglia la categoria opportuna
        con XML request
     */

require_once("DBconfig.php");

    // ottiene la data in input
    $data = $_REQUEST["data"];

    $hint = "null"; //null = nessun indizio valido trovato

    if ($data !== "") {
        $query = "SELECT ID FROM categoria WHERE ".$data." BETWEEN categoria.minAnno AND categoria.maxAnno;";
        $ris = $conn->query($query);

        if($outCategoria = $ris->fetch_assoc()){    //se trova una categoria valida
            $hint = $outCategoria["ID"];
        }
    }

    echo $hint;

?>