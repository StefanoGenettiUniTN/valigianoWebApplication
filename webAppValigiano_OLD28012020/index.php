<!DOCTYPE html>
<html>

    <head>
        <title>Valigiano Web Application</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="w3.css">
    </head>
    <style>
        h1,h2,h3,h4,h5,h6 {font-family: "Oswald"}
        body {

            font-family: "Open Sans";

            background-image: url("immagini/jogging-2343558_1920.jpg");
            background-repeat: no-repeat;
            background-attachment: scroll;
            background-position: center;
        }

        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 33.33%;
            padding: 5px;
        }

        .row::after {
            content: "";
            clear: both;
            display: table;
        }

    </style>
<body class="w3-light-grey w3-animate-opacity">

    <div class="w3-content" style = "max-width:1600px" >

        <!--Header -->
        <header class="w3-container w3-center w3-padding-48 w3-white w3-opacity-min">
            <h1 class="w3-xxlarge"><b> Web Application Valigiano </b></h1 >
            <h6> Sviluppato da <span class="w3-tag w3-teal"> Stefano Genetti </span></h6 >
        </header >

        <div class="row w3-margin w3-margin-top w3-center" >
                <div style = "height: 350px; width: 33%;" class="w3-hover-shadow w3-center w3-display-container column" >
                    <a href = "modificaDati.php" ><img style = "height: 100%; width: 100%;" src = "immagini/social-media-2786261_1920.jpg" class="w3-round-medium" alt = "modifica" >
                        <div class="w3-panel w3-blue-grey" >
                            <h2 class="w3-display-middle" style = "background-color: LightGray; width: 90%; color: white; text-shadow:1px 1px 0 #444;" > MODIFICA DATI </h2 ></a >
                        </div >
                </div >

                <div style = "height: 350px; width: 33%;" class="w3-hover-shadow w3-center w3-display-container column" >
                    <a href = "gara.php" ><img style = "height: 100%; width: 100%;" src = "immagini/relay-race-655353_1920.jpg" class="w3-round-medium" alt = "gara" >
                        <div class="w3-panel w3-blue-grey" >
                            <h2 class="w3-display-middle" style = "background-color: LightGray; width: 90%; color: white; text-shadow:1px 1px 0 #444;" > GARA </h2 ></a >
                         </div >
                </div >

                <div style = "height: 350px; width: 33%;" class="w3-hover-shadow w3-center w3-display-container column" >
                    <a href = "classifica.php" ><img style = "height: 100%; width: 100%;" src = "immagini/trophy-1674911_1280.png" class="w3-round-medium" alt = "classifica" >
                        <div class="w3-panel w3-blue-grey" >
                            <h2 class="w3-display-middle" style = "background-color: LightGray; width: 90%; color: white; text-shadow:1px 1px 0 #444;" > CLASSIFICA </h2 ></a >
                        </div >
                </div >
        </div >
    <!--END w3 - content-->
    </div >

</body>
</html>
