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

<script>
    /*#TODO NON ANCORA USATO NELLA PAGINA modificaSocieta.php*/
    /*Script per collegamento ipertestuale click sulla riga della tabella*/
    /*
    $(document).ready(function(){
        $('.riga').click(function(){
            window.location = $(this).attr('href');
            return false;
        });
    });
    */
</script>

<body>
<a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
<div class="w3-card w3-margin w3-round">
    <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 8%;">Pagina di modifica societ&agrave</h2>
</div>

<a href="aggiungiSocieta.php" class="w3-ripple w3-teal w3-button w3-block w3-centered" style="margin: auto; width: 90%;" >AGGIUNGI SOCIETA'</a><br>

<div class="w3-responsive"><!--Scroll bar se schermata troppo piccola-->
    <table align="center" style="width: 90%;" class="w3-table w3-striped w3-centered w3-large w3-hoverable w3-border">
        <tr class="w3-green">
            <th>Nome Societ&agrave</th>
            <th>Sede</th>
        </tr>

        <?php
        $query = "SELECT * FROM societa;";
        $ris = $conn->query($query);

        while($outSocieta = $ris->fetch_assoc()){
            /*href='profiloSocieta.php?userID=".$outSocieta['ID']."'*/
            echo "
                <tr class='riga'>
                    <td>".$outSocieta["nome"]."</td>
                    <td>".$outSocieta["sede"]."</td>
                    ";
            echo "</tr>";
        }
        ?>
    </table>
</div>

</body>

</html>