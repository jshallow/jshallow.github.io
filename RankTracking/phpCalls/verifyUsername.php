<?php
include '../../includes/dbConnection.php';
$conn = getDatabaseConnection("loef");


$sql = "SELECT username
        FROM users
        WHERE username = :username";
$statement = $conn->prepare($sql);
$statement->execute(array(":username"=>$_GET['username']));
$result = $statement->fetch(PDO::FETCH_ASSOC);  

echo json_encode($result);
?>