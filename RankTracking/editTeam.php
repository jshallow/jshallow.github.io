<?php

// for logins
session_start();

// for databases
include '../includes/dbConnection.php';
$conn = getDatabaseConnection("loef");

// for navbar
include 'includes/headerVisibility.php';

function getTeams(){
    global $conn, $record;
    
    $sql = "SELECT * 
            FROM teams";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $records = $stmt->fetchALL(PDO::FETCH_ASSOC);
    
    foreach($records as $record){
        echo "<option value='" . $record['teamID'] . "'>" . $record['teamName'] . "</option>";
    }
}

function updatePosition(){
    global $conn;
    
    $sql = "UPDATE teams
            SET " . $_POST['selectField'] . "='" . $_POST['summonerName'] . "' 
            WHERE teamID=" . $_POST['selectTeam'];
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

if(isset($_POST['update'])){
    updatePosition();
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>League of Progress</title>
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
            <form method="POST">
                Select Team: 
                <select name="selectTeam">
                    <option value = "">Select Team</option>
                    <?=getTeams()?>
                </select> <br />
                Select Position to Change: 
                <select name="selectField">
                    <option value = "top">Top</option>
                    <option value = "jungle">Jungle</option>
                    <option value = "mid">Mid</option>
                    <option value = "bot">Bot</option>
                    <option value = "support">Support</option>
                    <option value = "sub1">Sub 1</option>
                    <option value = "sub2">Sub 2</option>
                    <option value = "sub3">Sub 3</option>
                </select> <br />
                Input New Player:
                <input type="text" name="summonerName" />
                <br />
                <input type="submit" name="update" value="Update" />
            </form>
        </main>
    </body>
    
    <div id="footer"></div>
</html>