<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
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
                    <a class="header-options" href="user.php">User Page</a>
                    <a class="header-options" href="http://">About Us</a>

                    <?php if (isset($_SESSION["username"])) : ?>
                        <span class="header-username">Logged in as: <?php echo $_SESSION["username"]; ?></span>
                        <button class="header-logout-btn" type="submit"
                            hx-post="data/logout.php"
                            hx-target="#login-feedback">Log Out</button>
                    <?php else: ?>
                        <button id="open-login" class="header-btn">Login</button>
                        <button id="open-register" class="header-btn">Register</button>
                    <?php endif; ?>
                    <!-- Login Modal -->
                    <div id="login-modal" class="modal" style="display: none;">
                        <div class="modal-content">
                            <span class="close" id="close-login">&times;</span>
                            <div class="modal-header">
                                <h3>Login</h3>
                            </div>
                            <form hx-post="data/login.php"
                                hx-target="#login-feedback"
                                id="login-form"
                                hx-swap="innerHTML" class="header-form"
                                hx-on::after-request="
                                    let form = document.getElementById('login-form'); 
                                    if (form) {
                                        form.querySelectorAll('input').forEach(input => input.value = ''); 
                                        let firstInput = form.querySelector('input');
                                        if (firstInput) firstInput.focus();
                                    }
                                ">
                                <label for="username">Username:</label>
                                <input type="text" name="username" id="username">
                                <label for="password">Password:</label>
                                <input type="password" name="password" id="password">
                                <button type="submit">Login</button>
                            </form>
                            <div id="login-feedback"></div>
                        </div>
                    </div>

                    <!-- Registration Modal -->
                    <div id="registration-modal" class="modal" style="display: none;">
                        <div class="modal-content">
                            <span class="close" id="close-register">&times;</span>
                            <div class="modal-header">
                                <h3>Register</h3>
                            </div>
                            <form hx-post="data/register.php"
                                hx-target="#registration-feedback"
                                id="register-form"
                                hx-swap="innerHTML" class="header-form"
                                hx-on::after-request="
                                    let form = document.getElementById('register-form'); 
                                    if (form) {
                                        form.querySelectorAll('input').forEach(input => input.value = ''); 
                                        let firstInput = form.querySelector('input');
                                        if (firstInput) firstInput.focus();
                                    }
                                ">
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
            document.addEventListener("DOMContentLoaded", function() {
                const openLogin = document.getElementById("open-login");
                const closeLogin = document.getElementById("close-login");
                const openRegister = document.getElementById("open-register");
                const closeRegister = document.getElementById("close-register");
                const loginModal = document.getElementById("login-modal");
                const registerModal = document.getElementById("registration-modal");

                if (openLogin && loginModal) {
                    openLogin.onclick = function() {
                        loginModal.style.display = "block";
                    };
                }

                if (closeLogin && loginModal) {
                    closeLogin.onclick = function() {
                        loginModal.style.display = "none";
                    };
                }

                if (openRegister && registerModal) {
                    openRegister.onclick = function() {
                        registerModal.style.display = "block";
                    };
                }

                if (closeRegister && registerModal) {
                    closeRegister.onclick = function() {
                        registerModal.style.display = "none";
                    };
                }

                window.onclick = function(event) {
                    if (event.target == loginModal) {
                        loginModal.style.display = "none";
                    }
                    if (event.target == registerModal) {
                        registerModal.style.display = "none";
                    }
                };
            });
        </script>
    </header>