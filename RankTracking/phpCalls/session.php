<?php
session_start();
if (!isset($_SESSION['username'])) {
    
    header('Location: login.php'); //sends users back to login screen if they haven't logged in
    exit;
}

?>