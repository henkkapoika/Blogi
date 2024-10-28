<?php
session_start();
if (isset($_SESSION["username"])) : ?>
    <span class="header-username">Logged in as: <?php echo $_SESSION["username"]; ?></span>
    <button class="header-logout-btn" 
            hx-post="../data/logout.php"
            hx-target="#user-login-wrapper"
            hx-trigger="click"
            hx-swap="outerHTML"
            >Log Out</button>
<?php else : ?>
    <button id="open-login" class="header-btn">Login</button>
    <button id="open-register" class="header-btn">Register</button>
<?php endif; ?>

