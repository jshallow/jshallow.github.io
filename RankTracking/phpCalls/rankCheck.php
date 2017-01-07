<?php
session_start();

include 'getInfo.php';
include '/home/ubuntu/workspace/includes/dbConnection.php';
$conn = getDatabaseConnection("loef");

$sql = "SELECT lastRank 
        FROM users
        WHERE username = :username";

$namedParameters = array();          
$namedParameters[':username'] = $_SESSION['username'];  

$statement = $conn->prepare($sql);
$statement->execute($namedParameters);
$record = $statement->fetch(PDO::FETCH_ASSOC);

function getLastRank(){
    global $record;
    return $record['lastRank'];
}

function getLastRankInt(){
    global $conn;
    
    $sql = "SELECT lastRankInt
            FROM users
            WHERE username = :username";

    $namedParameters = array();          
    $namedParameters[':username'] = $_SESSION['username'];  

    $statement = $conn->prepare($sql);
    $statement->execute($namedParameters);
    $r = $statement->fetch(PDO::FETCH_ASSOC);
    
    return $r['lastRankInt'];
}

function updateRank(){
    global $conn;
    $rank = getRank();
    //if(!isset($rank))
    //    $rank = "Unranked";
    //echo "<br /><br /> RANK: " . getRank() . "<br />";
    $sql = "UPDATE users 
            SET lastRank='" . $rank . "', lastRankInt='" . rankToInt($rank) . "' 
            WHERE username='" . $_SESSION['username'] . "'";
    $statement = $conn->prepare($sql);
    $statement->execute($namedParameters);
}

function addProgress(){
    global $conn;
    
    // check if data has been inserted or needs to just be updated
    $sql = "SELECT *
            FROM rankedProgress
            WHERE username='" . $_SESSION['username'] . "' 
            AND date=CURDATE()";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(!empty($record)){
        $sql = "UPDATE rankedProgress
                SET rank='" . getRank() ."'
                WHERE username='" . $_SESSION['username'] . "'
                AND date=CURDATE()";
            
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
    else{
        $sql = "INSERT INTO rankedProgress
                (username, rank)
                VALUES
                (:username, :rank)";
                
        $namedParameters = array();
        $namedParameters[':username']       = $_SESSION['username'];
        $namedParameters[':rank']           = getRank();
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($namedParameters);
    }
}

function rankToInt($rank){
    
    if($rank == "UNRANKED"){
        return 0;
    }
    
    $div = substr($rank, 0, strpos($rank, " "));
    $tier = substr($rank, strpos($rank, " ") + 1, strlen($rank));
    
    $num = 0;
    switch($div){
        case "BRONZE":
            $num += 0;
            break;
        case "SILVER":
            $num += 5;
            break;
        case "GOLD":
            $num += 10;
            break;
        case "PLATINUM":
            $num += 15;
            break;
        case "DIAMOND":
            $num += 20;
            break;
        case "MASTER":
            $num += 25;
            break;
        case "CHALLENGER":
            $num += 26;
            break;
    }
    switch($tier){
        case "I":
            $num += 4;
            break;
        case "II":
            $num += 3;
            break;
        case "III":
            $num += 2;
            break;
        case "IV":
            $num += 1;
            break;
        case "V":
            $num += 0;
            break;
        
    }
    return $num;
}

function intToRank($num){
    if($num == 0){
        return "No one is ranked.";
    }
    
    $rank;
    
    if($num == 26)
        return "CHALLENGER";
    if($num == 25)
        return "MASTER";
    else if($num >= 20)
        $rank = "DIAMOND";
    else if($num >= 15)
        $rank = "PLATINUM";
    else if($num >= 10)
        $rank = "GOLD";
    else if($num >= 5)
        $rank = "SILVER";
    else
        $rank = "BRONZE";
        
    switch($num % 5){
        case 0:
            $rank .= " V";
            break;
        case 1:
            $rank .= " IV";
            break;
        case 2:
            $rank .= " III";
            break;
        case 3:
            $rank .= " II";
            break;
        case 4:
            $rank .= " I";
            break;
    }
    
    return $rank;
}

?>
