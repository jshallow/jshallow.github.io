<?php

// for logins
session_start();
include 'phpCalls/rankCheck.php';

// for database
include '../includes/dbConnection.php';
$conn = getDatabaseConnection("loef");

function loggedIn(){
    if(!isset($_SESSION['username']))
        return "none";
    return "block";
}
function loggedOut(){
    if(!isset($_SESSION['username']))
        return "block";
    return "none";
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>League of Ended Friendships</title>
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
            <form action="phpCalls/processLogin.php"  method="post">
                Username: <input type="text" name="username" /> <br />
                Password: <input type="password" name="password"  /><br />
                <input type="submit" name="loginForm" value="Login!" />
            </form>
            <br />
            Admin Login Info: <br />
             - Username: jshallow <br />
             - Password: s3cr3t <br />
            Simple and clean User Login Info: <br />
             - Username: sanctuary <br />
             - Password: s3cr3t <br />
        </main>
        
        <div id="footer"></div>
    </body>
</html>