<?php

    /**Connessione al database SQL*/

    $nomeDB = "dbvaligiano";
    $user = "stefanogenetti";
    $host = "localhost";
    $password = "password";

    // Create connection
    $conn = new mysqli($host, $user, $password, $nomeDB);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

?>
