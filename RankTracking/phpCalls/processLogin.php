<?php
session_start();  //MUST be included whenever $_SESSION is used in the program

include '../../includes/dbConnection.php';

$conn = getDatabaseConnection("loef");

$username = $_POST['username'];
$password = sha1($_POST['password']);  // hash("sha1", $_POST['password']);

$sql = "SELECT * 
        FROM users
        WHERE username = :username
          AND password = :password";

$namedParameters = array();          
$namedParameters[':username'] = $username;  
$namedParameters[':password'] = $password;  

$statement = $conn->prepare($sql);
$statement->execute($namedParameters);
$record = $statement->fetch(PDO::FETCH_ASSOC);
//print_r($record);

    if (empty($record)) {  
        echo "Wrong username or password!";
        echo "<a href='../login.php'> Try again </a>";
    } else {
        // save session variables
        $_SESSION['username'] = $record['username'];
        $_SESSION['admin'] = $record['admin'];
        $_SESSION['updatedRank'] = false;
        // redirect  
        header('Location: ../userDash.php');        
        
    }
?>