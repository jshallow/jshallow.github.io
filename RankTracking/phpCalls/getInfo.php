<?php

session_start();

include '/home/ubuntu/workspace/includes/dbConnection.php';
$conn = getDatabaseConnection("loef");

$userData = array();
$sumID;
$apiKey = "api_key=465ea80f-a83e-4600-8cad-edaefc5f324c";

// if the name was searched for, use the post
if(isset($_POST['summonerName'])){
    $sumName = $_POST['summonerName'];
    // get summonerID from RiotAPI
    $apiCall = "https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/";
    $ch = curl_init($apiCall . $_POST['summonerName'] . "?" . $apiKey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $userData = json_decode(curl_exec($ch), true);
    $sumID = $userData[strtolower($_POST['summonerName'])]['id'];
}
// else, use the current user
else{
    $sql = "SELECT summonerID 
        FROM users
        WHERE username = :username";

    $namedParameters = array();          
    $namedParameters[':username'] = $_SESSION['username'];  
    
    $statement = $conn->prepare($sql);
    $statement->execute($namedParameters);
    $record = $statement->fetch(PDO::FETCH_ASSOC);
    
    $sumID = $record['summonerID'];
}

// get ranked data using summonerID
$apiCall = "https://na.api.pvp.net/api/lol/na/v2.5/league/by-summoner/";
$ch = curl_init($apiCall . $sumID . "/entry?" . $apiKey);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$userData = json_decode(curl_exec($ch), true);

function printData(){
    global $userData, $sumID, $apiKey;
    
    if(isset($userData[$sumID])){
        
        echo "<div class='col-lg-4'>";
        echo "<h2>Solo Queue Ranked Stats</h2>";
        echo "<table class='table table-sm table-bordered table-striped'>";
        echo "<tr>
                <td style='width:200px; vertical-align:middle;' rowspan='7'><img src='images/tier_icons/" . strtolower($userData[$sumID][0]['tier']) . "_" . strtolower($userData[$sumID][0]['entries'][0]['division']) .".png' height='200px' /> </td>
                <td>Summoner Name</td>
                <td>" . $userData[$sumID][0]['entries'][0]['playerOrTeamName'] . "</td>
            </tr>
            <tr>
                <td>Rank</td>
                <td>" . getRank() . "</td>
            </tr>
            <tr>
                <td>Division</td>
                <td>" . $userData[$sumID][0]['name'] . "</td> 
            </tr>";
        checkSeries();
        calcWinRate();
        echo "</table>";
        echo "</div>";
    }
    else{
        // check if user is unranked
        $apiCall = "https://na.api.pvp.net/api/lol/na/v1.3/stats/by-summoner/";
        $ch = curl_init($apiCall . $sumID . "/summary?season=SEASON2016&" . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $userData = json_decode(curl_exec($ch), true);
        
        // if they're found, print out different data about them
        if(isset($userData['summonerId'])){
            // get summoner level
            https://na.api.pvp.net/api/lol/na/v1.4/summoner/20751185?api_key=465ea80f-a83e-4600-8cad-edaefc5f324c
            $apiCall = "https://na.api.pvp.net/api/lol/na/v1.4/summoner/";
            $ch = curl_init($apiCall . $sumID . "?" . $apiKey);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $sumLevel = json_decode(curl_exec($ch), true);
            
            for($i = 0; $i < count($userData['playerStatSummaries']); $i++){
                if($userData['playerStatSummaries'][$i]['playerStatSummaryType'] == 'Unranked'){
                    echo "<div class='soloQueueData'>";
                    echo "<table border='1'>";
                    echo "<tr><th colspan='3'>Player Stats</th></tr>";
                    echo "<tr>
                            <td rowspan='6'><img src='images/tier_icons/unranked.png' height='100px' /> </td>
                            <td>Level</td>
                            <td>" . $sumLevel[$sumID]['summonerLevel'] . "</td>
                        </tr>
                        <tr>
                            <td>Normal Wins</td>
                            <td>" . $userData['playerStatSummaries'][4]['wins'] . "</td> 
                        </tr>
                        <tr>
                            <td>Total Champion Kills</td>
                            <td>" . $userData['playerStatSummaries'][4]['aggregatedStats']['totalChampionKills'] . "</td>
                        </tr>
                        <tr>
                            <td>Total Minion Kills</td>
                            <td>" . $userData['playerStatSummaries'][4]['aggregatedStats']['totalMinionKills'] . "</td>
                        </tr>
                        <tr>
                            <td>Total Jungle Creeps Killed</td>
                            <td>" . $userData['playerStatSummaries'][4]['aggregatedStats']['totalNeutralMinionsKilled'] . "</td>
                        </tr>
                        <tr>
                            <td>Total Towers Destroyed</td>
                            <td>" . $userData['playerStatSummaries'][4]['aggregatedStats']['totalTurretsKilled'] . "</td>
                        </tr>";
                    echo "</table>";
                    echo "</div>";
                }
            }
        }
        else
            echo "Summoner does not exist!";
    }
}

function getRank(){
    global $userData, $sumID;
    
    if(isset($userData[$sumID]))
        return $userData[$sumID][0]['tier'] . " " . $userData[$sumID][0]['entries'][0]['division'];
    else 
        return "UNRANKED";
}

function calcWinRate(){
    global $userData, $sumID;
    
    $wins = $userData[$sumID][0]['entries'][0]['wins'];
    $losses = $userData[$sumID][0]['entries'][0]['losses'];
    
    echo "<tr>
            <td>Wins</td>
            <td>" . $userData[$sumID][0]['entries'][0]['wins'] . "</td>
        </tr>
        <tr>
            <td>Losses</td>
            <td>" . $userData[$sumID][0]['entries'][0]['losses'] . "</td>
        </tr>
        <tr>
            <td>W/L %</td>";
        // avoid dividing by 0
        if($wins + $losses == 0)  
            echo "<td>No Games</td>";
        else
            echo "<td>" . round($userData[$sumID][0]['entries'][0]['wins'] / ($userData[$sumID][0]['entries'][0]['wins'] + $userData[$sumID][0]['entries'][0]['losses']), 4) * 100 . "%</td>";
    
    echo "</tr>";
}

function checkSeries(){
    global $userData, $sumID;
    
    echo "<tr>";
    
    // if series is active
    if(isset($userData[$sumID][0]['entries'][0]['miniSeries'])){
        echo "<td>Series Progress</td>
            <td>" . $userData[$sumID][0]['entries'][0]['miniSeries']['progress'] . "</td>";
    }
    else
        echo "<td>League Points</td>
            <td>" . $userData[$sumID][0]['entries'][0]['leaguePoints'] . "</td>";
        
    echo "</tr>";
}
?>