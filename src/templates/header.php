<?php
if (session_status() == PHP_SESSION_NONE) {
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
        <div class="header-container">
            <a href="index.php" class="logo">
                <img src="images/bloghouse_logo.png" alt="Logo of the website" class="logo-image">
            </a>

            <div class="nav-login-container">
                <nav class="nav-menu" id="nav-menu">
                    <ul class="nav-list">
                        <li><a href="user.php">User Page</a></li>
                        <li><a href="#">About Us</a></li>
                    </ul>
                </nav>

                <div id="header-login-status">
                    <?php if (isset($_SESSION["username"])) : ?>
                    <li>
                        <span class="header-username">Logged in as: <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
                        <button class="header-logout-btn" type="button"
                            hx-post="data/logout.php"
                            hx-target="#header-login-status"
                            hx-swap="outerHTML">Log Out</button>
                    </li>
                    <?php else: ?>
                        <button id="open-login" class="header-btn">Login</button>
                        <button id="open-register" class="header-btn">Register</button>
                    <?php endif; ?>
                </div>
            </div>

            <button class="hamburger" id="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>
        

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
                    hx-swap="innerHTML" class="header-form">

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
            function attachModalEventListeners() {
                const loginBtn = document.getElementById('open-login');
                if (loginBtn) {
                    loginBtn.addEventListener('click', () => {
                        document.getElementById('login-modal').style.display = 'block';
                    });
                }

                const registerBtn = document.getElementById('open-register');
                if (registerBtn) {
                    registerBtn.addEventListener('click', () => {
                        document.getElementById('registration-modal').style.display = 'block';
                    });
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                const hamburger = document.getElementById('hamburger');
                const navMenu = document.getElementById('nav-menu');
                const openLogin = document.getElementById("open-login");
                const closeLogin = document.getElementById("close-login");
                const openRegister = document.getElementById("open-register");
                const closeRegister = document.getElementById("close-register");
                const loginModal = document.getElementById("login-modal");
                const registerModal = document.getElementById("registration-modal");

                hamburger.addEventListener('click', () => {
                    navMenu.classList.toggle('active');
                    hamburger.classList.toggle('active');
                });

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
            document.addEventListener('DOMContentLoaded', attachModalEventListeners);

            document.body.addEventListener('htmx:afterSwap', (event) => {
                if (event.detail.target.id === 'header-login-status') {
                    attachModalEventListeners();
                }
            });

            document.body.addEventListener('htmx:afterRequest', function(event) {
                if (event.target && event.target.id === 'login-form') {
                    if (event.detail.xhr.status === 200) {
                        document.getElementById('login-modal').style.display = 'none';
                    }
                }
            });
        </script>
    </header>