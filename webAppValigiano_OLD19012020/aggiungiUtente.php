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

<body>
    <a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
    <div class="w3-card w3-margin w3-round">
        <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 8%;">Inserimento nuovo utente</h2>
    </div>

    <div class="w3-card w3-margin w3-round">
    <form class="w3-container w3-card-4 w3-round" action="register.php" method="POST">
        <p>
            <input class="w3-input" type="text" name="nome" style="width:90%" required>
            <label>Nome</label></p>
        <p>
            <input class="w3-input" type="text" name="cognome" style="width:90%" required>
            <label>Cognome</label></p>
        <p>
            <input class="w3-input" type="date" name="data_nascita" style="width:90%" required>
            <label>Data di nascita</label></p>
        <p>
            <input class="w3-button w3-section w3-teal w3-ripple" type="submit" name="submit" value="REGISTRA"></p>

    </form>
    </div>

    <div class="w3-margin-left w3-margin-right w3-container w3-card w3-round" id="panel" style="display: none;"><h4 class="w3-margin">Operazione avvenuta con successo</h4></div>

</body>

</html>