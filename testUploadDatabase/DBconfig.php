<?php

    /**Connessione al database SQL*/

    $nomeDB = "dbvaligiano";
    $user = "root";
    $host = "localhost";
    $password = "";

    // Create connection
    $conn = new mysqli($host, $user, $password, $nomeDB);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

?>
