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