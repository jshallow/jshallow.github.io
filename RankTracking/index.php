<?php

// for logins
session_start();

// for databases
include '../includes/dbConnection.php';
$conn = getDatabaseConnection("loef");

// for navbar
include 'includes/headerVisibility.php';

// for api calls
include 'phpCalls/getInfo.php';

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
                <fieldset>
                    <legend>Enter a Summoner to search!</legend>
                    <input type="text" name="summonerName" placeholder="e.g. Tryndamere" />
                    <input type="submit" name="searchSummoner" value="Search!"/>
                </fieldset>
            </form>
            <br />
            <?php
                if(isset($_POST['searchSummoner'])){
                    printData();
                }
            ?>
            <br />
            <a href="Documentation.pdf">Link to Documentation</a>
        </main>
    </body>
    
    <div id="footer"></div>
</html>