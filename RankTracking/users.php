<?php

// for logins
session_start();

// for databases
include '../includes/dbConnection.php';
$conn = getDatabaseConnection("loef");

function getAvailableRanks(){
    global $conn;
    
    $sql = "SELECT DISTINCT(lastRank) 
            FROM users 
            ORDER BY lastRank";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchALL(PDO::FETCH_ASSOC);
    
    foreach($records as $record){
        if($record['lastRank'] != NULL)
            echo "<option value='" . $record['lastRank'] . "'>" . $record['lastRank'] . "</option>";
    }
}

function getUsers(){
    global $conn;
    $sql = "SELECT userID, username, summonerName, lastRank
                FROM users
                WHERE 1";
    if(isset($_GET['submit'])){
        $namedParameters = array();
        
        if(!empty($_GET['selectRank'])){
            // uses named parameters to prevent SQL Injection
            $sql .= " AND lastRank LIKE :lastRank ";
            $namedParameters[':lastRank'] = "%" . $_GET['selectRank'] . "%";
        }
    }
    
    
    $statement = $conn->prepare($sql);
    $statement->execute($namedParameters);
    $record = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div class='userTable'>";
    echo "<table class='table table-bordered table-striped'>";
    echo "<tr><th>Username</th><th>Summoner Name</th><th>Rank</th>";
    for($i = 0; $i < count($record); $i++){
        echo "<tr>";
        echo "<td>" . $record[$i]['username'] . "</td>";
        echo "<td>" . $record[$i]['summonerName'] . "</td>";
        echo "<td>" . $record[$i]['lastRank'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Users - League of Progress</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/custom.css" />
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        
        <script>
            $(function(){
              $("#header").load("includes/header.php"); 
              $("#footer").load("includes/footer.php"); 
            });
        </script>
    </head>
    
    <body>
        <div id="header"></div>
        
        <main>
            <h2>Users</h2>
            
            <form>
                Filter by Rank: 
                <select name="selectRank">
                    <option value = "">Select Option</option>
                    <?=getAvailableRanks()?>
                </select>
                <input type="submit" name="submit" value="Search" />
            </form>
            
            <?=getUsers()?>
        </main>
    </body>
    
    <div id="footer"></div>
</html>