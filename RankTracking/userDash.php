<?php
// for logins
session_start();

// for databases
include '../includes/dbConnection.php';
$conn = getDatabaseConnection("loef");
// for functions
include 'includes/headerVisibility.php';
include 'phpCalls/rankCheck.php';
include 'phpCalls/displayProgress.php';

function welcomeUser(){
    global $record;
    $curRank = getRank();
    $lastRank = getLastRank();
    $rankChange = $curRank - $lastRank;
    
    if(getLastRankInt() == -1){
        echo "Welcome, ";
        echo "it looks like this is your first time logging in!<br />";
        echo "Your current rank is " . $curRank . ".<br />";
    }
    else{
        echo "Welcome back! ";
        echo "Last time you logged in, your rank was " . $lastRank . ".<br />"; 
        if($rankChange == 0)
            echo "Your rank has not changed.<br />";
        else{
            echo "You are now " . $curRank . ", which means you have gone" .
                ($rankChange > 1 ? "up" : "down") . $rankChange . "divisions.";
        }
    }
    echo "<br />";
    printData();
    printProgress();
    
    // update rank on login, store it in rankedProgress
    if(!$_SESSION['updatedRank']){
        updateRank();
        addProgress();
        $_SESSION['updatedRank'] = true;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>User Dashboard - League of Progress</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/custom.css" />
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        
        <script>
            // for header and footer 
            $(function(){
              $("#header").load("includes/header.php"); 
              $("#footer").load("includes/footer.php"); 
            });
        </script>
    </head>
    
    <body>
        <div id="header"></div>
        
        <main>
            <?=welcomeUser()?>
        </main>
    
        <div id="footer"></div>
    </body>
</html>