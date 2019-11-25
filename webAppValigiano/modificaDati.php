<html>

    <head>
        <title>Valigiano Web Application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="w3.css">

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
            <h2 class="w3-margin w3-container" style="text-shadow:1px 1px 0 #444; height: 8%;">Modifica base di dati</h2>
        </div>

        <a href="modificaUtente.php"><div class="w3-card w3-padding w3-margin w3-hover-shadow w3cardPadre">
            <div style = "height: 15%; width: 10%;" class="w3-center w3-display-container w3-margin-left w3-padding w3cardFiglio">
                <img style = "height: 100%; width: 100%;" src = "newUser3.png" alt = "modifica utente" >
            </div>
            <h5 style="font-family: 'myFont'; font-size: x-large;" class="w3cardFiglio w3-margin-left">Modifica dati <i>utente</i></h5>
        </div></a>
        <hr>
        <a href="modificaCategoria.php"><div class="w3-card w3-padding w3-margin w3-hover-shadow w3cardPadre">
            <div style = "height: 15%; width: 10%;" class="w3-center w3-display-container w3-margin-left w3-padding w3cardFiglio" >
                <img style = "height: 100%; width: 100%;" src = "categoria.jpg" alt = "modifica categoria" >
            </div >
            <h5 style="font-family: 'myFont'; font-size: x-large;" class="w3cardFiglio w3-margin-left">Modifica dati <i>categoria</i></h5>
        </div></a>
        <hr>
        <a href="modificaSocieta.php"><div class="w3-card w3-padding w3-margin w3-hover-shadow w3cardPadre">
                <div style = "height: 15%; width: 10%;" class="w3-center w3-display-container w3-margin-left w3-padding w3cardFiglio" >
                    <img class="w3-round-xxlarge" style = "height: 100%; width: 100%;" src = "societa4.jpg" alt = "modifica societa" >
                </div >
                <h5 style="font-family: 'myFont'; font-size: x-large;" class="w3cardFiglio w3-margin-left">Modifica dati <i>societ&agrave</i></h5>
        </div></a>


        <!-- Footer -->
        <?php include("footerLayout.php");?>

    </body>

</html>
