<?php

//check the session is active
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); //sends users back to login screen if they haven't logged in
    exit;
}

// db connection
include "../../includes/dbConnection.php";
$dbConn = getDatabaseConnection('loef');

$sql = "DELETE FROM users
        WHERE userID = " . $_GET['userID'];
        
echo "userID: " . $_GET['userID'];
        
$statement = $dbConn->prepare($sql);
$statement->execute();

header("Location: ../adminDash.php");
?>