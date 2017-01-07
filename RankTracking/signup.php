<?php

// for logins
session_start();

// for functions
include 'includes/headerVisibility.php';

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create Account - League of Progress</title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/custom.css" />
        
        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        
        <script>
            // for header and footer 
            $(function(){
              $("#header").load("includes/header.php"); 
              $("#footer").load("includes/footer.php"); 
            });
            
            // check if username is in use
            function checkUsername(){
                var isValid = true;
                
                if ($("#username").val().trim().length < 6) {
                    $("#usernameError").html("Username must be at least 6 characters!");
                    $("#usernameGood").html("");
                    isValid = false;
                } else if($("#username").val().trim().length > 16) {
                    $("#usernameError").html("Username must be less than 16 characters!");
                    $("#usernameGood").html("");
                    isValid = false;
                }
                else{
                    $.ajax({
                        type: "get",
                        url: "phpCalls/verifyUsername.php",
                        dataType: "json",
                        data: { "username": $("#username").val() },
                        success: function(data,status) {
                            //alert(data);  
                            if (!data){
                                $("#usernameError").html("");
                                $("#usernameGood").html("Username is available!");
                            } else{
                                $("#usernameError").html("Username is unavailable!");
                                $("#usernameGood").html("");
                                isValid = false;
                            }
                          },
                          complete: function(data,status) { //optional, used for debugging purposes
                              //alert(status);
                          }
                      });//AJAX
                }
                
                return isValid;
            }
            
            function checkPassword(){
                var isValid = true;
                
                // check password length
                if ($("#password").val().trim().length < 6) {
                    $("#passwordError").html("Password must be at least 6 characters!");
                    isValid = false;
                }
                else if ($("#password").val().trim().length > 12) {
                    $("#passwordError").html("Password less than 12 characters!");
                    isValid = false;
                }
                // check that password contains a number and one uppercase letter
                else if(/^(?=.{6,}$)(?=.*[A-Za-z])(?=.*[0-9]).*$/.test($("#password").val()) == false){
                    $("#passwordError").html("Password must contain at least 1 digit and 1 letter!");
                    isValid = false;
                } 
                else{
                    $("#passwordError").html("");
                    isValid = true;
                }
                
                return isValid;
            }
            
            function checkPasswordValidation(){
                var isValid = true;
                
                if ($("#passwordV").val() != $("#password").val()) {
                    $("#passwordValidationError").html("Passwords must match!");
                    isValid = false;
                }
                
                return isValid;
            }
            
            function checkSummonerName(){
                var isValid = true;
                
                if ($("#summonerName").val().trim().length > 16) {
                    $("#summonerNameError").html("Summoner Name must be less than 16 characters!");
                    $("#summonerNameGood").html("");
                    isValid = false;
                } else if ($("#summonerName").val().trim().length < 1) {
                    $("#summonerNameError").html("Enter a Summoner Name!");
                    $("#summonerNameGood").html("");
                    isValid = false;
                }else{
                    $.ajax({
                        type: "get",
                        url: "phpCalls/verifySummonerName.php",
                        dataType: "json",
                        data: { "summonerName": $("#summonerName").val() },
                        success: function(data,status) {
                            //alert(data);  
                            if (!data){
                                $("#summonerNameError").html("");
                                $("#summonerNameGood").html("Summoner Name is available!");
                            } else{
                                $("#summonerNameError").html("Summoner Name is unavailable!");
                                $("#summonerNameGood").html("");
                                isValid = false;
                            }
                          },
                          complete: function(data,status) { //optional, used for debugging purposes
                              //alert(status);
                          }
                      });//AJAX
                      // check if summoner name exists
                      $.ajax({
                        type: "get",
                        url: "phpCalls/verifySummonerName.php",
                        dataType: "json",
                        data: { "summonerName": $("#summonerName").val() },
                        success: function(data,status) {
                            //alert(data);  
                            if (!data){
                                $("#summonerNameError").html("");
                                $("#summonerNameGood").html("Summoner Name is available!");
                            } else{
                                $("#summonerNameError").html("Summoner Name is unavailable!");
                                $("#summonerNameGood").html("");
                                isValid = false;
                            }
                          },
                          complete: function(data,status) { //optional, used for debugging purposes
                              //alert(status);
                          }
                      });//AJAX
                }
                
                return isValid;
            }
            
            function validateForm(){
                return checkUsername() && 
                    checkPassword() && 
                    checkPasswordValidation() && 
                    checkSummonerName();
            }
            
            $(document).ready(function(){
                $("#username").change(function(){
                    checkUsername();
                });
                $("#password").change(function(){
                   checkPassword(); 
                });
                $("#passwordV").change(function(){
                   checkPasswordValidation(); 
                });
                $("#summonerName").change(function(){
                   checkSummonerName(); 
                });
            });
        </script>
    </head>
    
    <body>
        <div id="header"></div>
        
        <main>
            <form onsubmit="return validateForm()" action="phpCalls/addUser.php" id="signupForm" method="post">
            <fieldset>
                <legend>Sign Up</legend>
                <table>
                    <tr>
                        <td>Username: </td>
                        <td>
                            <input type="text" id="username" name="username" />
                                        <span id="usernameError" class="error"></span>
                                        <span id="usernameGood" class="available"></span><br /> 
                        </td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td>
                            <input type="password" id="password" name="password">
                                <span id="passwordError" class="error"></span><br /> 
                        </td>
                    </tr>
                    <tr>
                        <td>Type Password Again: </td>
                        <td>
                            <input type="password" id="passwordV" name="passwordV">
                            <span id="passwordValidationError" class="error"></span><br /> 
                        </td>
                    </tr>
                    <tr>
                        <td>Summoner Name:  </td>
                        <td>
                            <input type="text" id="summonerName" name="summonerName" />
                            <span id="summonerNameError" class="error"></span><br />  
                            <!-- Hidden -->
                            <span id="summonerID" name="summonerID" style="display:none"></span><br />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" value="Sign up!" name="addUser" />
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>
        </main>
    
        <div id="footer"></div>
    </body>
</html>