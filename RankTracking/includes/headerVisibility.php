<?php

// for logins
session_start();

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

function isAdmin(){
    if(isset($_SESSION['admin']) && $_SESSION['admin'])
        return "block";
    return "none";
}

?>