<?php

// for logins
session_start();

// for functions
include 'headerVisibility.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <style>
            #loggedIn{
                display: <?=loggedIn()?>;
                }
            #loggedOut{
                display: <?=loggedOut()?>;
            }
            #adminDash{
                display: <?=isAdmin()?>;
            }
        </style>
    </head>
    
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Brand</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
              <li><a href="index.php">Home</a></li>
              <li><a href="users.php">Users</a></li>
              <li id="loggedIn"><a href="userDash.php">User Dashboard</a></li>
              <li id="adminDash"><a href="adminDash.php">Admin Dashboard</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right" id="loggedOut">
            <li><a href="signup.php">Create Account</a></li>
            <li><a href="login.php">Login</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right" id="loggedIn">
            <li><a href="phpCalls/logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>
</html>