<?php
require "data/dbconn.php";
require "data/post.php";


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/style.css">
    <script src="https://unpkg.com/htmx.org@2.0.3"></script>
</head>
<body>
    <header>
        <div class="header-field">
            <ul class="header-list">
                <li>
                    <a href="index.php">
                        <img class="logo-image" src="images/bloghouse_logo.png" alt="Logo of the website">   
                    </a>
                </li>
                <li>
                    <a class="header-options" href="http://">User Page</a>
                    <a class="header-options" href="http://">About Us</a>
                    <button id="open-login" class="header-btn">Login</button>
                    <div id="login-modal" class="modal" style="display: none;">
                        <div class="modal-content">
                            <span class="close" id="close-login">&times;</span>
                            <h3>Login</h3>
                            <form hx-post="data/login.php" hx-target="#login-feedback" hx-swap="innerHTML" class="header-form">
                                <label for="username">Username:</label>
                                <input type="text" name="username" id="username">
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password">
                                <button type="submit">Login</button>
                            </form>
                            <div id="login-feedback"></div>
                        </div>
                    </div>
                    <button id="open-register" class="header-btn">Register</button>
                    <div id="registration-modal" class="modal" style="display: none;">
                        <div class="modal-content">
                            <span class="close" id="close-register">&times;</span>
                            <h3>Register</h3>
                            <form hx-post="data/register.php" hx-target="#register-feedback" hx-swap="innerHTML" class="header-form">
                                <label for="username">Username:</label>
                                <input type="text" name="username" id="username">
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password">
                                <button type="submit">Register</button>
                            </form>
                            <div id="registration-feedback"></div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <script>
            document.getElementById("open-login").onclick = function() {
                document.getElementById("login-modal").style.display = "block";
            }
            document.getElementById("close-login").onclick = function() {
                document.getElementById("login-modal").style.display = "none";
            }
            document.getElementById("open-register").onclick = function() {
                document.getElementById("registration-modal").style.display = "block";
            }
            document.getElementById("close-register").onclick = function() {
                document.getElementById("registration-modal").style.display = "none";
            }

            window.onclick = function(event) {
                if(event.target == document.getElementById("login-modal")) {
                    document.getElementById("login-modal").style.display = "none";
                }
                if(event.target == document.getElementById("registration-modal")) {
                    document.getElementById("registration-modal").style.display = "none";
                }
            }
        </script>