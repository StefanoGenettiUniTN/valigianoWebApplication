<?php
    require_once("DBconfig.php");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="w3.css">
    <title>Caricamento dati atleti</title>
</head>
<body>
<div class="w3-container">
<h1>Software caricamento dati</h1>
<form action="caricaDati.php" method="POST" enctype="multipart/form-data">
    <label for="societa" class="w3-text-teal"><b>Societ&agrave:</b></label><br>
    <select name="societa" class="w3-select w3-animate-input" style="width:50%" required>
        <option value="" disabled selected>...nessuna societ&agrave selezionata...</option>
        <?php
        $query = "SELECT id, nome FROM societa;";
        $ris = $conn->query($query);
        while($societa = $ris->fetch_assoc()) {
            echo "<option value='".$societa["id"]."'>".$societa["nome"]."</option>";
        }
        ?>
    </select><br><br>

    <label for="categoria" class="w3-text-teal"><b>Categoria:</b></label><br>
    <select name="categoria" class="w3-select w3-animate-input" style="width:50%" required>
        <option value="" disabled selected>...nessuna categoria selezionata...</option>
        <?php
        $query = "SELECT id, nome FROM categoria;";
        $ris = $conn->query($query);
        while($categoria = $ris->fetch_assoc()) {
            echo "<option value='".$categoria["id"]."'>".$categoria["nome"]."</option>";
        }
        ?>
    </select><br><br>

    <label for="file" class="w3-text-teal"><b>File:</b></label><br>
    <input type="file" name="fileToUpload" accept=".csv" required><br>

    <input type="submit" name="submit" class="w3-button w3-section w3-teal w3-ripple" value="Carica">
    <input type="reset" class="w3-button w3-section w3-pale-red w3-ripple" value="Reset">
</form>
</div>
</body>
</html>