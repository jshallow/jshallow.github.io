<?php
// for logins
session_start();

// for databases
include '../includes/dbConnection.php';
$conn = getDatabaseConnection("loef");
$record = array();
// for functions
include 'includes/headerVisibility.php';
include 'phpCalls/rankCheck.php';

function welcomeAdmin(){
    
    printData();
}

function printAllUsers(){
    global $conn, $record;
    
    $sql = "SELECT userID, username, summonerName, lastRank, admin
            FROM users";
    
    $statement = $conn->prepare($sql);
    $statement->execute();
    $record = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div>";
    echo "<h2>Users</h2>";
    echo "<td>
                <form action='signup.php'>
                       <input type='submit' value='Add User'>
                    </form>
              </td>";
    echo "<table class='table table-bordered table-striped'>";
    echo "<tr><th>Username</th><th>Summoner Name</th><th>Rank</th><th>Admin</th><th></th><th></th></tr>";
    for($i = 0; $i < count($record); $i++){
        echo "<tr>";
        echo "<td>" . $record[$i]['username'] . "</td>";
        echo "<td>" . $record[$i]['summonerName'] . "</td>";
        echo "<td>" . $record[$i]['lastRank'] . "</td>";
        echo "<td>" . ($record[$i]['admin'] == 1 ? "Yes" : "No") . "</td>";
        echo "<td>
                <form action='phpCalls/deleteUser.php' 
                onsubmit='return confirmDelete(\"".$record[$i]['username']."\")'>
                       <div style='font-style:0'> <input type='hidden' name='userID' value='". $record[$i]['userID']."' style='display:none'></div>
                       <input type='submit' value=Delete>
                    </form>
              </td>";
        echo "<td>
            <form action='phpCalls/makeAdmin.php' 
            onsubmit='return confirmAdmin(\"".$record[$i]['username']."\")'>
                       <div style='font-style:0'> <input type='hidden' name='userID' value='". $record[$i]['userID']."' style='display:none'></div>
                   <input type='submit' value='Make Admin'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
}

function printAllTeams(){
    global $conn, $record;
    
    $sql = "SELECT teamName, teamTag, isActive, top, jungle, mid, bot, support, sub1, sub2, sub3
            FROM teams";
    
    $statement = $conn->prepare($sql);
    $statement->execute();
    $record = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div>";
    echo "<h2>Collegiate Teams</h2>";
    echo "<form action='editTeam.php'>
           <input type='submit' value='Edit Team'>
           </form>";
    echo "<table class='table table-bordered table-striped'>";
    echo "<thead>";
    echo "<tr><th>Team Name</th><th>Team Tag</th><th>Activity</th><th>Top</th><th>Jungle</th><th>Mid</th><th>Bot</th><th>Support</th><th>Sub 1</th><th>Sub 2</th><th>Sub 3</th>";
    echo "</thead>";
    echo "<tbody>";
    for($i = 0; $i < count($record); $i++){
        echo "<tr>
                <td>" . $record[$i]['teamName'] . "</td>
                <td>" . $record[$i]['teamTag'] . "</td>
                <td>" . ($record[$i]['isActive'] == 1 ? "Active" : "Inactive") . "</td>
                <td>" . $record[$i]['top'] . "</td>
                <td>" . $record[$i]['jungle'] . "</td>
                <td>" . $record[$i]['mid'] . "</td>
                <td>" . $record[$i]['bot'] . "</td>
                <td>" . $record[$i]['support'] . "</td>
                <td>" . $record[$i]['sub1'] . "</td>
                <td>" . $record[$i]['sub2'] . "</td>
                <td>" . $record[$i]['sub3'] . "</td>
              </tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
}

// 3 aggregate function calls
function printUserRankInfo(){
    echo "<div class='aggregatedStats'>";
    printNumUsers();
    printAvgRank();
    printHighestRank();
    echo "</div>";
}

function printAvgRank(){
    global $conn, $record;
    
    $sql = "SELECT AVG(lastRankInt)
            FROM users";
    
    $statement = $conn->prepare($sql);
    $statement->execute();
    $record = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    $avg = $record[0]['AVG(lastRankInt)'];
    if(floor($avg) == round($avg))
        echo "Average Rank: " . intToRank(floor($avg)) . "<br />";
    else
        echo "Average Rank: " . intToRank(floor($avg)) . 
             " - " . intToRank(round($avg)) . "<br />";
}

function printHighestRank(){
    global $conn, $record;
    
    $sql = "SELECT MAX(lastRankInt)
            FROM users";
    
    $statement = $conn->prepare($sql);
    $statement->execute();
    $record = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Highest Rank: " . intToRank($record[0]['MAX(lastRankInt)']) . "<br />";
}

function printNumUsers(){
    global $conn;
    
    $sql = "SELECT COUNT(*)
            FROM users";
    
    $statement = $conn->prepare($sql);
    $statement->execute();
    $record = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Number of Registered Users: " . $record[0]['COUNT(*)'] . "<br />";
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Admin Dashboard - League of Progress</title>
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
            
            // remove whitespace from buttons
            $("#remove_br").find('br').remove()

            function confirmDelete(firstName) {
                return confirm("Are you sure you wanna delete " + firstName + "?");
            }
            
            function confirmAdmin(firstName) {
                return confirm("Are you sure you wanna make " + firstName + " an Admin?");
            }
        </script>
    </head>
    
    <body>
        <div id="header"></div>
        
        <main>
            <?=printAllUsers()?>
            <?=printAllTeams()?>
            <?=printUserRankInfo()?>
        </main>
    
        <div id="footer"></div>
    </body>
</html>