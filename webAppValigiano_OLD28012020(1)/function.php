<?php
/**
 * Funzioni utili
 */

function userExists($_nome, $_cognome){
    global $conn;
    $sql = "SELECT * FROM utente WHERE nome = '$_nome' AND cognome = '$_cognome' ;";
    if(($conn->query($sql))->num_rows>=1) return true;
    else return false;
}

function pettorinaExists($_pettorina){
    global $conn;
    $sql = "SELECT * FROM utente WHERE n_pettorina=".$_pettorina.";";
    if(($conn->query($sql))->num_rows>=1) return true;
    else return false;
}

function categoriaExists($_nome){
    global $conn;
    $sql = "SELECT * FROM categoria WHERE nome = '$_nome';";
    if(($conn->query($sql))->num_rows>=1) return true;
    else return false;
}

function societaExists($_nome){
    global $conn;
    $sql = "SELECT * FROM societa WHERE nome = '$_nome';";
    if(($conn->query($sql))->num_rows>=1) return true;
    else return false;
}

function garaExists($_luogo, $_data){
    global $conn;
    $sql = "SELECT * FROM gara WHERE luogo = '$_luogo' AND data = '$_data';";
    if(($conn->query($sql))->num_rows>=1) return true;
    else return false;
}

/**Funzione usata in agggiungi iscritto gara

 * Controlla se nell'array passato in input l'ID è contenuto

 */
function isRegistered($_arrayIscritti, $_userId){
    foreach ($_arrayIscritti as $utente) {
        if($utente == $_userId)
            return true;
    }
    return false;
}

/**Aggiunta 27 gennaio 2020 dopo aver scoperto che gli utenti sono da subito iscritti a tutte le gare*/
/**Vengono iscritti tutti gli utenti alla gara passata in input*/
function inserisciIscritti($garaID){
    global $conn;
    //Prendo ID di tutti gli utenti
    $getUserQuery = "SELECT ID FROM utente;";
    $resultGetUserQuery = $conn->query($getUserQuery);
    //Popolo classifica con id utenti
    while($outGetUserQuery = $resultGetUserQuery->fetch_assoc()){
        $insertQuery  = "INSERT INTO classifica (id_utente, id_gara, punteggio) VALUES (".$outGetUserQuery["ID"].", ".$garaID.", 0);";
        if(!$risultato = $conn->query($insertQuery)){
            echo "<script>alert(\"Err\");</script>";
        }
    }
}

/**Restituisce nome gara da ID gara*/
function getRaceName($_garID){
    global $conn;
    $sql = "SELECT luogo FROM gara WHERE id=".$_garID.";";
    $resultSql = $conn->query($sql);
    if($outSql = $resultSql->fetch_assoc())
        return $outSql["luogo"];
}