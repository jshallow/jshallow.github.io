<?php

// for logins
session_start();

// for database
include '../../includes/dbConnection.php';
$conn = getDatabaseConnection("loef");

// api call stuff
$sumID;
$apiCall;
$apiKey = "?api_key=465ea80f-a83e-4600-8cad-edaefc5f324c";

if(isset($_GET['callType'])){
    global $sumID, $apiKey;
    
    if($_GET['callType'] == "rankedBySearch"){
        $apiCall = "https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/";
        
        $ch = curl_init($apiCall . $_GET['summonerName'] . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $userData = array();
        $userData = json_decode(curl_exec($ch), true);
        
        $sumID = $userData[strtolower($_POST['summonerName'])]['id'];
        
        getRankedInfo();
    } 
    else if($_GET['callType'] == "rankedDashboard"){
        $sql = "SELECT summonerID
                FROM users
                WHERE username = :username";
        $statement = $conn->prepare($sql);
        $statement->execute(array(":username"=>$_SESSION['username']));
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        $sumID = $result['summonerID'];
        getRankedInfo();
    }
}

function getRankedInfo(){
    global $apiCall, $summonerID;
    
    $apiCall = "https://na.api.pvp.net/api/lol/na/v2.5/league/by-summoner/";
    toJson();
}

function toJson(){
    global $apiCall, $sumID, $apiKey;
    
    $ch = curl_init($apiCall . $sumID . $apiKey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $userData = array();
    $userData = json_decode(curl_exec($ch), true);
    
    echo json_decode(curl_exec($ch), true);
}

?>