<?php

// check if users are logged in
session_start();

include '../../includes/dbConnection.php';
$dbConn = getDatabaseConnection('loef');

$apiKey = "?api_key=465ea80f-a83e-4600-8cad-edaefc5f324c";
$apiCall = "https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/";

// remove spaces and make all lowercase
$normSN = strtolower(str_replace(' ','', $_POST['summonerName']));

$ch = curl_init($apiCall . $normSN . $apiKey);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$userData = array();
$userData = json_decode(curl_exec($ch), true);

$summonerID = $userData[$normSN]['id'];

function addUser(){
    global $dbConn, $summonerID;
    
    $sql = "INSERT INTO users
            (username, password, summonerName, summonerID)
            VALUES
            (:username, :password, :summonerName, :summonerID)";
            
    $namedParameters = array();
    $namedParameters[':username']       = $_POST['username'];
    $namedParameters[':password']       = sha1($_POST['password']);
    $namedParameters[':summonerName']   = $_POST['summonerName'];
    $namedParameters[':summonerID']     = $summonerID;
    
    $stmt = $dbConn->prepare($sql);
    $stmt->execute($namedParameters);
}

if(isset($_POST['addUser'])){
    addUser();
    echo "User was added!";
    if(!isset($_SESSION['username']))
        $_SESSION['username'] = $_POST['username'];
    header('Location: ../userDash.php');
}

?>
