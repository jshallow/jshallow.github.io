<?php
// must have an active session
session_start();
include 'rankCheck.php';

//updateRank();

echo "yeah we here now";
session_destroy();
// return home
header("Location: ../index.php");

?>