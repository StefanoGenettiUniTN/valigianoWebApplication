<html>

<head>
    <title>Valligiano Web Application</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="w3.css">
    <link rel="icon" href="immagini/logo.png" type="image/gif" sizes="16x16">

    <style>

        /**Gestione w3card stampa elementi stessa riga*/
        .w3cardPadre {
            list-style: none;
            white-space: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .w3cardFiglio {
            display: inline-block;
        }

        @font-face {
            font-family: myFont;
            src: url("Calistoga/Calistoga-Regular.ttf");
        }

        a {
            text-decoration: inherit;
            color: inherit;
            cursor: auto;
        }

    </style>

</head>

<body>
<a href="index.php" style="text-decoration: none;"><button class="w3-button w3-margin w3-teal w3-display-topright" style="height: 8%;"><i style="margin-right: 30px;"><img src="homeGrande.png"></i>Torna alla home</button></a>
<div class="w3-card w3-margin w3-round">
    <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 8%;">Classifica campionato Valigiano</h2>
</div>

<a href="classificaTotale.php"><div class="w3-card w3-padding w3-margin w3-hover-shadow w3cardPadre">
        <div style = "height: 15%; width: 15%;" class="w3-center w3-display-container w3-margin-left w3-padding w3cardFiglio">
            <img style = "height: 100%; width: 100%;" src = "immagini/winner-1013979_1920.jpg" alt = "modifica utente" class="w3-round-large">
        </div>
        <h5 style="font-family: 'myFont'; font-size: x-large;" class="w3cardFiglio w3-margin-left">CLASSIFICA COMPLESSIVA</h5>
    </div></a>
<hr>
<a href="classificaSingolaGara.php"><div class="w3-card w3-padding w3-margin w3-hover-shadow w3cardPadre">
        <div style = "height: 15%; width: 15%;" class="w3-center w3-display-container w3-margin-left w3-padding w3cardFiglio">
            <img style = "height: 100%; width: 100%;" src = "immagini/stopwatch-259303_1920.jpg" alt = "modifica utente" class="w3-round-large">
        </div>
        <h5 style="font-family: 'myFont'; font-size: x-large;" class="w3cardFiglio w3-margin-left">CLASSIFICA SINGOLA GARA</h5>
    </div></a>
<hr>
<a href="classificaSocieta.php"><div class="w3-card w3-padding w3-margin w3-hover-shadow w3cardPadre">
        <div style = "height: 15%; width: 15%;" class="w3-center w3-display-container w3-margin-left w3-padding w3cardFiglio" >
            <img style = "height: 100%; width: 100%;" src = "immagini/paper-3213924_1920.jpg" alt = "modifica categoria" class="w3-round-large">
        </div >
        <h5 style="font-family: 'myFont'; font-size: x-large;" class="w3cardFiglio w3-margin-left">CLASSIFICA SOCIETA'</h5>
    </div></a>
<hr>
<a href="classificaFamiglia.php"><div class="w3-card w3-padding w3-margin w3-hover-shadow w3cardPadre">
        <div style = "height: 15%; width: 15%;" class="w3-center w3-display-container w3-margin-left w3-padding w3cardFiglio" >
            <img style = "height: 100%; width: 100%;" src = "immagini/holding-hands-918990_1920.jpg" alt = "modifica categoria" class="w3-round-large">
        </div >
        <h5 style="font-family: 'myFont'; font-size: x-large;" class="w3cardFiglio w3-margin-left">CLASSIFICA FAMIGLIA</h5>
    </div></a>
<hr>

</body>
</html>
