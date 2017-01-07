<?php
include '../../includes/dbConnection.php';
$conn = getDatabaseConnection("loef");


$sql = "SELECT summonerName
        FROM users
        WHERE summonerName = :summonerName";
$statement = $conn->prepare($sql);
$statement->execute(array(":summonerName"=>$_GET['summonerName']));
$result = $statement->fetch(PDO::FETCH_ASSOC);  

echo json_encode($result);
?>