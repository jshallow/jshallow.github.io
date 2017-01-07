<?php
// for logins
session_start();

// for database
include '/home/ubuntu/workspace/includes/dbConnection.php';
$conn = getDatabaseConnection("loef");
$record = array();

global $conn, $record;

$sql = "SELECT username, rank, date
        FROM rankedProgress
        WHERE username = :username";

$namedParameters = array();          
$namedParameters[':username'] = $_SESSION['username'];  

$statement = $conn->prepare($sql);
$statement->execute($namedParameters);
$record = $statement->fetchAll(PDO::FETCH_ASSOC);
    
function printProgress(){
    global $record;
    
    echo "<div class='col-lg-4'>";
    echo "<h2>Ranked Progress</h2>";
    echo "<table class='table table-bordered table-striped'>";
    echo "<thead>";
    echo "<tr><th>Rank</th><th>Date</th></tr>";
    echo "</thead>";
    echo "<tbody>";
    for($i = 0; $i < count($record); $i++){
        echo "<tr>
                <td>" . $record[$i]['rank'] . "</td>
                <td>" . $record[$i]['date'] . "</td>
              </tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    
}

?>