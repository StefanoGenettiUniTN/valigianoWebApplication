<?php
require_once("DBconfig.php");
include("function.php");
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

    <body>
    <a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 10%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
    <div class="w3-card w3-margin w3-round">
        <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 10%;">Inserimento nuova categoria</h2>
    </div>

    <div class="w3-card w3-margin w3-round">
        <form class="w3-container w3-card-4 w3-round" action="aggiungiCategoria.php" method="POST">
            <p style="margin-bottom: 2.5%;">
                <input class="w3-input w3-animate-input" type="text" name="nome" style="width:90%" required>
                <label class="w3-text-teal"><b>Nome</b></label></p>
            <p style="margin-bottom: 2.5%;">
                <input style="float: left;" class="w3-button w3-section w3-teal w3-ripple" type="submit" name="submit" value="REGISTRA"></p>
            <a style="float: right; margin-right: 10%;" href="modificaCategoria.php" class="w3-margin-top">Torna alla lista delle categorie</a>
            <div style="clear:both; font-size:1px;"></div>
        </form>
    </div>

    <div class="w3-margin-left w3-margin-right w3-container w3-card w3-round" id="panel" style="display: none;"><h4 class="w3-margin">Operazione avvenuta con successo</h4></div>

    </body>

    </html>

<?php

if(isset($_POST["submit"])){
    $nome = $_POST['nome'];

    //Controlla se la categoria è gia presente #TODO invece che alert, scritta in rosso
    if(!categoriaExists($nome)){
        //Query
        $insertQuery  = "INSERT INTO categoria (nome) VALUES ( '".$nome."');";
        if(!($ris = $conn->query($insertQuery))){
            echo "<script>alert(\"Errore in fase di inserimento dati.\");</script>";
        }
    }
    else{
        echo "<script>alert(\"Errore, categoria già presente nel DB\");</script>";
    }
}

?>