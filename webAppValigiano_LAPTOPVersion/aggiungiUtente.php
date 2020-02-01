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
        <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 10%;">Inserimento nuovo utente</h2>
    </div>

    <div class="w3-card w3-margin w3-round">
    <form class="w3-container w3-card-4 w3-round" action="aggiungiUtente.php" method="POST">
        <p style="margin-bottom: 2.5%;">
            <input class="w3-input w3-animate-input" type="text" name="nome" style="width:90%" required>
            <label class="w3-text-teal"><b>Nome</b></label></p>
        <p style="margin-bottom: 2.5%;">
            <input class="w3-input w3-animate-input" type="text" name="cognome" style="width:90%" required>
            <label class="w3-text-teal"><b>Cognome</b></label></p>
        <p style="margin-bottom: 2.5%;">
            <input class="w3-input w3-animate-input" type="date" name="data_nascita" style="width:90%" required>
            <label class="w3-text-teal"><b>Data di nascita</b></label></p>
        <p>
            <label class="w3-margin-right w3-text-teal"><b>Sesso</b></label>
            M <input class="w3-radio w3-margin-right" type="radio" name="gender" value="male">
            F <input class="w3-radio" type="radio" name="gender" value="female">
            </p>
        <p style="margin-bottom: 2.5%;">
            <input style="width:90%" class="w3-input w3-animate-input" type="number" name="pettorina" required> <!--min="1" max="5"-->  <!--#TODO MAX and MIN range-->
            <label class="w3-text-teal"><b>Pettorina</b></label></p>

        <p style="margin-bottom: 2.5%;"><select class="w3-select w3-animate-input" name="societa" style="width:90%" required>
                <option value="" disabled selected>...nessuna societ&agrave selezionata...</option>
                <?php
                $query = "SELECT id, nome FROM societa;";
                $ris = $conn->query($query);
                while($societa = $ris->fetch_assoc()) {
                    echo "<option value='".$societa["id"]."'>".$societa["nome"]."</option>";
                }
                ?>
            </select><br>
            <label class="w3-text-teal"><b>Societ&agrave</b></label></p>

        <p style="margin-bottom: 2.5%;"><select class="w3-select w3-animate-input" name="categoria" style="width:90%" required>
                <option value="" disabled selected>...nessuna categoria selezionata...</option>
                <?php
                $query = "SELECT id, nome FROM categoria;";
                $ris = $conn->query($query);
                while($categoria = $ris->fetch_assoc()) {
                    echo "<option value='".$categoria["id"]."'>".$categoria["nome"]."</option>";
                }
                ?>
            </select><br>
            <label class="w3-text-teal"><b>Categoria</b></label></p>

        <p style="margin-bottom: 2.5%;">
            <input style="float: left;" class="w3-button w3-section w3-teal w3-ripple" type="submit" name="submit" value="REGISTRA"></p>
            <a style="float: right; margin-right: 10%;" href="modificaUtente.php" class="w3-margin-top">Torna alla lista utenti</a>
            <div style="clear:both; font-size:1px;"></div>
    </form>
    </div>

    <div class="w3-margin-left w3-margin-right w3-container w3-card w3-round" id="panel" style="display: none;"><h4 class="w3-margin">Operazione avvenuta con successo</h4></div>

</body>

</html>

<?php

if(isset($_POST["submit"])){
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $data = $_POST['data_nascita'];
    $sesso = $_POST['gender'];
    $pettorina = $_POST['pettorina'];
    $societa = $_POST['societa'];
    $categoria = $_POST['categoria'];

    //Controlla se l'utente è gia presente
    if(!userExists($nome, $cognome)){
        if(!is_int($pettorina)) {    //controlla se il numero di pettorina è un numero intero
            if (!pettorinaExists($pettorina)) {
                //Query
                $insertQuery = "INSERT INTO utente (nome, cognome, data_nascita, sesso, n_pettorina, id_societa, id_categoria) VALUES ( '" . $nome . "', '" . $cognome . "', '" . $data . "', '" . strtoupper($sesso) . "', '" . $pettorina . "', '" . $societa . "', '" . $categoria . "');";
                if (!($ris = $conn->query($insertQuery))) {
                    echo "<script>alert(\"Errore in fase di inserimento dati.\");</script>";
                }
            } else {
                /**Se la pettorina è gia presente*/
                echo "<script>alert(\"Valore inserito non valido - Numero di pettorina gia presente.\");</script>";
            }
        }else{
            echo "<script>alert(\"Valore inserito non valido - Numero di pettorina in formato non valido.\");</script>";
        }
    }
    else{
        echo "<script>alert(\"Errore, utente già presente nel DB\");</script>";
    }
}

?>